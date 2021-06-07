<?php

namespace app\modules\photogallery\modules\admin\controllers;

use Yii;
use app\modules\photogallery\models\CategoryDeleteForm;
use app\modules\photogallery\models\Category;
use app\modules\photogallery\models\Image;
use app\modules\photogallery\models\CategorySearch;
use app\modules\photogallery\models\ImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    /*public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }*/

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 10]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        $model = new Category();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->count = 0;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);  
            } 
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        $model = $this->findModel($id);
        $images = Image::find()->where(['category' => $model->title])->all();

        if ($model->load(Yii::$app->request->post())) {
            $new_category = $model->title;

            foreach ($images as $image) {
                $image->category = $new_category;
                $image->update(false);
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        $category = $this->findModel($id);
        $model = new CategoryDeleteForm();

        if ($model->load(Yii::$app->request->post())) {
            if (($model->action === "move" && $model->category === "") || ($model->action === "move" && $model->category === $category->title)) {
                Yii::$app->getSession()->setFlash('error','Category missed!');
                return $this->redirect(['delete','id' => $id]);
            } else{
                if ($model->action === "move") {
                    $query = Image::find()->where(['category' => $category->title])->all();
                    $q = 0;

                    foreach ($query as $item) {
                        $item->category = $model->category;
                        $q++;
                        $item->update(false);
                    }

                    $new_category = Category::findOne(['title' => $model->category]);
                    $new_category->count = $new_category->count + $q;
                    
                    if ($new_category->save()) {
                        $category->delete();
                        Yii::$app->getSession()->setFlash('success','Images were updated!');
                        return $this->redirect(['view','id' => $new_category->id]);
                    }

                } else if ($model->action === "delete") {
                    $query = Image::find()->where(['category' => $category->title])->all();
                    $q = 0;
                    $count = count($query);

                    foreach ($query as $item) {
                        unlink("images/photogallery/".$item->image);
                        $item->delete();
                        $q++;    
                    }

                    if ($q == $count) {
                        $category->delete();
                        Yii::$app->getSession()->setFlash('success','Category and images were deleted!');
                        return $this->redirect(['/photo/admin/category/index']);
                    }

                }
            }
        }

        return $this->render('delete', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }
        
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionImages($cat)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }
        
        $query = Image::find()->where(['category' => $cat]);
        $category = Category::findOne(['title' => $cat]);
        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        
        return $this->render('images',[
            'category' => $category,
            'models' => $models,
            'pages' => $pages,
        ]);
    }
}

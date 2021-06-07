<?php

namespace app\modules\photogallery\modules\admin\controllers;

use Yii;
use app\modules\photogallery\models\Image;
use app\modules\photogallery\models\ImageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\modules\photogallery\models\Category;

/**
 * ImageController implements the CRUD actions for Image model.
 */
class ImageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Image models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        $searchModel = new ImageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Image model.
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
     * Creates a new Image model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }

        $model = new Image();

        if ($model->load(Yii::$app->request->post())) {
            $model->load_image = UploadedFile::getInstance($model, 'load_image');
            $extension = "";
            if ($model->upload()) {
                $extension = $model->load_image->extension;
                $model->extension = $extension;
                $date = date("d-m-Y H-i-s");
                $model->date = $date;
            }

            if ($model->save()) {
                $model->image = $model->id.".".$extension;
                if ($model->save()) {

                    if ($model->watermark == "none") {
                        Yii::$app->getSession()->setFlash('success','Image successfuly saved :)');
                        $model->load_image->saveAs("images/photogallery/{$model->id}.{$extension}");
                    } else{

                        $image_path = "images/photogallery/".$model->load_image;
                        $model->load_image->saveAs("images/photogallery/{$model->load_image}");
                        $size = getimagesize($image_path);

                        switch ($extension) {
                            case 'jpg':
                            case 'jpeg':
                                $img = imagecreatefromjpeg($image_path);
                            break;

                            case 'png':
                                $img = imagecreatefrompng($image_path);
                            break;

                            case 'gif':
                                $img = imagecreatefromgif($image_path);
                            break;
                        }

                        $water = imagecreatefrompng("images/photogallery/watermark.png");

                        $res_width  = $size[0];
                        $res_height = $size[1];

                        $water_width  = imagesx($water);
                        $water_height = imagesy($water);

                        $res_img = imagecreatetruecolor($res_width, $res_height);
                        imagecopyresampled($res_img, $img, 0, 0, 0, 0, $res_width, $res_height, $size[0], $size[1]);

                        switch ($model->watermark) {
                            case 'right_bot':
                                imagecopy($res_img, $water, $res_width - $water_width, $res_height - $water_height, 0, 0, $water_width, $water_height);            
                            break;
                            
                            case 'left_bot':
                                imagecopy($res_img, $water, 0, $res_height - $water_height, 0, 0, $water_width, $water_height);
                            break;

                            case 'right_top':
                                imagecopy($res_img, $water, $res_width - $water_width, 0, 0, 0, $water_width, $water_height);
                            break;
                            
                            case 'left_top':
                                imagecopy($res_img, $water, 0, 0, 0, 0, $water_width, $water_height);
                            break;
                        }
                        $checkFlag = false;
                        if ($extension == "gif") {
                            imagegif($res_img, "images/photogallery/$model->id.$extension", 100);
                            $checkFlag = true;
                        } else if ($extension == "png") {
                            imagepng($res_img, "images/photogallery/$model->id.$extension", 9);
                            $checkFlag = true;
                        } else if ($extension == "jpg" || $extension == "jpeg") {
                            imagejpeg($res_img, "images/photogallery/$model->id.$extension", 100);
                            $checkFlag = true;
                        }

                        if ($checkFlag) {
                            imagedestroy($water);
                            imagedestroy($res_img);
                            unlink($image_path);
                            $category = Category::findOne(['title' => $model->category]);
                            $category->count = $category->count + 1;
                            $category->save();
                            Yii::$app->getSession()->setFlash('success','Image successfuly saved :)');
                        } else{
                            unlink($image_path);
                            Image::findOne(['image' => $model->image])->delete();
                            Yii::$app->getSession()->setFlash('error','If you see this message, tell us about it :(');
                        }

                    }
        
                    return $this->redirect(['view', 'id' => $model->id]);
                }  
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Image model.
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
        $model_category = $model->category;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->status == "" || $model->title == "" || $model->category == "") {
                Yii::$app->getSession()->setFlash('error','Something missed!');
            } else{
                $form_category = $model->category;
                if ($model->save(false)) {
                    
                    if ($model_category !== $form_category) {
                        $category = Category::findOne(['title' => $model_category]);
                        $category->count = $category->count - 1;
                        $category->save();

                        $category = Category::findOne(['title' => $form_category]);
                        $category->count = $category->count + 1;
                        $category->save();
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Image model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        } else {
            $model = Image::findOne($id);
            $category = Category::findOne(['title' => $model->category]);
            $image_path = $model->image;
            
            if ($model->delete()) {
                $category->count = $category->count - 1;
                $category->save();

                unlink("images/photogallery/$image_path");
            }

            Yii::$app->getSession()->setFlash('success','Image was successfuly deleted!');

            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the Image model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Image the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
            return $this->goHome();
        }
        
        if (($model = Image::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

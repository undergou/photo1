<?php

namespace app\modules\photogallery\modules\page\controllers;

use Yii;
use app\modules\photogallery\models\Category;
use app\modules\photogallery\models\Image;
use yii\web\Controller;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `page` module
 */
class PageController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($page)
    {
    	//Yii::$app->user->identity->username == "demo"
    	if (Yii::$app->user->isGuest) {
    		$query = Category::find()->where(['status' => 'guest'])->orderBy('id');
    	} else if (Yii::$app->user->identity->username == "demo") {
    		$query = Category::find()->where(['status' => 'guest'])->orWhere(['status' => 'user'])->orderBy('id');
    	} else if (Yii::$app->user->identity->username == "admin") {
    		$query = Category::find()->where(['status' => 'guest'])->orWhere(['status' => 'user'])->orWhere(['status' => 'admin'])->orderBy('id');
    	}
    	
    	$dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'pagination' => [
		    	'pageSize' => 10,
		    	'forcePageParam' => false,
		    	'pageSizeParam' => false,
		    ],
		]);

    	$countQuery = clone $query;
	    $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10, 'forcePageParam' => false, 'pageSizeParam' => false]);
	    $models = $query->offset($pages->offset)
	        ->limit($pages->limit)
	        ->all();
	    $amountModels = count($models);


        return $this->render('index',[
        	'dataProvider' => $dataProvider,
        	'models' => $models,
        	'pages' => $pages,
            'page' => $page,
        ]);
    }

    public function actionCategory($slug, $page = 1)
    {
        $category = Category::find()->where(['slug' => $slug])->one();
        if (!$category) {
            throw new \yii\web\HttpException(404,'Page not found!');
        }

        if ($category->status == "user") {
            if (Yii::$app->user->isGuest) {
                throw new \yii\web\HttpException(403,'You do not have the right to access this page!');
            }
        } else if ($category->status == "admin") {
            if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
                throw new \yii\web\HttpException(403,'You do not have the right to access this page!');
            }
        }

        if (Yii::$app->user->isGuest) {
            $query = Image::find()->where(['status' => 'guest'])->andWhere(['category' => $category->title])->orderBy('id');
        } else if (Yii::$app->user->identity->username == "demo") {
            $query = Image::find()->where(['status' => 'guest'])->orWhere(['status' => 'user'])->andWhere(['category' => $category->title])->orderBy('id');
        } else if (Yii::$app->user->identity->username == "admin") {
            $query = Image::find()->where(['status' => 'guest'])->orWhere(['status' => 'user'])->orWhere(['status' => 'admin'])->andWhere(['category' => $category->title])->orderBy('id');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
                'forcePageParam' => false,
                'pageSizeParam' => false,
            ],
        ]);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 10, 'forcePageParam' => false, 'pageSizeParam' => false]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        $amountModels = count($models);

        return $this->render('category',[
            'dataProvider' => $dataProvider,
            'models' => $models,
            'pages' => $pages,
            'category' => $category,
            'page' => $page,
        ]);
    }

}

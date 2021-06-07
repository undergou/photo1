<?php

namespace app\modules\photogallery\modules\admin\controllers;

use Yii;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	if (Yii::$app->user->isGuest || Yii::$app->user->identity->username == "demo") {
    		return $this->goHome();
    	}

    	return $this->redirect('/photo/admin/category/index');
    }
}

<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\photogallery\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\photogallery\models\Image */

$this->title = 'Update Image: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Images', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="image-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="image-form">

	    <?php $form = ActiveForm::begin([
	        'id' => 'image',
	        'layout' => 'default'
	    ]); ?>

	    <?= $form->field($model, 'title', ['inputOptions' => ['id' => 'image-title']])->textInput(['maxlength' => true]) ?>

	    <?php
	        $categories = Category::find()->all();
	        $items = ArrayHelper::map($categories,'title','title');
	        $options = [
	            'prompt' => 'Select category...',
	        ];
	    ?>

	    <?= $form->field($model, 'category', ['inputOptions' => ['id' => 'image-category']])->dropDownList($items, $options) ?>
	    
	    <?php
	        $items = [
	            'guest' => 'Guest',
	            'user' => 'User',
	            'admin' => 'Admin',
	            'link' => 'Link',
	        ];

	        $params = [
	            'prompt' => 'Select status...',
	        ];
	    ?>

	    <?= $form->field($model, 'status', ['inputOptions' => ['id' => 'status']])->dropDownList($items, $params) ?>

	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>
	    
	</div>

</div>

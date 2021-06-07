<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\photogallery\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\photogallery\models\Image */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="image-form">

    <?php $form = ActiveForm::begin([
        'id' => 'w0',
        'layout' => 'default'
    ]); ?>

    <?= $form->field($model, 'author', ['inputOptions' => ['id' => 'image-author']])->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'status', ['inputOptions' => ['id' => 'image-status']])->dropDownList($items, $params) ?>

    <?php
        $items = [
            'none' => 'None',
            'left_top' => 'Top left',
            'right_top' => 'Top right',
            'left_bot' => 'Bottom left',
            'right_bot' => 'Bottom right',
        ];

        $params = [
            'prompt' => 'Select watermark position...',
        ];
    ?>

    <?= $form->field($model, 'watermark', ['inputOptions' => ['id' => 'image-watermark']])->dropDownList($items, $params) ?>

    <?= $form->field($model, 'load_image', ['inputOptions' => ['id' => 'image-file']])->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

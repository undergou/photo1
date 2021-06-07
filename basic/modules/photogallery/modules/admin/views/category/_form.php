<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\photogallery\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php $form = ActiveForm::begin([
        'id' => 'category',
        'layout' => 'default'
    ]); ?>

    <?= $form->field($model, 'title', ['inputOptions' => ['id' => 'categouytrtyhry-title']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug', ['inputOptions' => ['id' => 'category-slug']])->textInput(['maxlength' => true]) ?>

    <?php
        $items = [
            'guest' => 'Guest',
            'user' => 'User',
            'admin' => 'Admin',
            'link' => 'Link',
        ];
    ?>

    <?= $form->field($model, 'status', ['inputOptions' => ['id' => 'category-status']])->dropDownList($items) ?>

    <!--$form->field($model, 'count')->textInput()-->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

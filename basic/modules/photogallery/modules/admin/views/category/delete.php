<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\photogallery\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\photogallery\models\Category */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Delete Category: ' . $category->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = 'Delete';
?>
<head>
    <style>
        #category-to-move{
            display: none;
        }
    </style>
</head>

<h1><?= Html::encode($this->title) ?></h1>

<div class="category-delete">

    <?php $form = ActiveForm::begin([
        'id' => 'form-to-move',
        'layout' => 'default'
    ]); ?>

    <?php
        $items = [
            'delete' => 'Delete',
            'move' => 'Move',
        ];

        $params = [
            'prompt' => 'Select action...',
        ];
    ?>

    <?= $form->field($model, 'action', ['inputOptions' => ['id' => 'category-action']])->dropDownList($items, $params) ?>

    <?php
        $categories = Category::find()->all();
        $items = ArrayHelper::map($categories,'title','title');
        $options = [
            'prompt' => 'Select category...',
        ];
    ?>

    <?= $form->field($model, 'category', ['inputOptions' => ['id' => 'category-to-move']])->dropDownList($items, $options) ?>

    <div class="form-group">
        <?= Html::submitButton('Remove', ['class' => 'btn btn-danger']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    document.getElementById('category-action').setAttribute('onchange','output()');
    document.getElementsByClassName('control-label')[1].style.display = "none";

    var items = document.getElementById('category-to-move').options;
    var bad_category = "<?= $category->title ?>";
    
    for (let i = 0; i < items.length; i++) {
        if (items[i].value === bad_category) {
            items[i].style.display = "none";
        }
    }

    function output(){
        let n = document.getElementById('category-action').options.selectedIndex;
        let item = document.getElementById('category-action').options[n].value;
        
        if (item == "delete" || item == "") {
            document.getElementById('category-to-move').style.display = "none";
            document.getElementsByClassName('control-label')[1].style.display = "none";
        } else if (item == "move") {
            document.getElementById('category-to-move').style.display = "block";
            document.getElementsByClassName('control-label')[1].style.display = "inline-block";
        }
    }
</script>

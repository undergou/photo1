<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\modules\photogallery\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\photogallery\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">
    <style>
        .category-title{
            font-weight: bold;
        }
    </style>

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Create Image', ['/photo/admin/image/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'slug',
            //'status',
            'count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>

<script type="text/javascript">
    var tds = document.getElementsByTagName('td');
    console.log(tds);
    
    for (let i = 8; i < tds.length; i++) {
        if (i % 6 == 2) {
            let href = "<a href='/photo/admin/category/images?cat=" + tds[i].innerHTML + "' class='category-title'>" + tds[i].innerHTML + "</a>";
            tds[i].innerHTML = href;
            
        }
    }
</script>

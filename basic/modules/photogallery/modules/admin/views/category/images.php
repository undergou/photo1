<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\photogallery\models\Image;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\photogallery\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Category Images: '.$category->title;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['view', 'id' => $category->id]];
$this->params['breadcrumbs'][] = "Images";
?>
<div class="category-index">
<style type="text/css">
    .action{
        margin-right: 10px;
    }
</style>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
        if (count($models) == 0) {
            echo "No images...";
        } else {
    ?>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
                <th>File name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                }
                foreach ($models as $model) {
                    echo "<tr data-key='$model->id'>";
                        echo "<td>$model->id</td>";
                        echo "<td>$model->title</td>";
                        echo "<td>$model->status</td>";
                        echo "<td>$model->image</td>";
                        echo "<td>";
                            echo "<a href='/photo/admin/image/view?id=$model->id' title='View' aria-label='View' data-pjax='0' class='action'><span class='glyphicon glyphicon-eye-open'></span></a>";
                            echo "<a href='/photo/admin/image/update?id=$model->id' title='Update' aria-label='Update' data-pjax='0' class='action'><span class='glyphicon glyphicon-pencil'></span></a>";
                            echo "<a href='/photo/admin/image/delete?id=$model->id' title='Delete' aria-label='Delete' data-pjax='0' data-confirm='Are you sure you want to delete this item?' data-method='post' class='action'><span class='glyphicon glyphicon-trash'></span></a>";
                        echo "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>

    <?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
</div>

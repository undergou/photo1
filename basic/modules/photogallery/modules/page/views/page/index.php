<?php
	use yii\helpers\Html;
	use yii\grid\GridView;
	use app\modules\photogallery\models\Image;
	use yii\widgets\LinkPager;
	use yii\widgets\Pjax;
	use yii\widgets\ListView;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<style>
		#container{
			border: 5px solid black;
			display: flex;
			justify-content: space-around;
			flex-wrap: wrap;
			padding-top: 20px;
			border-radius: 30px;
		}
		.category-title{
			cursor: pointer;
			border: 1px solid black;
			width: 200px;
			margin-bottom: 20px;
			text-align: center;
		}

		.category-image{
			width: 199px;
			height: 199px;
			display: block;
			border: 1px solid black;
			border-left: none;
			border-top: none;
		}

		.span-elem{
			display: block;
			width: 199px;
			color: white;
			background-color: black;
			padding: 10px 5px;
			border: 1px solid black;
			text-align: center;
			font-size: 20px;
			opacity: 85%;
		}

		.span-elem a{
			color: white;
			text-decoration: none;
		}
	</style>
</head>
<body>

	<h1>Categories</h1>

	<?php
		if (!count($models)) {
			echo "<h2>No categories :(</h2>";
		} else {
	?>

	<?= LinkPager::widget([
        'pagination' => $pages,
    ]); ?>
	
	<div id="container">
		<?php
			}
			foreach ($models as $model) {
				if (Yii::$app->user->isGuest) {
					$query = Image::find()->where(['status' => 'guest'])->andWhere(['category' => $model->title]);
				} else if (Yii::$app->user->identity->username == "demo") {
					$query = Image::find()->where(['status' => 'guest'])->orWhere(['status' => 'user'])->andWhere(['category' => $model->title]);
				} else if (Yii::$app->user->identity->username == "admin") {
					$query = Image::find()->where(['status' => 'guest'])->orWhere(['status' => 'user'])->orWhere(['status' => 'admin'])->andWhere(['category' => $model->title]);
				}
				$countQuery = clone $query;
				$amount = $countQuery->count();

				$img = $query->orderBy('id DESC')->one();

				echo "<div class='category-title'>";
					if ($img == NULL) {
						echo "<a href='/page/category/$model->slug/1'><img src='/images/photogallery/No image.png' class='category-image'/></a>";
						echo "<span class='span-elem'><a href='/page/category/$model->slug'><span class='category'>$model->title</span> <span class='category-count'>$model->count</span></a></span>";
					} else {
						echo "<a href='/page/category/$model->slug/1'><img src='/images/photogallery/$img->image' class='category-image'/></a>";
						echo "<span class='span-elem'><a href='/page/category/$model->slug'><span class='category'>$model->title</span> <span class='category-count'>$amount</span></a></span>";
					}
				echo "</div>";
			}
		?>
	</div>

	<?php Pjax::begin() ?>
	<?=  
		GridView::widget([
		    'dataProvider' => $dataProvider,
		    'pager' => [
		        'class' => \kop\y2sp\ScrollPager::className(),
		        'container' => '#container',
		        'item' => '.category-title',
		        'paginationSelector' => '.grid-view .pagination',
		        'triggerText' => Yii::t('app', 'Show more'),
	        	'triggerTemplate' => '<span class="reveal-btn"><a style="cursor: pointer;" id="showMore">{text}</a></span>',
	          	'triggerOffset'=>$dataProvider->totalCount/$dataProvider->pagination->pageSize,
	          	'noneLeftText' => '',
          		'noneLeftTemplate' => '',
		    ],
		]);
	?>
	<?php Pjax::end() ?>
	<script type="text/javascript">
		var summary = document.getElementsByClassName('summary');

		if (summary.length == 0) {
			document.getElementsByClassName('table')[0].style.display = "none";
		} else if (summary.length > 0) {
			document.getElementsByClassName('summary')[0].style.display = "none";
			document.getElementsByClassName('table')[0].style.display = "none";
		}
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</body>
</html>

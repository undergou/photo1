<?php
	use yii\helpers\Html;
	use yii\grid\GridView;
	use app\modules\photogallery\models\Image;
	use yii\widgets\LinkPager;
	use yii\widgets\Pjax;
	use kop\y2sp\ScrollPager;
?>
<head>
	<style>
			#gallery{
				border: 5px solid black;
				display: flex;
				justify-content: space-around;
				flex-wrap: wrap;
				padding-top: 20px;
				border-radius: 30px;
			}

			.small-image{
				cursor: pointer;
				width: 200px;
				margin-bottom: 20px;
				text-align: center;
				border: 1px solid black;
			}

			.grid-img{
				width: 198px;
				height: 198px;
				display: block;
				border-left: none;
				border-top: none;
			}

			.no_images{
				width: 200px;
				padding-top: 80px;
				text-align: center;
				font-size: 20px;
			}
	</style>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.0/baguetteBox.min.css" integrity="sha512-pkvU4cxjSBpmI2BIthq5ADwB7UIQO9SKhKSAcuAwQSDpQdFUoVW5h05u0gNDmN/0nZJpL1KRvdhmgAh0gL96hQ==" crossorigin="anonymous" />
</head>

<p>
	<h1>Category images: <?= $category->title ?></h1>
</p>

<?php
	if (!count($models)) {
		echo "<h2>No images :(</h2>";
	} else {
?>

<?= LinkPager::widget([
    'pagination' => $pages,
]); ?>

<div id="gallery">
	<?php
		}
		foreach ($models as $model) {
			echo "<div class='small-image'>";
				echo "<a class='image-href' href='/images/photogallery/$model->image' data-caption='$model->title' title='$model->title'>";
					echo "<img src='/images/photogallery/$model->image' class='grid-img'/>";
				echo "</a>";
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
			    'container' => '#gallery',
			    'item' => '.small-image',
			    'paginationSelector' => '.grid-view .pagination',
			    'triggerText' => Yii::t('app', 'Show more'),
	          	'triggerTemplate' => '<span class="reveal-btn"><a style="cursor: pointer;" id="showMore">{text}</a></span>',
	          	'triggerOffset'=>$dataProvider->totalCount/$dataProvider->pagination->pageSize,
	          	'noneLeftText' => '<div class="no_images">No more images</div>',
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.11.0/baguetteBox.min.js" integrity="sha512-gBBvs+bYCFzQmRAaVhs83VeuEsAepEXy0b9dgL0lPp2JEGKwJGD22XVWFg9mRrkSiKCMyfaRNMlInr2RpNTD4w==" crossorigin="anonymous"></script>


<script type="text/javascript">
	window.addEventListener('load', function() {
		baguetteBox.run('#gallery', {
			animation: 'fadeIn', // fadeIn or slideIn
			overlayBackgroundColor: 'rgba(0,0,0,1)'
		});
	});
</script>

<script type="text/javascript">
	
	$("#gallery").bind("DOMSubtreeModified", function(){
  		baguetteBox.run('#gallery', {
			animation: 'fadeIn', // fadeIn or slideIn
			overlayBackgroundColor: 'rgba(0,0,0,1)'
		});
	});

</script>


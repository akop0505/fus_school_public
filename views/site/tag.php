<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var app\models\Channel $model */
/* @var app\models\Channel $dataSidebar */
/* @var app\models\Post $posts */
/* @var array $articleClass */
/* @var int $postCount */
/* @var boolean $tagSubscribed */
/* @var yii\data\Pagination $pages */

$this->title .= ' - '. $model .' tag';
$urlTagSubscribe = Url::to(['ajax-actions/tag']);
$counter = 0;
?>
<!-- start:header -->
<header id="header">
	<!-- start:cover -->
	<div class="cover">
		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->
	</div>
	<!-- end:cover -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">

	<!-- start:post -->
	<article class="post">

		<!-- start:cnt -->
		<div class="cnt clr">

			<!-- start:header -->
			<header class="header">

				<h1><?= Html::encode($model->name); ?></h1>

				<!-- start:details -->
				<div class="details clr">
					<ul class="left clr">
						<li>
							<span><?= $postCount; ?></span> posts
						</li>
					</ul>
					<div class="right">
						<?php if(!Yii::$app->user->isGuest): ?>
							<button class="button red size-30 _tag<?= $tagSubscribed == 0 ? '' : ' active' ?>" data-tag="<?= $tagSubscribed; ?>" data-title="<?= $tagSubscribed == 0 ? 'Subscribe' : 'Unsubscribe'; ?>"><i class="fa fa-tag"></i></button>
						<?php endif; ?>
					</div>
				</div>
				<!-- end:details -->

			</header>
			<!-- end:header -->

			<!-- start:column -->
			<div class="column">

				<!-- start:channels -->
				<div class="channels">

					<!-- start:list -->
					<div class="list clr">

						<?php foreach($posts as $article): ?>

							<!-- strt:article -->
							<article class="<?= $articleClass[$counter]; ?>">
								<a href="<?= $article->getUrl(); ?>">
									<span class="thumbnail" style="background-image: url('<?= $article->getPicBaseUrl('hasThumbPhoto'). $article->getPicName('hasThumbPhoto'); ?>');">
										 <?php if($article->video != ''):?>
											 <i class="icon play"></i>
										 <?php else: ?>
											 <i class="icon pen"></i>
										 <?php endif;  ?>
									</span>
									<div class="bottom">
										<h3><?= Html::encode($article->title); ?></h3>
										<p>
											<?= Html::encode($article->createdBy->getUserFullName()) . ', '; ?>
											<?= Html::encode($article->createdBy->institution->name); ?>
										</p>
										<?php if($articleClass[$counter] == 'article size-split'): ?>
										<p>
											<?= $article->getDescription(250); ?>
										</p>
										<p>
											<strong>Watch Now</strong>
										</p>
										<?php  endIf;?>
									</div>
								</a>
							</article>
							<!-- end:article -->
						<?php $counter++; endforeach; ?>

					</div>
					<!-- end:list -->

				</div>
				<!-- end:channels -->

				<!-- start:pagination -->
					<?php
					echo LinkPager::widget([
						'pagination' => $pages,
						'options' => [
							'class' => 'pagination clr',
						]
					]);
					?>
				<!-- end:pagination -->

			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<?= $this->render('sidebar', ['model' => $dataSidebar]); ?>
			<!-- end:sidebar -->

		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->

<?php
if(!Yii::$app->user->isGuest)
{
	$userId = Yii::$app->user->identity->getId();
	$this->registerJs(<<<JSCLIP
		$('._tag').on('click',function()
		{
	    	var tag = $(this).data('tag');
	    	$.post("{$urlTagSubscribe}",
	    		{tagId:{$model->id}, userId:{$userId}, tag:tag},
	    		function(response) {
	    			if(response == '0') return;
					if(tag == '0')
					{
						$('._tag').data('tag', '1').attr('data-title', 'Unsubscribe From Tag').addClass('active');;
					}
					else
					{
						$('._tag').data('tag', '0').attr('data-title', 'Subscribe To Tag').removeClass('active');;
					}
	    		}
	    	);
	  	});
JSCLIP
		, $this::POS_READY, 'article-init');
}

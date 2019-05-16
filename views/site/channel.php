<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var yii\data\Pagination $pages */
/* @var app\models\Channel $model */
/* @var app\models\Channel $dataSidebar */
/* @var app\models\Post $article */
/* @var array $posts */
/* @var array $articleClass */
/* @var array $months */
/* @var string $searchTerm */
/* @var string $selectedPeriod */
/* @var int $postCount */
/* @var int $institutionId */

$this->title .= ' - '. $model .' channel';

$counter = 0;
$title = $model->name;
if($searchTerm) $title .= ' - Search results for ‘'. Html::encode($searchTerm) . '‘';
?>
<!-- start:header -->
<header id="header">

	<!-- start:cover -->
	<div class="cover"<?php if($model->hasPhoto): ?> style="background-image: url('<?= $model->getPicBaseUrl('hasPhoto'). $model->getPicName('hasPhoto', true); ?>');"<?php endif; ?>>
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

				<h1><?= Html::encode($title); ?></h1>

				<!-- start:details -->
				<div class="details clr">
					<ul class="left clr">
						<?php if($model->description): ?>
						<li>
							<span><?= Html::encode($model->description); ?></span>
						</li>
						<li>|</li>
						<?php endif; ?>
						<li>
							<span><?= $postCount; ?></span> posts
						</li>
					</ul>
					<?php if($model->institutionId): ?>
						<ul class="right clr filter-dropdown">
							<li>
								<?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::toRoute(['site/channel', 'id' => $model->id])]);  ?>
								<div class="form-group">
									<?php echo
										Html::dropDownList('period', $selectedPeriod,
											ArrayHelper::map($months, 'id', 'name'),
											['onchange'=>'this.form.submit()']);
									?>
								</div>
								<?php ActiveForm::end(); ?>
							</li>
						</ul>
					<?php endif; ?>
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

						<?php foreach($posts as $article):
							$extraClass = '';
							if($model->institutionId && $article->createdBy->institutionId != $model->institutionId) $extraClass = ' repost';
						?>

							<!-- strt:article -->
							<article class="<?= $articleClass[$counter] . $extraClass; ?>">
								<a href="<?= $article->getUrl(); ?>">
									<span class="thumbnail" style="background-image: url('<?= $article->getPicBaseUrl('hasThumbPhoto'). $article->getPicName('hasThumbPhoto', true); ?>');">
										 <?php if($article->video != ''):?>
											 <i class="icon play"></i>
										 <?php else: ?>
											 <i class="icon pen"></i>
										 <?php endif;  ?>
										<?php if($article->isNational || ($institutionId && $article->createdBy->institutionId != $institutionId)):?>
											<span class="ribbon clr">
												<?php if($article->isNational):?>
													<i class="national" data-title="Made it to National"></i>
												<?php endif;  ?>
												<?php if($institutionId != 0 && $article->createdBy->institutionId != $institutionId):?>
													<i class="repost" data-title="Repost"></i>
												<?php endif;  ?>
											</span>
										<?php endif;  ?>
									</span>
									<div class="bottom">
										<h3><?= Html::encode($article->title); ?></h3>
										<p>
											<?= Html::encode($article->createdBy->getUserFullName()) . ', '; ?>
											<?= Html::encode($article->createdBy->institution->name); ?>
										</p>
										<?php if($articleClass[$counter] == 'article size-split'):?>
											<p>
											<?php
											$line = trim(substr(strip_tags($article->postText), 0, 250));
											if(preg_match('/^.{1,250}\b/s', $line, $match))
											{
												$line = $match[0];
											}
											echo $line , '...';
											?>
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
			<?= $this->render('sidebar', ['channels' => $dataSidebar]); ?>
			<!-- end:sidebar -->

		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->



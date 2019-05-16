<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var app\models\Institution $model */
/* @var app\models\Post $postLatest */
/* @var app\models\Post $postMustSee */
/* @var app\models\User $members */
/* @var app\models\User $archivedMembers */
/* @var app\models\Channel $schoolChannel */
/* @var array $articleClassLatest */
/* @var array $articleClassMustSee */
/* @var int $isLiked */
/* @var string $latestPictureUrl */
/* @var string $latestUrl */
/* @var int $channelSubscribed */
/* @var string $page */
/* @var array $dataTag */

$this->title .= ' - '. $model;

$urlMustSee = Url::toRoute(['site/channel', 'item' => $schoolChannel, 'sort' => 'views']);
$urlLatest = Url::toRoute(['site/channel', 'item' => $schoolChannel]);
$urlSchool = $model->getUrl();
$urlSchoolAbout = Url::toRoute(['site/school', 'item' => $model, 'about' => 1]);
$urlContact = Url::toRoute(['site/school-contact', 'institutionId' => $model->id]);
?>
<style>
	#about a {color: <?php echo $model->aboutUsLinkColor ?>;}
</style>
<header id="header">

	<!-- start:cover -->
	<div class="cover" style="background-image: url('<?= $model->getPicBaseUrl('header') . $model->getPicName('header', true); ?>');">

		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->
	</div>
	<!-- end:cover -->

	<!-- start:school -->
	<div class="school">

		<!-- start:cnt -->
		<div class="cnt clr">

			<!-- start:avatar -->
			<a href="<?= $urlSchoolAbout ?>" class="avatar" style="background-image: url('<?= $model->getPicBaseUrl('logo') . $model->getPicName('logo', true); ?>');"></a>
			<!-- end:avatar -->

			<!-- start:title -->
			<h2>
				<a href="<?= $urlSchool ?>"><?= Html::encode($model->name) .', '. Html::encode($model->city->stateId); ?></a>
			</h2>
			<!-- end:title -->

			<!-- start:navigation -->
			<ul class="clr">
				<li>
					<a href="<?= $urlLatest ?>">
						<strong><?= $schoolChannel->numPosts; ?></strong>
						<i class="fa fa-file-text-o margin-5-left"></i>
						<span>Posts</span>
					</a>
				</li>
				<li>
					<a href="#" class="_like<?php if($isLiked) echo ' active';?>" data-liked="<?= $isLiked; ?>">
						<strong><?= $model->numLikes; ?></strong>
						<i class="fa fa-heart margin-5-left"></i>
						<span>Likes</span>
					</a>
				</li>
				<li>
					<a href="#" class="_subscribe<?php if($channelSubscribed) echo ' active';?>" data-subscribed="<?= $channelSubscribed; ?>">
						<strong><?= $schoolChannel->numSubscribers; ?></strong>
						<i class="fa fa-feed margin-5-left"></i>
						<span>Subscribers</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlSchoolAbout; ?>">
						<i class="fa fa-info-circle margin-5-left"></i>
						<span>About us</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlContact; ?>">
						<i class="fa fa-envelope-o margin-5-left"></i>
						<span>Contact us</span>
					</a>
				</li>
				<li>
					<?php $form = ActiveForm::begin(['id' => 'search','method' => 'get', 'action' => Url::toRoute(['site/channel', 'id' => $schoolChannel->id])]); ?>
						<div class="form-group">
							<input id="term" name="term" type="text" class="form-control" placeholder="Search school channel">
						</div>
					<?php ActiveForm::end(); ?>
				</li>
			</ul>
			<!-- end:navigation -->

		</div>
		<!-- end:cnt -->

	</div>
	<!-- end:school -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">

	<?php if($page == 'about'): ?>

		<!-- start:about -->
		<section class="about">

			<!-- start:cnt -->
			<div class="cnt">

				<!-- start:list -->
				<div class="list clr">

					<!-- start:box -->
					<div class="box">
						<div class="background typography" style="background: <?= $model->themeColor ?>;">
							<div class="overflow" id=about>
								<h3><?= 'About ' . Html::encode($model->name); ?></h3>
								<?= $model->about; ?>
							</div>
						</div>
					</div>
					<!-- end:box -->

					<?php $counterMembers = 0; for($counterAll = 1; $counterAll <= 80; $counterAll++): ?>

						<?php
						if(!isset($members[$counterMembers])) break; 
						if($counterAll == 2 || $counterAll == 6): ?>
							<!-- start:box -->
							<div class="box">
								<a href="" style="background: #e12c3c;">
									<div class="tbl">
										<div class="tcell vertical-middle">
										<?php if($counterAll != 6): ?>
											Meet Our <br> Members
										<?php else: ?>
											Join our <br>team<br> today
										<?php endif; ?>
										</div>
									</div>
								</a>
							</div>
							<!-- end:box -->
						<?php else: ?>
							<!-- start:box -->
							<div class="box">
								<?php if($members[$counterMembers]->hasPhoto || !empty($members[$counterMembers]->avatar_name)): ?>
									<a href="<?= $members[$counterMembers]->getUrl(); ?>" style="background-image: url('<?= $members[$counterMembers]->getAvatar($members[$counterMembers]->hasPhoto, $members[$counterMembers]->avatar_name); ?>');">
										<div class="bottom">
											<?= Html::encode($members[$counterMembers]->getUserFullName()); ?>
										</div>
									</a>
								<?php else: ?>
									<a href="<?= $members[$counterMembers]->getUrl(); ?>" data-initials="<?= $members[$counterMembers]->getUserInitials(); ?>">
										<div class="bottom">
											<?= Html::encode($members[$counterMembers]->getUserFullName()); ?>
										</div>
									</a>
								<?php endif; ?>
							</div>
							<!-- end:box -->
							<?php $counterMembers++; ?>
						<?php endif; ?>

					<?php endfor; ?>
				</div>
				<!-- end:list -->

			</div>
			<!-- end:cnt -->
			<!-- start:cnt -->
			<div class="cnt">

				<!-- start:list -->
				<div class="list clr">
					<div class="featured">
                                                <header class="transparent clr" style="background: <?= $model->themeColor ?>;">
                                                                <h2>Alumni</h2>
                                                </header>

					<?php $counterMembers = 0; for($counterAll = 1; $counterAll <= 80; $counterAll++): ?>
						<?php
						if(!isset($archivedMembers[$counterMembers])) break; ?>
							<!-- start:box -->
												<!-- start:box -->
					<!-- end:box -->
							<div class="box">
								<?php if($archivedMembers[$counterMembers]->hasPhoto || !empty($archivedMembers[$counterMembers]->avatar_name)): ?>
									<a href="<?= $archivedMembers[$counterMembers]->getUrl(); ?>" style="background-image: url('<?= $archivedMembers[$counterMembers]->getAvatar($archivedMembers[$counterMembers]->hasPhoto, $archivedMembers[$counterMembers]->avatar_name); ?>');">
										<div class="bottom">
											<?= Html::encode($archivedMembers[$counterMembers]->getUserFullName()); ?>
										</div>
									</a>
								<?php else: ?>
									<a href="<?= $archivedMembers[$counterMembers]->getUrl(); ?>" data-initials="<?= $archivedMembers[$counterMembers]->getUserInitials(); ?>">
										<div class="bottom">
											<?= Html::encode($archivedMembers[$counterMembers]->getUserFullName()); ?>
										</div>
									</a>
								<?php endif; ?>
							</div>
							<!-- end:box -->
							<?php $counterMembers++; ?>
					<?php endfor; ?>
				</div>
				<!-- end:list -->
			</div>
			<!-- end:cnt -->			
		</section>
		<!-- end:about -->
	<?php endif; ?>

	<?php if($postLatest): ?>
	<!-- start:featured -->
	<?= $this->render('articleFeatured', [
			'data' => $postLatest,
			'articleClass' => $articleClassLatest,
			'class' => 'featured',
			'title' => 'See All Latest Posts',
			'titleMain' => 'Latest',
			'url' => $urlLatest,
			'addSection' => false,
			'institutionId' => $model->id,
			'latestPictureUrl' => $latestPictureUrl,
			'latestUrl' => $latestUrl,
			'headerExtra' => 'style="background: '. $model->themeColor .';"'
		]);
	?>
	<!-- end:featured -->
	<?php endif; ?>

	<?php if($postMustSee && $page != 'about'): ?>
	<!-- start:featured -->
	<?= $this->render('articleFeatured', [
			'data' => $postMustSee,
			'articleClass' => $articleClassMustSee,
			'class' => 'featured',
			'title' => 'See All Must See Posts',
			'titleMain' => 'Featured',
			'url' => false,
			'addSection' => true,
			'institutionId' => $model->id,
			'headerExtra' => 'style="background: '. $model->themeColor .';"'
		]);
	?>
	<!-- end:featured -->
	<?php endif; ?>

	<?php if($dataTag && $page != 'about'): ?>
		<div>
		<?php foreach($dataTag as $tag): ?>
			<!-- start:videos -->
			<?= $this->render('articleList', [
					'model' => $tag['model'],
					'title' => $tag['title'],
					'class' => 'videos school-profile',
					'institutionId' => $model->id
				]);
			?>
			<!-- end:videos -->
		<?php endforeach; ?>
		</div>
	<?php endif; ?>

</main>
<!-- end:main -->

<?php
	$urlPostLike = Url::to(['ajax-actions/school-like']);
	$urlChannelSubscribe = Url::to(['ajax-actions/subscribe']);
	$userId = Yii::$app->user->isGuest ? 0 : Yii::$app->user->identity->getId();
	$this->registerJs(<<<JSCLIP
		$('._like').on('click',function()
		{
			if({$userId})
			{
				var liked = $(this).data('liked');
				$.post("{$urlPostLike}",
					{institutionId:{$model->id}, userId:{$userId}, liked:liked},
					function(response) {
						if(response == '0') return;
						if(liked == '0')
						{
							var numTmp = $('._like').data('liked', '1').addClass('active').find('strong');
							numTmp.text(parseInt(numTmp.text()) + 1);
						}
						else
						{
							var numTmp = $('._like').data('liked', '0').removeClass('active').find('strong');
							numTmp.text(parseInt(numTmp.text()) - 1);
						}
					}
				);
			}
			return false;
	  	});
	  	$('._subscribe').on('click',function()
		{
			if({$userId})
			{
				var subscribed = $(this).data('subscribed');
				$.post("{$urlChannelSubscribe}",
					{channelId:{$schoolChannel->id}, userId:{$userId}, subscribed:subscribed},
					function(response) {
						if(response == '0') return;
						if(subscribed == '0')
						{
							var numTmp = $('._subscribe').data('subscribed', '1').addClass('active').find('strong');
							numTmp.text(parseInt(numTmp.text()) + 1);
						}
						else
						{
							var numTmp = $('._subscribe').data('subscribed', '0').removeClass('active').find('strong');
							numTmp.text(parseInt(numTmp.text()) - 1);
						}
					}
				);
			}
			return false;
	  	});
JSCLIP
	, $this::POS_READY, 'article-init');


<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var app\models\Post $model */
/* @var app\models\Post $postRecent */
/* @var app\models\Post $postMostViewed */
/* @var app\models\Channel $dataSidebar */
/* @var app\models\User $user */
/* @var app\models\Tag[] $tags */
/* @var app\models\Channel[] $channels */
/* @var array $postCounterSidebar */
/* @var int $userChannelId */
/* @var int $userInstitutionChannelId */
/* @var int $channelAuthorSubscribed */
/* @var int $channelSubscribed */
/* @var int $postLiked */
/* @var int $postFavorite */
/* @var int $postLater */
/* @var int $daysCreatedAgo */
/* @var boolean $rePosted */

$urlChannelSubscribe = Url::to(['ajax-actions/subscribe']);
$urlPostLike = Url::to(['ajax-actions/like']);
$urlPostFavorite = Url::to(['ajax-actions/favorite']);
$urlPostLater = Url::to(['ajax-actions/later']);
$urlInstitution = $model->createdBy->institution->getUrl();
$urlRePost = Url::to(['ajax-actions/repost']);
$urlAuthorSubscribe = Url::to(['ajax-actions/author']);

$this->title .= ' - '. $model->title;
if($model->video) $this->registerJsFile(Yii::$app->params['jwp.player.domain'] .'/players/'. $model->video .'-wtzqEpA3.js');
?>
<!-- start:header -->
<header id="header">

	<!-- start:cover -->
	<div class="cover" style="background-image: url('<?= $model->getPicBaseUrl('hasHeaderPhoto') . $model->getPicName('hasHeaderPhoto', true); ?>');">

		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->

		<?php if($model->video != ''): ?>
			<!-- start:video -->
			<div class="video">

				<!-- start:cnt -->
				<div class="cnt clr">

					<!-- start:play -->
					<a href="#" class="play toggle">
					</a>
					<!-- end:play -->

					<!-- start:player -->
					<div class="player">
						<div class="flex" id="botr_<?= $model->video ?>_wtzqEpA3_div"></div>
						<a href="#" class="close toggle" rel="botr_<?= $model->video ?>_wtzqEpA3_div">
							<i class="fa fa-times-circle"></i>
						</a>
					</div>
					<!-- end:player -->

				</div>
				<!-- end:cnt -->

			</div>
			<!-- end:video -->
		<?php endif;?>

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

				<h1><?= Html::encode($model->title); ?></h1>

				<!-- start:details -->
				<div class="details clr">
					<ul class="left clr">
						<li>
							from <a href="<?= $user->getUrl(); ?>"><?= Html::encode($model->createdBy->getUserFullName()); ?></a>
						</li>
						<li>|</li>
						<li>
							<a href="<?= $urlInstitution; ?>"><?= Html::encode($model->createdBy->institution->name); ?></a>
						</li>
						<li>|</li>
						<li>
							<span><?= $model->views; ?></span> views
						</li>
						<li>|</li>
						<!--<li>
							<?php if($daysCreatedAgo) { ?>
							<span><?= $daysCreatedAgo; ?></span> <?= $daysCreatedAgo > 1 ? 'days ago' : 'day ago'; ?>
							<?php } else echo 'today'; ?>
						</li>-->
						<li><?= Yii::$app->formatter->asDate($model->datePublished); ?></li>
					</ul>
					<div class="right">
						<?php if(!Yii::$app->user->isGuest): ?>
						<?php if($userInstitutionChannelId > 0): ?>
						<button class="button red size-30 _subscribe<?= $channelSubscribed == 0 ? '' : ' active' ?>" data-subscribed="<?= $channelSubscribed; ?>" data-title="<?= $channelSubscribed == 0 ? 'Subscribe To School' : 'Unsubscribe From School'; ?>"><i class="fa fa-university"></i></button>
						<?php endif; ?>
						<?php if($userChannelId > 0 &&  Yii::$app->user->identity->getId() != $model->createdById): ?>
						<button class="button red size-30 _author<?= $channelAuthorSubscribed == 0 ? '' : ' active' ?>" data-author="<?= $channelAuthorSubscribed; ?>" data-title="<?= $channelAuthorSubscribed == 0 ? 'Subscribe To Author' : 'Unsubscribe From Author'; ?>"><i class="fa fa-user"></i></button>
						<?php endif; ?>
						<button class="button red size-30 _like<?= $postLiked == 0 ? '' : ' active' ?>" data-liked="<?= $postLiked; ?>" data-title="<?= $postLiked == 0 ? 'Like' : 'Unlike'; ?>"><i class="fa fa-thumbs-o-up"></i></button>

						<button class="button red size-30 _favorite<?= $postFavorite == 0 ? '' : ' active' ?>" data-favorite="<?= $postFavorite; ?>" data-title="<?= $postFavorite == 0 ? 'Favorite' : 'Remove Favorite'; ?>"><i class="fa fa-star"></i></button>

						<button class="button red size-30 _later<?= $postLater == 0 ? '' : ' active' ?>" data-later="<?= $postLater; ?>" data-title="<?= $postLater == 0 ? 'View Later' : 'Remove View Later'; ?>"><i class="fa fa-clock-o"></i></button>
						<?php if(!$rePosted): ?>
							<button class="button red size-30 _postToChannel"> Repost <i class="fa fa-share margin-10-left"></i></button>
						<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<!-- end:details -->

			</header>
			<!-- end:header -->

			<!-- start:column -->
			<div class="column">

				<!-- start:content -->
				<div class="content typography">
					<p><?= $model->addGalleryToContent(); ?></p>
				</div>
				<!-- end:content -->

				<!-- start:author -->
				<div class="author">
					<?php if($user->hasPhoto || !empty($user->avatar_name)): ?>
						<a href="<?= $user->getUrl(); ?>" class="thumbnail" style="background-image: url('<?= $user->getAvatar($user->hasPhoto, $user->avatar_name); ?>');"></a>
					<?php else: ?>
						<a href="<?= $user->getUrl(); ?>" data-initials="<?= $user->getUserInitials(); ?>" class="thumbnail"></a>
					<?php endif; ?>

					<div class="top">
						<a href="<?= $user->getUrl(); ?>" class="name"><?= Html::encode($model->createdBy->getUserFullName()); ?></a>
					</div>
					<div class="info">
						<p>
							<?= $model->createdBy->about; ?><a href="<?= $user->getUrl(); ?>">Full profile</a>
						</p>
					</div>
				</div>
				<!-- end:author -->

				<?php if($tags): ?>
					<!-- start:tags -->
					<div class="tags">
						<ul class="clr">
							<li>
								Similar posts
							</li>
							<?php foreach($tags as $tag): ?>
								<li>
									<a href="<?= $tag->getUrl() ?>"><?= Html::encode($tag->name); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<!-- end:tags -->
				<?php endif; ?>

				<?php if($channels): ?>
					<!-- start:tags -->
					<div class="tags">
						<ul class="clr">
							<li>
								Channel
							</li>
							<?php
								foreach($channels as $channel):
									if($channel->isSystem) continue;
							?>
								<li>
									<a href="<?= $channel->getUrl() ?>"><?= Html::encode($channel->name); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<!-- end:tags -->
				<?php endif; ?>
			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<?= $this->render('sidebar', ['channels' => $dataSidebar, 'post' => $model]); ?>
			<!-- end:sidebar -->

		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

	<!-- start:videos -->
	<?= $this->render('articleList', ['model' => $postMostViewed, 'title' => 'More Videos', 'institutionId' => $model->createdBy->institutionId]); ?>
	<!-- end:videos -->

	<!-- start:videos -->
	<?= $this->render('articleList', ['model' => $postRecent, 'title' => 'Recently uploaded', 'institutionId' => $model->createdBy->institutionId]); ?>
	<!-- end:videos -->

</main>
<!-- end:main -->

<?php
if(!Yii::$app->user->isGuest)
{
	$userId = Yii::$app->user->identity->getId();
	$this->registerJs(<<<JSCLIP
		$('._subscribe').on('click',function()
		{
	    	var subscribed = $(this).data('subscribed');
	    	$.post("{$urlChannelSubscribe}",
	    		{channelId:{$userInstitutionChannelId}, userId:{$userId}, subscribed:subscribed},
	    		function(response) {
	    			if(response == '0') return;
					if(subscribed == '0')
					{
						$('._subscribe').data('subscribed', '1').attr('data-title', 'Unsubscribe From School').addClass('active');
					}
					else
					{
						$('._subscribe').data('subscribed', '0').attr('data-title', 'Subscribe To School').removeClass('active');
					}
	    		}
	    	);
	  	});

	  	$('._author').on('click',function()
		{
	    	var subscribed = $(this).data('author');
	    	$.post("{$urlChannelSubscribe}",
	    		{channelId:{$userChannelId}, userId:{$userId}, subscribed:subscribed},
	    		function(response) {
	    			if(response == '0') return;
					if(subscribed == '0')
					{
						$('._author').data('author', '1').attr('data-title', 'Unsubscribe From Author').addClass('active');
					}
					else
					{
						$('._author').data('author', '0').attr('data-title', 'Subscribe To Author').removeClass('active');
					}
	    		}
	    	);
	  	});

	  	$('._like').on('click',function()
		{
	    	var liked = $(this).data('liked');
	    	$.post("{$urlPostLike}",
	    		{postId:{$model->id}, userId:{$userId}, liked:liked},
	    		function(response) {
	    			if(response == '0') return;
					if(liked == '0')
					{
						$('._like').data('liked', '1').attr('data-title', 'Unlike').addClass('active');
					}
					else
					{
						$('._like').data('liked', '0').attr('data-title', 'Like').removeClass('active');
					}
	    		}
	    	);
	  	});
	
	  	$('._favorite').on('click',function()
		{
	    	var favorite = $(this).data('favorite');
	    	$.post("{$urlPostFavorite}",
	    		{postId:{$model->id}, userId:{$userId}, favorite:favorite},
	    		function(response) {
	    			if(response == '0') return;
					if(favorite == '0')
					{
						$('._favorite').data('favorite', '1').attr('data-title', 'Remove Favorite').addClass('active');
					}
					else
					{
						$('._favorite').data('favorite', '0').attr('data-title', 'Favorite').removeClass('active');
					}
	    		}
	    	);
	  	});
	
	  	$('._later').on('click',function()
		{
	    	var later = $(this).data('later');
	    	$.post("{$urlPostLater}",
	    		{postId:{$model->id}, userId:{$userId}, later:later},
	    		function(response){
	    			if(response == '0') return;
					if(later == '0')
					{
						$('._later').data('later', '1').attr('data-title', 'Remove View Later').addClass('active');
					}
					else
					{
						$('._later').data('later', '0').attr('data-title', 'View Later').removeClass('active');
					}
	    		}
	    	);
	  	});

	  	$('._postToChannel').on('click',function()
		{
	    	$.post("{$urlRePost}",
	    		{postId:{$model->id}, userId:{$userId}},
	    		function(response){
	    			if(response == '0') return;
	    			else $('._postToChannel').remove();
	    		}

	    	);
	  	});
	
JSCLIP
		, $this::POS_READY, 'article-init');
}

$this->registerJs(<<<JSCLIP
	$(".post-slider").flexslider({
		  controlNav: false
	});
JSCLIP
	, $this::POS_READY, 'flexslider-init');
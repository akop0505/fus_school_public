<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var app\models\UserActivity $activity */
/* @var app\models\Post $post */
/* @var app\models\User[] $subscriptionUsers */
/* @var app\models\Institution[] $subscriptionSchools */
/* @var app\models\Channel $channel */
/* @var app\models\Tag $tags */
/* @var int $userChannelId */
/* @var boolean $subscribed */
/* @var string $page */
/* @var string $title */
/* @var yii\data\Pagination $pages */

$urlChannelSubscribe = Url::to(['ajax-actions/subscribe']);
?>
				<header class="clr">

					<h2><?= Html::encode($title); ?></h2>
					<?php if(!Yii::$app->user->isGuest && $page == 'latest'): ?>
					<?php if($userChannelId > 0): ?>
						<button class="button red size-30 _subscribe<?= $subscribed == 0 ? '' : ' active' ?>" data-subscribed="<?= $subscribed; ?>" data-title="<?= $subscribed == 0 ? 'Subscribe To Author' : 'Unsubscribe From Author'; ?>"><i class="fa fa-user"></i></button>
					<?php endif; ?>
					<?php endif; ?>

				</header>

				<!-- start:videos -->
				<div class="videos">

						<?php if($page != 'activity'): ?>
					<!-- start:list -->
					<div class="list four clr">
						<?php foreach($post as $article): ?>
							<!-- start:video item -->
							<?php echo $this->render('_searchBox', ['data' => $article]); ?>
							<!-- end:video item -->
						<?php endforeach; ?>
					</div>
					<!-- end:list -->
						<?php else: ?>
								<ul class="activity-list">
								<?php foreach($activity as $one): ?>
									<?php echo $this->render('_profileContentBox', ['data' => $one]); ?>
								<?php endforeach; ?>
								</ul>
						<?php endif; ?>

					<!-- start:pagination -->
					<?php
						if($pages) echo LinkPager::widget([
							'pagination' => $pages,
							'options' => [
								'class' => 'pagination clr',
							]
						]);
					?>
					<!-- end:pagination -->
				</div>
				<!-- end:videos -->
<?php
if(!Yii::$app->user->isGuest)
{
	$userId = Yii::$app->user->identity->getId();
	$this->registerJs(<<<JSCLIP
		$('._subscribe').on('click',function()
		{
	    	var subscribed = $(this).data('subscribed');
	    	$.post("{$urlChannelSubscribe}",
	    		{channelId:{$userChannelId}, userId:{$userId}, subscribed:subscribed},
	    		function(response) {
	    			if(response == '0') return;
					if(subscribed == '0')
					{
						$('._subscribe').data('subscribed', '1').attr('data-title', 'Unsubscribe From Author').addClass('active');
					}
					else
					{
						$('._subscribe').data('subscribed', '0').attr('data-title', 'Subscribe To Author').removeClass('active');
					}
	    		}
	    	);
	  	});

JSCLIP
		, $this::POS_READY, 'article-init');
}

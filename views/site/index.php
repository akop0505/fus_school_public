<?php

use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Channel;

/* @var $this yii\web\View */
/* @var app\models\Post $topSlider */
/* @var app\models\Post $bottomSlider */
/* @var app\models\Post $homeLatest */
/* @var app\models\Post $homeMustSee */
/* @var array $homeFeatured */
/* @var app\models\DiscoverChannel $discoverChannels */

$urlMustSee = Url::toRoute(['site/channel', 'id' => Channel::CHANNEL_HOME_MUST_SEE]);
$urlLatest = Url::toRoute(['site/channel', 'id' => Channel::CHANNEL_HOME_LATEST]);


?>

<?= $this->registerCssFile("@web/css/layout_main.css"); ?>

<!-- start:header -->
<header id="header">

	<!-- start:slider -->
	<div class="slider">

		<?= $this->render('top'); ?>

		<!-- start:slides -->
		<ul class="slides clr">
			<?php foreach($topSlider as $big): ?>
			<li style="background-image: url('<?= $big->getPicBaseUrl('hasThumbPhoto') . $big->getPicName('hasThumbPhoto', true); ?>');">

				<!-- start:tbl -->
				<div class="tbl">

					<!-- start:tcell -->
					<div class="tcell vertical-middle">

						<!-- start:cnt -->
						<div class="cnt">
							<h2><?= Html::encode($big->title); ?></h2>
							<p>
								<?= Html::encode($big->createdBy->getUserFullName()); ?> |
								<?= Html::encode($big->createdBy->institution->name); ?>
							</p>
							<p>
								<?= Html::encode($big->getDescription(100)); ?>
							</p>
							<a href="<?= $big->getUrl(); ?>" class="button red size-40"> <?= ($big->video != '') ? 'Watch Now' : 'Read Now' ?></a>
						</div>
						<!-- end:cnt -->

					</div>
					<!-- end:tcell -->

				</div>
				<!-- end:tbl -->

			</li>
			<?php endforeach; ?>
		</ul>
		<!-- end:slides -->

		<!-- start:flex arrow -->
		<button type="button" class="flex-arrow previous clr">
        <span class="arrow">
            <i class="fa fa-long-arrow-left"></i>
        </span>
			<span class="image"></span>
		</button>
		<button type="button" class="flex-arrow next clr">
			<span class="image"></span>
        <span class="arrow">
            <i class="fa fa-long-arrow-right"></i>
        </span>
		</button>
		<!-- end:flex arrow -->

	</div>
	<!-- end:slider -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">

	<!-- start:featured -->
	<section class="featured top">

		<!-- start:cnt -->
		<div class="cnt">

			<!-- start:intro -->
			<div class="intro">
				<h2>About Fusfoo</h2>
				<p>
					Changing the way high school students discover the bigger picture.<br>
					Fusfoo - don't ask us what it means.
				</p>
			</div>
			<!-- end:intro -->

			<!-- start:list -->
			<div class="list clr">

				<?php foreach($bottomSlider as $one): ?>
				<!-- strt:article -->
				<article class="article size-1x1-3">
					<a href="<?= $one->getUrl(); ?>">
                            <span class="thumbnail" style="background-image: url('<?= $one->getPicBaseUrl('hasThumbPhoto') . $one->getPicName('hasThumbPhoto', true); ?>');">
                                <?php if($one->video != ''):  $labelName = 'Video'; ?>
									<i class="icon play"></i>
								<?php else:  $labelName = 'Post'; ?>
									<i class="icon pen"></i>
								<?php endif; ?>
								<?php if($one->isNational):?>
									<span class="ribbon clr">
										<i class="national" data-title="Made it to National"></i>
									</span>
								<?php endif; ?>
                            </span>
						<div class="bottom">
							<h3><?= Html::encode($one->title); ?></h3>
							<p>
								<?= Html::encode($one->createdBy->getUserFullName()) . ', '; ?>
								<?= Html::encode($one->createdBy->institution->name); ?>
							</p>
						</div>
					</a>
				</article>
				<!-- end:article -->
				<?php endforeach; ?>

			</div>
			<!-- end:list -->

		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:featured -->

	<?= $this->render('indexFeatured', [
			'data' => $homeLatest,
			'titleMain' => 'Latest',
			'url' => $urlLatest,
			'channelId' => Channel::CHANNEL_HOME_LATEST,
			'listClass' => 'list list-10 clr',
			'withHeader' => false
		]);
	?>

	<?= $this->render('indexFeatured', [
			'data' => $homeMustSee,
			'titleMain' => 'Must See',
			'url' => $urlMustSee,
			'channelId' => Channel::CHANNEL_HOME_MUST_SEE,
			'listClass' => 'list list-8 clr',
			'withHeader' => true
		]);
	?>

	<?php foreach($homeFeatured as $key => $featured): ?>

		<?php if($key == 1): ?>
			<!-- start:join fusfoo -->
			<div class="join-fusfoo">

				<!-- stat:cnt -->
				<div class="cnt">

					<!-- start:background -->
					<div class="background">
						<h2>Bring Fusfoo to your school today!</h2>
						<a href="<?= Url::to(['site/contact-fusfoo']) ?>" class="button white size-40">CONTACT US</a>
					</div>
					<!-- end:background -->

				</div>
				<!-- end:cnt -->

			</div>
			<!-- end:join fusfoo -->
		<?php elseif($featured['posts']): ?>
			<?= $this->render('indexFeatured', [
					'data' => $featured['posts'],
					'titleMain' => $featured['channelName'],
					'channelId' => $featured['channelId'],
					'listClass' => $featured['listClass'],
					'numPost' => $featured['numPost'],
					'withHeader' => true
				]);
			?>
		<?php endif; ?>

	<?php endforeach; ?>

	<!-- start:join fusfoo -->
	<div class="join-fusfoo">

		<!-- stat:cnt -->
		<div class="cnt">

			<!-- start:background -->
			<div class="background">
				<h2>STUDENTS - find out how to get Fusfoo at your school!</h2>
				<a href="<?= Url::to(['site/article', 'id' => 15]) ?>" class="button white size-40">Learn How</a>
			</div>
			<!-- end:background -->

		</div>
		<!-- end:cnt -->

	</div>
	<!-- end:join fusfoo --->

	<?php if($discoverChannels): ?>
	<!-- start:discover -->
	<section class="discover">

		<!-- start:cnt -->
		<div class="cnt">

			<!-- start:break -->
			<hr class="break">
			<!-- end:break -->

			<h3>Discover More Fusfoo Channels</h3>

			<ul class="clr">
				<?php foreach($discoverChannels as $one): ?>
					<?php $channel = Channel::findOne($one->channelId); ?>
					<li>
						<a href="<?= $channel->getUrl(); ?>" class="tbl" style="background-image: url('<?= $one->channel->getPicBaseUrl('hasPortraitPhoto') . $one->channel->getPicName('hasPortraitPhoto', true); ?>');">
							<div class="tcell vertical-middle">
								<span><?= Html::encode($one->channel->name); ?></span>
							</div>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>

		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:discover -->
	<?php endif; ?>
	<!-- start:partners -->
	<section class="partners">

		<!-- start:cnt -->
		<div class="cnt">

			<h3>Our Partners</h3>

			<ul class="clr">
				<li>
					<img src="/images/partners/frsnj.png" style="max-height: 60px;" alt="FRSNJ">
				</li>
				<li>
					<img src="/images/partners/aasa.png" style="max-height: 55px;" alt="AASA">
				</li>
				<li>
					<img src="/images/partners/nhsca.png" style="max-height: 55px;" alt="NHSCA">
				</li>
				<li>
					<img src="/images/partners/njsba.jpg" style="max-height: 75px;" alt="NJSBA">
				</li>

			</ul>

		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:partners -->

</main>
<!-- end:main -->


<?php
if(!Yii::$app->user->isGuest && Yii::$app->request->cookies->getValue('to_subscribe_to_mailchimp', 1)){
    $subscribed = (new \app\services\mailchimp\MailChimpService())->memberStatus(Yii::$app->user->identity->email);
    if(!$subscribed) {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'to_subscribe_to_mailchimp',
            'value' => 0,
            'expire' => time() * 2,
        ]));
        ?>

        <div class="modal" id="learnMoreModal" style="display: none">
            <!-- start:tbl -->
            <div class="tbl">
                <!-- start:tcell -->
                <div class="tcell vertical-middle">
                    <!-- start:content -->
                    <div class="content">
                        <div class="subscribe_mailchimp">
                            <span class="title">Amplify your experience. </span>
                            <span class="title">Stay connected with what high school students
                                            are creating across the country. </span>
                            <span class="title">Discover more.</span>
                            <span class="modal-body">
                                Sign up for the Fusfoo Newsletter <a href="/user/profile-update.html" class="click_here">  here. </a>
                            </span>

                            <button type="button" class="close" onclick="$('#learnMoreModal').fadeOut('slow');">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- end:content -->
                </div>
                <!-- end:tcell -->
            </div>
            <!-- end:tbl -->
        </div>
        <script>

        </script>
        <!-- end:modal -->
        <?php

        $script = <<< JS
            setTimeout((function(){
                $('#learnMoreModal').fadeIn("slow");
            }),500);
            setTimeout((function(){
                $('#learnMoreModal').fadeOut("slow");
            }),10000);
JS;
        $this->registerJs($script,\yii\web\View::POS_READY);

    }
};
?>




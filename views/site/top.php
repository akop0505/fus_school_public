<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use \app\models\User;

?>
<div class="top">

	<!-- start:cnt -->
	<div class="cnt clr">

		<!-- start:left -->
		<div class="left">

			<!-- start:menu -->
			<a href="#" class="menu" title="Menu"></a>
			<!-- end:menu -->

			<!-- start:logo -->
			<h1>
				<a href="/" class="ir">
					<img src="/images/fusfoo.svg" alt="Fusfoo">
					Fusfoo
				</a>
			</h1>
			<!-- end:logo -->

		</div>
		<!-- end:left -->

		<!-- start:right -->
		<div class="right clr">

			<!-- start:social -->
			<ul class="social clr">
				<li>
					<a href="https://www.facebook.com/fusfoo/" target="_blank">
						<i class="fa fa-facebook-square"></i>
					</a>
				</li>
				<li>
					<a href="https://twitter.com/fusfoo/" target="_blank">
						<i class="fa fa-twitter-square"></i>
					</a>
				</li>
				<li>
					<a href="https://www.instagram.com/fusfoo/" target="_blank">
						<i class="fa fa-instagram"></i>
					</a>
				</li>
				<li>
					<a href="mailto:info@fusfoo.com">
						<i class="fa fa-envelope"></i>
					</a>
				</li>
			</ul>
			<!-- end:social -->

			<!-- start:search -->
			<div class="search">
				<?php $form = ActiveForm::begin(['id' => 'search','method' => 'get', 'action' => Url::to(['site/search'])]); ?>
					<input id="term" name="term" type="text" class="white size-40" placeholder="Search videos, channels and more">
					<button type="submit" class="button">
						<i class="fa fa-search"></i>
					</button>
				<?php ActiveForm::end(); ?>
			</div>
			<!-- end:search -->

			<?php if(!Yii::$app->user->isGuest):
				/**
				 * @var User $user
				 */
				$user = Yii::$app->user->identity;
				$counts = $user->getUserCounts();
			?>
			<!-- start:user -->
			<div class="user">
				<?php if($user->hasPhoto || !empty($user->avatar_name)): ?>
					<a href="<?= $user->getUrl(); ?>" style="background-image: url('<?= $user->getAvatar($user->hasPhoto, $user->avatar_name); ?>');">
						<span><?= $counts['all']; ?></span>
					</a>
				<?php else: ?>
					<a href="<?= $user->getUrl(); ?>" data-initials="<?= $user->getUserInitials(); ?>">
						<span><?= $counts['all']; ?></span>
					</a>
				<?php endif; ?>

				<div class="dropdown">
					<div class="background">
						<ul class="welcome">
							<li><?= 'Hi ' . Html::encode($user->firstName) . ','; ?></li>
							<li>Welcome back!</li>
						</ul>
						<ul class="menu">
							<li>
								<a href="<?= Url::toRoute(['site/profile', 'item' => $user, 'postType' => 'latest']); ?>">
									Posts
									<span><?=$counts['posts']; ?></span>
								</a>
							</li>
							<li>
								<a href="<?= Url::toRoute(['site/profile', 'item' => $user, 'postType' => 'favorite']); ?>">
									Favorite posts
									<span><?= $counts['favorite']; ?></span>
								</a>
							</li>
							<li>
								<a href="<?= Url::toRoute(['site/profile', 'item' => $user, 'postType' => 'like']); ?>">
									Liked posts
									<span><?= $counts['like']; ?></span>
								</a>
							</li>
							<li>
								<a href="<?= Url::toRoute(['site/profile', 'item' => $user, 'postType' => 'subscriptions']); ?>">
									Subscriptions
									<span><?= $counts['subscriptions']; ?></span>
								</a>
							</li>
							<li>
								<a href="<?= Url::toRoute(['site/profile', 'item' => $user, 'postType' => 'watchLater']); ?>">
									View later
									<span><?= $counts['later']; ?></span>
								</a>
							</li>
							<li>
								<a href="<?= $user->getUrl(); ?>">Your profile</a>
							</li>
						</ul>
						<ul class="logout">
							<li>
								<a href="<?php echo Url::toRoute(['/site/logout']); ?>" data-method="post">Logout</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- end:user -->
			<?php endif; ?>
		</div>
		<!-- end:right -->

	</div>
	<!-- end:cnt -->

</div>

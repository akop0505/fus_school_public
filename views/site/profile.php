<?php

use app\models\StudentsFeatured;
use app\models\StudentsArchived;
use app\models\Post;
use app\models\PostRepost;
use app\models\PostFeatured;
use app\models\TagFeatured;
use app\models\User;
use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use \app\models\Form;

/* @var $this yii\web\View */
/* @var app\models\User $model */
/* @var app\models\Post $post */
/* @var app\models\Post $drawerVideo */
/* @var app\models\Post $drawerNoVideo */
/* @var app\models\Channel $channel */
/* @var string $page */
/* @var string $title */
/* @var string $profileContent */
/* @var int $countLatest */
/* @var int $countWatchLater */
/* @var int $countLike */
/* @var int $countFavorite */
/* @var int $countSubscriptions */
/* @var int $countActivity */
/* @var yii\data\Pagination $pages */

$this->title .= $model->getUserFullName() ? (' - '. $model->getUserFullName()) : '';

$urlLatest = Url::toRoute(['site/profile', 'item' => $model, 'postType' => 'latest']);
$urlFavorite = Url::toRoute(['site/profile', 'item' => $model, 'postType' => 'favorite']);
$urlLike = Url::toRoute(['site/profile', 'item' => $model, 'postType' => 'like']);
$urlSubscriptions = Url::toRoute(['site/profile', 'item' => $model, 'postType' => 'subscriptions']);
$urlActivity = Url::toRoute(['site/profile', 'item' => $model, 'postType' => 'activity']);
$urlWatchLater = Url::toRoute(['site/profile', 'item' => $model, 'postType' => 'watchLater']);
$editSchoolProfile = Url::toRoute(['institution/school-admin-update']);
$editUserProfile = Url::toRoute(['user/profile-update']);
$publishPost = Url::toRoute(['post/publish-post']);
$postsAdmin = Url::toRoute(['profile/posts']);
$postsDraft = Url::toRoute(['profile/posts', 'page'=>'draft','PostSearch'=>['isActive'=>0,'isApproved'=>0]]);
$contest = Url::toRoute(['/profile/contest']);
$rePostsAdmin = Url::toRoute(['profile/repost']);
$postsFeaturedAdmin = Url::toRoute(['profile/featured']);
$studentsFeaturedAdmin = Url::toRoute(['profile/featured-students']);
$studentsArchivedAdmin = Url::toRoute(['profile/archived-students']);
$tagsFeaturedAdmin = Url::toRoute(['profile/featured-tags']);
$usersAdmin = Url::toRoute(['profile/users']);
$usersAnalytics = Url::toRoute(['profile/analytics']);
$facebookConnect = Url::toRoute(['profile/facebook']);
?>
<!-- start:header -->
<header id="header">

	<!-- start:cover -->
	<div class="cover">

		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->

		<!-- start:profile -->
		<div class="profile">

			<!-- start:cnt -->
			<div class="cnt clr">

				<!-- start:avatar -->
				<?php if($model->hasPhoto || !empty($model->avatar_name)): ?>
					<a href="<?= $model->getUrl() ?>" class="avatar" style="background-image: url('<?= $model->getAvatar($model->hasPhoto, $model->avatar_name); ?>');"></a>
				<?php else: ?>
					<a href="<?= $model->getUrl(); ?>" data-initials="<?= $model->getUserInitials(); ?>" class="avatar"></a>
				<?php endif; ?>
				<!-- end:avatar -->

				<!-- start:title -->
				<h2><?= Html::encode($model->getUserFullName()); ?></h2>
				<!-- end:title -->

				<!-- start:school name -->
				<?php if($model->institutionId): ?>
				<h3>
					<a href="<?= Url::toRoute(['site/school', 'item' =>  $model->institution]); ?>">
					<?= Html::encode($model->institution->name); ?>
					</a>
				</h3>
				<?php endif; ?>
				<!-- end:school name -->

				<!-- start:description -->
				<div class="description">

					<p><?= $model->about; ?></p>

				</div>
				<!-- end:description -->

			</div>
			<!-- end:cnt -->

		</div>
		<!-- end:profile -->

	</div>
	<!-- end:cover -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">

	<!-- start:general -->
	<section class="general">

		<!-- start:cnt -->
		<div class="cnt clr">

			<!-- start:column -->
			<div class="column">

				<?php echo Alert::widget(); ?>
				<?= $profileContent ?>

			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<div class="sidebar">

				<ul>
					<li class="<?php if($page == 'default' || $page == 'latest') echo 'current'; ?>">
						<a href="<?= $urlLatest; ?>">
							<i class="fa fa-pencil-square-o fa-fw margin-5-right"></i>
							Posts
							<span><?= $countLatest; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'default' || $page == 'favorite') echo 'current'; ?>">
						<a href="<?= $urlFavorite; ?>">
							<i class="fa fa-star fa-fw margin-5-right"></i>
							Favorite posts
							<span><?= $countFavorite; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'like') echo 'current'; ?>">
						<a href="<?= $urlLike; ?>">
							<i class="fa fa-heart fa-fw margin-5-right"></i>
							Liked posts
							<span><?= $countLike; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'subscriptions') echo 'current'; ?>">
						<a href="<?= $urlSubscriptions; ?>">
							<i class="fa fa-feed fa-fw margin-5-right"></i>
							Subscriptions
							<span><?= $countSubscriptions; ?></span>
						</a>
					</li>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id): ?>
						<li class="<?php if($page == 'activity') echo 'current'; ?>">
							<a href="<?= $urlActivity; ?>">
								<i class="fa fa-random fa-fw margin-5-right"></i>
								Activity
								<span><?= $countActivity; ?></span>
							</a>
						</li>
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id): ?>
						<li class="<?php if($page == 'watchLater') echo 'current'; ?>">
							<a href="<?= $urlWatchLater; ?>">
								<i class="fa fa-clock-o fa-fw margin-5-right"></i>
								View later
								<span><?= $countWatchLater; ?></span>
							</a>
						</li>
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id): ?>
					<li class="<?php if($page == 'userProfile') echo 'current'; ?>">
						<a href="<?= $editUserProfile; ?>">
							<i class="fa fa-user fa-fw margin-5-right"></i>
							Edit your profile
						</a>
					</li>
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id && Yii::$app->user->can('SchoolAdmin')): ?>
						<li class="<?php if($page == 'schoolProfile') echo 'current'; ?>">
							<a href="<?= $editSchoolProfile; ?>">
								<i class="fa fa-university fa-fw margin-5-right"></i>
								Edit school profile
							</a>
						</li>
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id && Yii::$app->user->can('SchoolAuthor')): ?>
						<li class="<?php if($page == 'publishPost') echo 'current'; ?>">
							<a href="<?= $publishPost; ?>">
								<i class="fa fa-pencil fa-fw margin-5-right"></i>
								Publish post
							</a>
						</li>
					<?php endif; ?>
                    <?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id): ?>
                        <li class="<?php if($page == 'postsAdmin') echo 'current'; ?>">
                            <a href="<?= $postsAdmin; ?>">
                                <i class="fa fa-newspaper-o fa-fw margin-5-right"></i>
                                Posts admin
                                <?php
                                $param = [
                                    'institutionId' => Yii::$app->user->identity->institutionId,
                                    'isApproved' => 0
                                ];
                                if(!Yii::$app->user->can('ApprovePost') && !Yii::$app->user->can('ApproveVideo')) $param['createdById'] = Yii::$app->user->id;
                                ?>
                                <span><?= Post::find()->joinWith(['createdBy'], false)->where($param)->count(); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php /*if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id): */?><!--
						<li class="<?php /*if($page == 'draft') echo 'current'; */?>">
							<a href="<?/*= $postsDraft; */?>">
								<i class="fa fa-suitcase fa-fw margin-5-right"></i>
								Draft
								<?php
/*									$param = [
										'institutionId' => Yii::$app->user->identity->institutionId,
										'isApproved' => 0,
                                        'isActive'=>0
									];
									if(!Yii::$app->user->can('ApprovePost') && !Yii::$app->user->can('ApproveVideo')) $param['createdById'] = Yii::$app->user->id;
								*/?>
								<span><?/*= Post::find()->joinWith(['createdBy'], false)->where($param)->count(); */?></span>
							</a>
						</li>
					--><?php /*endif; */?>
                    <?php
/*                    if(!Yii::$app->user->isGuest && Yii::$app->user->can('SuperAdmin')): */?><!--
                        <li class="<?php /*if($page == 'contest') echo 'current'; */?>">
                            <a href="<?/*= $contest; */?>">
                                <i class="fa fa-file-text-o fa-fw margin-5-right"></i>
                                Contests
                                <?php
/*                                $param = [
                                    'isApproved' => 0
                                ];
                                */?>
                                <span><?/*= Form::find()->where($param)->count(); */?></span>
                            </a>
                        </li>
                    --><?php /*endif; */?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id && (Yii::$app->user->can('ApprovePost') || Yii::$app->user->can('ApproveVideo'))): ?>
						<li class="<?php if($page == 'repostsAdmin') echo 'current'; ?>">
							<a href="<?= $rePostsAdmin; ?>">
								<i class="fa fa-share fa-fw margin-5-right"></i>
								Repost admin
								<span><?= PostRepost::find()->where(['institutionId' => Yii::$app->user->identity->institutionId, 'isApproved' => 0])->count(); ?></span>
							</a>
						</li>
						<li class="<?php if($page == 'postsFeaturedAdmin') echo 'current'; ?>">
							<a href="<?= $postsFeaturedAdmin; ?>">
								<i class="fa fa-sort-numeric-asc fa-fw margin-5-right"></i>
								Featured post admin
								<span><?= PostFeatured::find()->where(['institutionId' => Yii::$app->user->identity->institutionId])->count(); ?></span>
							</a>
						</li>
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id && Yii::$app->user->can('ApproveUser')): ?>
						<li class="<?php if($page == 'usersAdmin') echo 'current'; ?>">
							<a href="<?= $usersAdmin; ?>">
								<i class="fa fa-users fa-fw margin-5-right"></i>
								Users admin
								<span><?= User::find()->where(['institutionId' => Yii::$app->user->identity->institutionId, 'status' => 'pending'])->count(); ?></span>
							</a>
						</li>
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id && Yii::$app->user->can('SchoolAdmin')): ?>
						<li class="<?php if($page == 'studentsFeaturedAdmin') echo 'current'; ?>">
							<a href="<?= $studentsFeaturedAdmin; ?>">
								<i class="fa fa-user-plus fa-fw margin-5-right"></i>
								Featured students
								<span><?= StudentsFeatured::find()->where(['institutionId' => Yii::$app->user->identity->institutionId])->count(); ?></span>
							</a>
						</li>
                                                <li class="<?php if($page == 'studentsArchivedAdmin') echo 'current'; ?>">
                                                        <a href="<?= $studentsArchivedAdmin; ?>">
                                                                <i class="fa fa-user-plus fa-fw margin-5-right"></i>
                                                                Archived students
                                                                <span><?= StudentsArchived::find()->where(['institutionId' => Yii::$app->user->identity->institutionId])->count(); ?></span>
                                                        </a>
                                                </li>
						<li class="<?php if($page == 'tagsFeaturedAdmin') echo 'current'; ?>">
							<a href="<?= $tagsFeaturedAdmin; ?>">
								<i class="fa fa-tags fa-fw margin-5-right"></i>
								Featured tags
								<span><?= TagFeatured::find()->where(['institutionId' => Yii::$app->user->identity->institutionId])->count(); ?></span>
							</a>
						</li>
						<!--<li class="<?php if($page == 'facebook') echo 'current'; ?>">
							<a href="<?= $facebookConnect; ?>">
								<i class="fa fa-facebook-square fa-fw margin-5-right"></i>
								Facebook connect
							</a>
						</li>-->
					<?php endif; ?>
					<?php if(!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id): ?>
						<li class="<?php if($page == 'analytics') echo 'current'; ?>">
							<a href="<?= $usersAnalytics; ?>">
								<i class="fa fa-bar-chart fa-fw margin-5-right"></i>
								Analytics
							</a>
						</li>
					<?php endif; ?>
				</ul>

			</div>
			<!-- end:sidebar -->

		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:general -->

</main>
<!-- end:main -->

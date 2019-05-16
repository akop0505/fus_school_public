<?php

use yii\helpers\Html;
use app\models\Post;
use app\models\State;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var app\models\Post $postsVideo */
/* @var app\models\Post $postsRead */
/* @var app\models\State $states */

$postsVideo = Post::find()->where(['isActive' => 1])
	->andWhere(['IS NOT', 'video', NULL])->orderBy('id desc')->limit(8)->all();

$postsRead = Post::find()->where(['isActive' => 1])
	->andWhere(['IS', 'video', NULL])->orderBy('id desc')->limit(8)->all();

$contests = \app\models\Contest::find()->where(['isActive' => 1])->orderBy('id desc')->limit(8)->all();

$states = State::find()->all();

$urlCity =  Url::to(['auto-complete/city']);
$urlAbout =  Url::toRoute(['site/content', 'contentType' => 'about']);
$urlResources =  Url::to(['site/resources']);
?>
<div class="drawer">

	<!-- start:scrollbar -->
	<div class="scrollbar">

		<!-- start:tbl -->
		<div class="tbl">

			<!-- start:trow -->
			<div class="trow">

				<!-- start:tcell -->
				<div class="tcell vertical-top">

					<!-- start:top -->
					<div class="top">
						<a href="/">
							<img src="/images/fusfoo.svg" alt="Fusfoo">
						</a>
						<a href="#" class="menu">
							<i class="fa fa-angle-left"></i>
						</a>
					</div>
					<!-- end:top -->

					<!-- start:navigation -->
					<ul class="navigation">
						<li>
							<a href="<?= Url::to(['site/school-search']) ?>" data-part="schools">
								<i class="fa fa-university fa-fw margin-10-right"></i>
								Schools
							</a>
						</li>
						<li>
							<a href="<?= Url::to(['site/browse-school']) ?>" class="skipSubmenu">
								<i class="fa fa-search-plus fa-fw margin-10-right"></i>
								Browse Schools
							</a>
						</li>
						<li>
							<a href="<?= Url::to(['site/search']) ?>" class="skipSubmenu">
								<i class="fa fa-search fa-fw margin-10-right"></i>
								Search
							</a>
						</li>
						<li>
							<a href="#" data-part="watch">
								<i class="fa fa-film fa-fw margin-10-right"></i>
								Watch
							</a>
						</li>
						<li>
							<a href="#" data-part="read">
								<i class="fa fa-pencil fa-fw margin-10-right"></i>
								Read
							</a>
						</li>
                        <!--<li>
                            <a href="#" data-part="contests">
                                <i class="fa fa-file-text-o fa-fw margin-10-right"></i>
                                Contests
                            </a>
                        </li>-->
                        <?php if(Yii::$app->user->isGuest) { ?>
						<li>
							<a href="<?= Url::to(['site/login']) ?>"  data-part="login">
								<i class="fa fa-user fa-fw margin-10-right"></i>
								Log In / Sign Up
							</a>
						</li>
						<?php
						} else {
						?>
						<li>
							<a href="<?= Yii::$app->user->identity->getUrl(); ?>" class="skipSubmenu">
								<i class="fa fa-user fa-fw margin-10-right"></i>
								My Profile
							</a>
						</li>
						<li>
							<a href="<?php echo Url::toRoute(['/site/logout']); ?>" class="skipSubmenu" data-method="post">
								<i class="fa fa-sign-out fa-fw margin-10-right"></i>
								Logout
							</a>
						</li>
						<?php } ?>
					</ul>
					<!-- end:navigation -->

				</div>
				<!-- end:tcell -->

			</div>
			<!-- end:trow -->

			<!-- start:trow -->
			<div class="trow">

				<!-- start:tcell -->
				<div class="tcell vertical-bottom">

					<!-- start:subnavigation -->
					<ul class="subnavigation">
						<li>
							<a href="<?= $urlAbout; ?>">About Fusfoo</a>
						</li>
						<li>
							<a href="https://www.fusfoo.com/site/resources-partial.html?contentType=resources-product">Product Overview</a>
						</li>
						<li>
							<a href="<?= $urlResources; ?>">Learn More</a>
						</li>
						<li>
							<a href="https://fusfoo.zendesk.com/hc/en-us" target="_blank">Help Center</a>
						</li>
					</ul>
					<!-- end:subnavigation -->

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

				</div>
				<!-- end:tcell -->

			</div>
			<!-- end:trow -->

		</div>
		<!-- end:tbl -->

	</div>
	<!-- end:scrollbar -->

	<!-- start:expand -->
	<div class="expand">

		<!-- start:part -->
		<div class="part" data-part="schools">

			<!-- start:header -->
			<div class="header">
				Search Schools
				<a href="<?= Url::to(['site/school-search']) ?>" class="close">
					<i class="fa fa-times"></i>
				</a>
			</div>
			<!-- end:header -->

			<!-- start:scrollbar -->
			<div class="scrollbar">

				<!-- start:content -->
				<div class="content">

					<p>
						Find a school channel on the Fusfoo high school digital network.
					</p>

					<?php $form = ActiveForm::begin(['id' => 'school-search','method' => 'get', 'action' => Url::to(['site/search-institution'])]); ?>

						<div class="row">
							<input id="schoolName" name="SchoolSearchForm[schoolName]" type="text" class="white size-40" placeholder="High School Name">
						</div>

						<div class="row">
							<select style="width: 100%;" name="SchoolSearchForm[stateId]" id="drawerStateId">
								<option value="">State</option>
								<?php foreach($states as $state)
								{
									echo '<option value="'. $state->code .'">'. $state->name .'</option>';
								}
								?>
							</select>
						</div>

						<div class="row">
							<?php
								echo Select2::widget([
									'name' => 'SchoolSearchForm[cityId]',
									'options' => ['placeholder' => 'Search for city ...', 'class' => 'skipSelect2'],
									'pluginOptions' => [
										'allowClear' => true,
										'minimumInputLength' => 3,
										'ajax' => [
											'url' => $urlCity,
											'dataType' => 'json',
											'data' => new JsExpression('function(params){ return {term:params.term}; }')
										],
										'templateSelection' => new JsExpression('function (city) { return city.name !== undefined ? city.name : city.text; }'),
									],
									'pluginEvents' => [
										"select2:select" => 'function (e) {
											var tmpData = e.params.data;
											$("#drawerStateId").val(tmpData.cityStateId).trigger("change");
											$("#drawerZip").val(tmpData.cityZip).trigger("change");
										}',
									]
								]);
							?>
						</div>

						<div class="row">
							<input id="drawerZip" name="SchoolSearchForm[zip]" type="text" class="white size-40" placeholder="Zip code">
						</div>

						<div class="row">
							<button type="submit" class="button red size-60">Search</button>
						</div>

					<?php ActiveForm::end(); ?>
				</div>
				<!-- end:content -->

			</div>
			<!-- end:scrollbar -->

		</div>
		<!-- end:part -->

		<!-- start:part -->
		<div class="part" data-part="watch">

			<!-- start:header -->
			<div class="header">
				Watch
				<a href="#" class="close">
					<i class="fa fa-times"></i>
				</a>
			</div>
			<!-- end:header -->

			<!-- start:scrollbar -->
			<div class="scrollbar">

				<!-- start:articles -->
				<ul class="articles">

					<?php foreach($postsVideo as $video): ?>

						<li class="current">
							<a href="<?= $video->getUrl(); ?>">
								<span class="thumbnail" style="background-image: url('<?= $video->getPicBaseUrl('hasThumbPhoto'). $video->getPicName('hasThumbPhoto'); ?>');"></span>
								<h2><?= Html::encode($video->title); ?></h2>
								<p>
									<?php
									$line = trim(substr(strip_tags($video->postText), 0, 50));
									if(preg_match('/^.{1,50}\b/s', $line, $match))
									{
										$line = $match[0];
									}
									echo $line , '...';
									?>
								</p>
							</a>
						</li>

					<?php endforeach; ?>
				</ul>
				<!-- end:articles -->

			</div>
			<!-- end:scrollbar -->

		</div>
		<!-- end:part -->

		<!-- start:part -->
		<div class="part" data-part="read">

			<!-- start:header -->
			<div class="header">
				Read
				<a href="#" class="close">
					<i class="fa fa-times"></i>
				</a>
			</div>
			<!-- end:header -->

			<!-- start:scrollbar -->
			<div class="scrollbar">

				<!-- start:articles -->
				<ul class="articles">
					<?php foreach($postsRead as $article): ?>

						<li class="current">
							<a href="<?= $article->getUrl(); ?>">
								<span class="thumbnail" style="background-image: url('<?= $article->getPicBaseUrl('hasThumbPhoto'). $article->getPicName('hasThumbPhoto'); ?>');"></span>
								<h2><?= Html::encode($article->title); ?></h2>
								<p>
									<?php
									$line = trim(substr(strip_tags($article->postText), 0, 50));
									if(preg_match('/^.{1,50}\b/s', $line, $match))
									{
										$line = $match[0];
									}
									echo $line , '...';
									?>
								</p>
							</a>
						</li>

						<?php endforeach; ?>
				</ul>
				<!-- end:articles -->

			</div>
			<!-- end:scrollbar -->

		</div>
		<!-- end:part -->

		<!-- start:part -->
		<div class="part" data-part="login">

			<!-- start:header -->
			<div class="header">
				Log In / Sign Up
				<a href="#" class="close">
					<i class="fa fa-times"></i>
				</a>
			</div>
			<!-- end:header -->

			<!-- start:scrollbar -->
			<div class="scrollbar">

				<!-- start:content -->
				<div class="content">

					<p>
						Join the Fusfoo high school digital network now to follow all of your favorite channels and creators.
					</p>

					<?php $form = ActiveForm::begin(['id' => 'login-form', 'action' => ['/site/login']]); ?>

					<div class="row">
						<input type="text" class="white size-40" name="LoginForm[username]" placeholder="Username">
					</div>

					<div class="row">
						<input type="password" class="white size-40" name="LoginForm[password]" placeholder="Password">
					</div>

					<div class="row">
						<button type="submit" class="button red size-60">Log In</button>
					</div>

					<?php ActiveForm::end(); ?>

					<div class="row horizontal-center">
						<a href="<?= Url::to(['site/request-password-reset']) ?>" class="signup">Lost password?</a>
					</div>

					<div class="row horizontal-center">
						<a href="<?= Url::to(['site/register']) ?>" class="signup">Sign Up for Fusfoo</a>
					</div>

				</div>
				<!-- end:content -->

			</div>
			<!-- end:scrollbar -->

		</div>
		<!-- end:part -->

        <!-- start:part -->
        <div class="part" data-part="contests">

            <!-- start:header -->
            <div class="header">
                Contest
                <a href="#" class="close">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <!-- end:header -->

            <!-- start:scrollbar -->
            <div class="scrollbar">

                <!-- start:articles -->
                <ul class="articles">

                    <?php foreach($contests as $contest): ?>

                        <li class="current">
                            <a href="<?= Url::to(['/form/store','id'=>$contest->id]) ?>">
                                <span class="thumbnail" style="background-image: url('<?= $contest->getPicBaseUrl('hasHeaderPhoto'). $contest->getPicName('hasThumbPhoto'); ?>');"></span>
                                <h2><?= Html::encode($contest->title); ?></h2>
                                <p>
                                    <?php
                                    $line = trim(substr(strip_tags($contest->content), 0, 50));
                                    if(preg_match('/^.{1,50}\b/s', $line, $match))
                                    {
                                        $line = $match[0];
                                    }
                                    echo $line , '...';
                                    ?>
                                </p>
                            </a>
                        </li>

                    <?php endforeach; ?>
                </ul>
                <!-- end:articles -->

            </div>
            <!-- end:scrollbar -->

        </div>
        <!-- end:part -->

	</div>
	<!-- end:expand -->

</div>
<!-- end:drawer -->

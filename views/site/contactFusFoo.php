<?php

use app\models\State;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\ContactFusFooForm */

$this->title = 'Contact Fusfoo';
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

			<?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

				<div class="alert alert-success">
					Thank you for contacting us. We will respond to you as soon as possible.
				</div>

			<?php else: ?>
				<section class="section user-form">
					<h2><?= Html::encode($this->title) ?></h2>
					<p>
						<?= Yii::t('app', 'Thank you for your interest in Fusfoo!
						Please complete the form below and we will get back to you right away about how
						your high school can be part of the Fusfoo High School Digital Network.')
						?>
					</p>
					<p>
						<?= Yii::t('app', 'Are you a student?') ?>
						<a href="<?= Url::to(['site/article', 'id' => 15]) ?>">Click here</a>
					</p>
					<?php
					$form = ActiveForm::begin([
						'id' => 'contact-form',
						'options' => ['class' => 'autoFocus']]);
					?>
					<?= $form->field($model, 'firstName')->textInput(['autofocus' => true]) ?>

					<?= $form->field($model, 'lastName')->textInput(['autofocus' => true]) ?>

					<?= $form->field($model, 'highSchool')->textInput() ?>

					<?= $form->field($model, 'stateId')->dropDownList(State::dropDownFind(), ['prompt' => '']) ?>

					<?= $form->field($model, 'schoolPosition')->textInput() ?>

					<?= $form->field($model, 'email') ?>

					<?= $form->field($model, 'phone')->textInput() ?>

					<?= $form->field($model, 'referral')->textInput() ?>

					<?= $form->field($model, 'message')->textArea(['rows' => 6]) ?>

					<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
						'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
					]) ?>

					<div class="form-group">
						<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
					</div>

					<?php ActiveForm::end(); ?>
				</section>

			<?php endif; ?>
		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->
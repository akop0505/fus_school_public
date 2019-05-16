<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\captcha\Captcha;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\RegisterForm */
/* @var $regDone bool */

$this->title = Yii::t('app', 'Register');

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
			<?php echo Alert::widget(); ?>

			<?php
			if($regDone == 1) echo Yii::t('app', "Registration complete! Your account has to be activated by the school's administrator in order to log in.");
			elseif($regDone == -1) echo Yii::t('app', "Registration complete! Account activation link has been sent to your e-mail. You have to click the link to activate the account.");
			else { ?>
			<section class="section user-form">
				<h2><?= Html::encode($this->title) ?></h2>
				<p><?= Yii::t('app', 'Changing the way high school students discover the bigger picture') ?>.</p>
						<?php $form = ActiveForm::begin([
							'id' => 'register-form',
							'enableClientValidation' => true,
							'enableAjaxValidation' => true,
							'options' => ['class' => 'autoFocus']]);
						?>
						<?= $form->field($model, 'firstName') ?>
						<?= $form->field($model, 'lastName') ?>
						<?= $form->field($model, 'school')->dropDownList(\app\models\Institution::dropDownFind(['isActive' => 1]), ['prompt' => 'None']) ?>
						<?= $form->field($model, 'email') ?>
						<?= $form->field($model, 'username') ?>
						<?= $form->field($model, 'password')->passwordInput() ?>
						<?= $form->field($model, 'passwordRepeat')->passwordInput()->label(Yii::t('app', 'Re-type password')) ?>
                        <div class="form-group" style="font-weight:700">
                            <?= Html::checkbox("subscribe",false,[
                                'label'=>'Stay connected to Fusfoo by subscribing to our newsletter.'
                            ])?>
                        </div>
                        <div class="form-group">
                            <p>Fusfoo does not share personal information with third parties and respects privacy according to our <?= Html::a("<b>privacy policy.</b>",\yii\helpers\Url::to(["site/content","contentType"=>"privacy-policy"]),["target"=>"_blank"])?> </p>
                        </div>
						<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
							'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>('
							. Yii::t('app', 'Can\'t read the text above? Click the image to reload.') .')',
						]) ?>
						<!-- <a href="<?php echo Url::toRoute(['site/register', 'provider' => 'Twitter']); ?>">Signup with Twitter</a>
						<a href="<?php echo Url::toRoute(['site/register', 'provider' => 'Google']); ?>">Signup with Google</a> -->
						<div class="form-group">
							<?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
						</div>
						<?php ActiveForm::end(); ?>
			</section>
		<?php } ?>
		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->

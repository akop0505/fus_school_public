<?php

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\models\forms\PasswordResetRequestForm */

$this->title = Yii::t('app', 'Request password reset');

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

			<section class="section user-form">
				<h2><?= Html::encode($this->title) ?></h2>
				<p>
					<?= Yii::t('app', 'Please fill out your email. A link to reset password will be sent there.') ?>
				</p>
				<?php
					$form = ActiveForm::begin([
						'id' => 'request-password-reset-form',
						'options' => ['class' => 'autoFocus']
					]);
				?>

				<?= $form->field($model, 'email') ?>

				<div class="form-group">
					<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</section>
		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->

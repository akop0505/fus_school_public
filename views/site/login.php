<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\LoginForm */

$this->title = 'Login';

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
			<div class="user-form">
				<?php echo Alert::widget(); ?>

				<h1><?= Html::encode($this->title) ?></h1>

				<p>Please fill out the following fields to login:</p>

				<?php $form = ActiveForm::begin(
					[
						'id' => 'login-form',
						'options' => ['class' => 'form-horizontal'],
						'fieldConfig' => [
							'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
							'labelOptions' => ['class' => 'col-lg-1 control-label'],
						],
					]
				); ?>

				<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

				<?= $form->field($model, 'password')->passwordInput() ?>

				<?= $form->field($model, 'rememberMe')->checkbox(
					[
						'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
					]
				) ?>

				<div class="form-group">
					<div class="col-lg-offset-1 col-lg-11">
						<?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
					</div>
				</div><br>

				<?php ActiveForm::end(); ?>

				<div class="form-group">
					<a href="<?= Url::to(['site/request-password-reset']) ?>" data-part="login">
						<i class="fa fa-key margin-10-right"></i>
						Lost password?
					</a>
				</div>

				<div class="form-group">
					<a href="<?= Url::to(['site/register']) ?>" data-part="login">
						<i class="fa fa-user fa-fw margin-10-right"></i>
						Sign Up
					</a>
				</div>
			</div>
		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->
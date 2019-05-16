<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'username') ?>

	<?= $form->field($model, 'authKey') ?>

	<?= $form->field($model, 'passwordHash') ?>

	<?= $form->field($model, 'passwordResetToken') ?>

	<?php // echo $form->field($model, 'email') ?>

	<?php // echo $form->field($model, 'emailVerified') ?>

	<?php // echo $form->field($model, 'status') ?>

	<?php // echo $form->field($model, 'createdAt') ?>

	<?php // echo $form->field($model, 'updatedAt') ?>

	<?php // echo $form->field($model, 'lastLogin') ?>

	<?php // echo $form->field($model, 'firstName') ?>

	<?php // echo $form->field($model, 'lastName') ?>

	<?php // echo $form->field($model, 'isMale') ?>

	<?php // echo $form->field($model, 'dateOfBirth') ?>

	<?php // echo $form->field($model, 'mobilePhone') ?>

	<?php // echo $form->field($model, 'timeZoneId') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

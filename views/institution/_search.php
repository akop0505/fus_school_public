<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\InstitutionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="institution-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'name') ?>

	<?= $form->field($model, 'cityId') ?>

	<?= $form->field($model, 'address') ?>

	<?= $form->field($model, 'themeColor') ?>

	<?php // echo $form->field($model, 'posts') ?>

	<?php // echo $form->field($model, 'likes') ?>

	<?php // echo $form->field($model, 'subscribers') ?>

	<?php // echo $form->field($model, 'isActive') ?>

	<?php // echo $form->field($model, 'createdAt') ?>

	<?php // echo $form->field($model, 'createdById') ?>

	<?php // echo $form->field($model, 'updatedAt') ?>

	<?php // echo $form->field($model, 'updatedById') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

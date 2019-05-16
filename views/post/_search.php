<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'title') ?>

	<?= $form->field($model, 'postText') ?>

	<?= $form->field($model, 'hasHeaderPhoto') ?>

	<?= $form->field($model, 'hasThumbPhoto') ?>

	<?php // echo $form->field($model, 'views') ?>

	<?php // echo $form->field($model, 'isActive') ?>

	<?php // echo $form->field($model, 'isApproved') ?>

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

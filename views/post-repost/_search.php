<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\PostRepostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-repost-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'postId') ?>

	<?= $form->field($model, 'institutionId') ?>

	<?= $form->field($model, 'isApproved') ?>

	<?= $form->field($model, 'createdAt') ?>

	<?= $form->field($model, 'createdById') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

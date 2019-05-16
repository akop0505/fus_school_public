<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\search\ContentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="content-search">

	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'urlSlug') ?>

	<?= $form->field($model, 'title') ?>

	<?= $form->field($model, 'bodyText') ?>

	<?= $form->field($model, 'createdAt') ?>

	<?php // echo $form->field($model, 'createdById') ?>

	<?php // echo $form->field($model, 'updatedAt') ?>

	<?php // echo $form->field($model, 'updatedById') ?>

	<?php // echo $form->field($model, 'extraHtml') ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

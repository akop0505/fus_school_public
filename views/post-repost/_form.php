<?php

use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\PostRepost */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
?>

<div class="post-repost-form">

	<?php $form = ActiveForm::begin(); ?>

	<?php
	echo $form->field($model, 'postId')->widget(Select2::classname(), [
		'options' => ['placeholder' => 'Search for post ...', 'class' => 'skipSelect2'],
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
				'url' => Url::toRoute(['auto-complete/post']),
				'dataType' => 'json',
				'data' => new JsExpression('function(params) { return {term:params.term}; }'),
			],
		],
	]);
	?>

	<?php
	echo $form->field($model, 'institutionId')->widget(Select2::classname(), [
		'options' => ['placeholder' => 'Search for institution ...', 'class' => 'skipSelect2'],
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
				'url' => Url::toRoute(['auto-complete/institution']),
				'dataType' => 'json',
				'data' => new JsExpression('function(params) { return {term:params.term}; }'),
			],
		],
	]);
	?>

	<?= $form->field($model, 'isApproved')->dropDownList($model::dropdownYesNo()) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

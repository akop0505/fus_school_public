<?php

use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Channel */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
$url = Url::to(['auto-complete/institution']);
$urlUser = Url::to(['auto-complete/user']);
?>

<div class="channel-form">

	<?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'description')->textInput(['maxlength' => 255]) ?>

	<?php

	echo $form->field($model, 'institutionId')->widget(Select2::classname(), [
		'initValueText' => $model->institutionId ? $model->institution : '', // set the initial display text
		'disabled' => true,
		'options' => ['placeholder' => 'Search for institution ...'],
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
				'url' => $url,
				'dataType' => 'json',
				'data' => new JsExpression('function(params){ return {term:params.term}; }')
			]
		],
	]);
	?>

	<?php

	echo $form->field($model, 'userId')->widget(Select2::classname(), [
		'initValueText' => $model->userId ? $model->user->getUserFullName() : '', // set the initial display text
		'disabled' => true,
		'options' => ['placeholder' => 'Search for user ...'],
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
				'url' => $urlUser,
				'dataType' => 'json',
				'data' => new JsExpression('function(params){ return {term:params.term}; }')
			]
		],
	]);
	?>

	<?= $form->field($model, 'hasPhoto', ['enableClientValidation' => false])->widget(FileInput::classname(), [
		'pluginOptions' => [
			'showCaption' => false,
			'showRemove' => false,
			'showUpload' => false,
			'showClose' => false,
			'browseClass' => 'btn btn-primary btn-block',
			'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
			'browseLabel' =>  Yii::t('app', 'Select Photo'),
			'initialPreview' => $model->hasPhoto ? [
				'<img src="'. $model->getPicBaseUrl('hasPhoto') . $model->getPicName('hasPhoto', true) .'" class="file-preview-image">'
			] : ''
		],
		'options' => ['accept' => 'image/*'],
	]) ?>

	<?= $form->field($model, 'hasPortraitPhoto', ['enableClientValidation' => false])->widget(FileInput::classname(), [
		'pluginOptions' => [
			'showCaption' => false,
			'showRemove' => false,
			'showUpload' => false,
			'showClose' => false,
			'browseClass' => 'btn btn-primary btn-block',
			'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
			'browseLabel' =>  Yii::t('app', 'Select Photo'),
			'initialPreview' => $model->hasPortraitPhoto ? [
				'<img src="'. $model->getPicBaseUrl('hasPortraitPhoto') . $model->getPicName('hasPortraitPhoto', true) .'" class="file-preview-image">'
			] : ''
		],
		'options' => ['accept' => 'image/*'],
	]) ?>

	<?= $form->field($model, 'isActive')->dropDownList($model::dropdownYesNo()) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

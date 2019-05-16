<?php

use app\models\Institution;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\TimeZone;
use app\assets\RedactorAsset;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
RedactorAsset::register($this);
?>

<div class="user-form">

	<?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>

	<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'password')->input('password')->label('Change password') ?>
	<?= $form->field($model, 'passwordConfirm')->input('password')->label('Repeat new password') ?>

	<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'emailVerified')->dropDownList($model::dropdownYesNo()) ?>

	<?php if($model->isNewRecord) echo $form->field($model, 'password')->passwordInput(); ?>

	<?= $form->field($model, 'status')->dropDownList([ 'pending' => 'Pending', 'active' => 'Active', 'deleted' => 'Deleted', ], ['prompt' => '']) ?>

	<?= $form->field($model, 'firstName')->textInput(['maxlength' => 64]) ?>

	<?= $form->field($model, 'lastName')->textInput(['maxlength' => 64]) ?>

	<?php if($model->isNewRecord) echo $form->field($model, 'isMale')->dropDownList([0 => Yii::t('app', 'Female'), 1 => Yii::t('app', 'Male')]) ; ?>

	<?php if($model->isNewRecord) echo $form->field($model, 'dateOfBirth')->widget(DateControl::className(), ['type' => 'date', 'displayTimezone' => 'UTC']); ?>

	<?php if($model->isNewRecord) echo $form->field($model, 'mobilePhone')->textInput(['maxlength' => 255]); ?>

	<?= $form->field($model, 'institutionId')->dropDownList(Institution::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'timeZoneId')->dropDownList(TimeZone::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'about')->textarea(['rows' => 6, 'maxlength' => 255, 'id' => 'about']) ?>

	<?= $form->field($model, 'hasPhoto', ['enableClientValidation' => false])->widget(FileInput::className(), [
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
	])
	?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs(<<<JSCLIP

	$('#about').redactor({
		lang: 'en',
		minHeight: 300,
		linebreaks: true,
		plugins: ['alignment', 'spellchecker'],
		buttons: ['format', 'bold', 'italic', 'deleted','lists', 'link', 'horizontalrule'],
		pasteImages: false,
		linkNofollow: true,
		linkify: false
	});
	$('form').on('submit',function(e){
	    $('#about').redactor("spellchecker.disable");
	});
JSCLIP
	, $this::POS_READY, 'redactor-init');

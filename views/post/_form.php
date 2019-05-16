<?php

use app\models\TimeZone;
use app\models\User;
use app\assets\RedactorAsset;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $form yii\widgets\ActiveForm */

$oldScenario = $model->getScenario();
$model->prepareForForm();
RedactorAsset::register($this);
if($model->video) $this->registerJsFile('https://content.jwplatform.com/players/'. $model->video .'-wtzqEpA3.js');
if($oldScenario == 'insert') $model->setScenario($oldScenario);

$timezoneId = Yii::$app->user->identity->attributes['timeZoneId'];
if($timezoneId)
{
	$tz = TimeZone::findOne($timezoneId);
	$timeZone = $tz->name;
}
else $timeZone = 'UTC';
?>

<div class="post-form">

	<?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'postText')->textarea(['rows' => 6, 'id' => 'postText']) ?>

	<?php if($model->video): ?>
	<!-- start:player -->
	<div class="form-group">
		<label class="control-label">Video</label>
		<div class="player">
			<div class="flex" id="botr_<?= $model->video ?>_wtzqEpA3_div"></div>
		</div>
	</div>
	<!-- end:player -->
	<?php endif; ?>

	<?= $form->field($model, 'hasHeaderPhoto', ['enableClientValidation' => $oldScenario == 'insert'])->label('Header (recommended 1400x400px)')->widget(FileInput::className(), [
		'pluginOptions' => [
			'showCaption' => false,
			'showRemove' => false,
			'showUpload' => false,
			'showClose' => false,
			'browseClass' => 'btn btn-primary btn-block',
			'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
			'browseLabel' =>  Yii::t('app', 'Select Photo'),
			'initialPreview' => $model->hasHeaderPhoto ? [
				'<img src="'. $model->getPicBaseUrl('hasHeaderPhoto') . $model->getPicName('hasHeaderPhoto', true) .'" class="file-preview-image">'
			] : ''
		],
		'options' => ['accept' => 'image/*'],
	])
	?>

	<?= $form->field($model, 'hasThumbPhoto', ['enableClientValidation' => $oldScenario == 'insert'])->label('Thumbnail (recommended 750x600px)')->widget(FileInput::className(), [
		'pluginOptions' => [
			'showCaption' => false,
			'showRemove' => false,
			'showUpload' => false,
			'showClose' => false,
			'browseClass' => 'btn btn-primary btn-block',
			'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
			'browseLabel' => Yii::t('app', 'Select Photo'),
			'initialPreview' => $model->hasThumbPhoto ? [
				'<img src="'. $model->getPicBaseUrl('hasThumbPhoto') . $model->getPicName('hasThumbPhoto', true) .'" class="file-preview-image">'
			] : ''
		],
		'options' => ['accept' => 'image/*'],
	])
	?>
	<?php
	$model->tag = [];
	foreach($model->tags as $one) $model->tag[(string)$one] = (string)$one;
	echo $form->field($model, 'tag')->widget(Select2::className(), [
		'options' => [
			'placeholder' => Yii::t('app', 'Choose tags ...'),
			'multiple' => true,
		],
		'showToggleAll' => false,
		'maintainOrder' => true,
		'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'multiple' => true,
			'tags'  => true,
			'createTag' => new JsExpression('function(params) {
				var term = $.trim(params.term);
				if(term === "") return null;
    		    return {id: term, text: term};
			}'),
			'ajax' => [
				'url' => Url::to(['auto-complete/tag']),
				'dataType' => 'json',
				'data' => new JsExpression('function(params) { return {term:params.term}; }'),
			],
		],
	]);
	?>

	<?php
	if(Yii::$app->user->can('SuperAdmin'))
	{
        $model->channel = $options = [];
        foreach($model->channels as $one)
		{
			if($one->isSystem) continue;
			$model->channel[] = $one->id;
			$options[$one->id] = (string)$one;
		}
		echo $form->field($model, 'channel')->widget(Select2::className(), [
			'data' => $options,
			'options' => [
				'placeholder' => Yii::t('app', 'Choose channels ...'),
				'multiple' => true
			],
			'showToggleAll' => false,
			'maintainOrder' => true,
			'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 3,
				'multiple' => true,
				'tags'  => true,
				'createTag' => new JsExpression('function(params) {
					return null;
				}'),
				'ajax' => [
					'url' => Url::toRoute(['auto-complete/channel', 'cond' => true, 'isSystem' => false]),
					'dataType' => 'json',
					'data' => new JsExpression('function(params) { return {term:params.term}; }'),
				],
			],
		]);
	}
	?>

	<?php
	if(Yii::$app->user->can('SchoolAdmin'))
	{
		echo $form->field($model, 'isActive')->dropDownList($model::dropdownYesNo());

		if($model->isNewRecord) echo $form->field($model, 'createdById')->dropDownList(User::dropDownFind(['institutionId' => Yii::$app->user->identity->institutionId, 'status' => 'active']))->label('Author');

		echo $form->field($model, 'dateToBePublished')->widget(DateControl::className(), [
			'type' => DateControl::FORMAT_DATETIME,
			'displayTimezone' => $timeZone,
			'saveTimezone' => 'UTC'
		]);
	}
	?>

	<?php
	if($model->isNewRecord)
	{
		$model->video = 0;
		echo $form->field($model, 'video')->dropDownList($model::dropdownYesNo());
	}
	?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs(<<<JSCLIP

	$('#postText').redactor({
		lang: 'en',
		minHeight: 300,
		linebreaks: true,
		plugins: ['alignment', 'spellchecker'],
		buttons: ['format', 'bold', 'italic', 'deleted', 'lists', 'link', 'horizontalrule'],
		pasteImages: false,
		linkNofollow: true,
		linkify: true
	});
	$('form').on('submit',function(e){
	    $('#postText').redactor("spellchecker.disable");
	});
JSCLIP
	, $this::POS_READY, 'redactor-init');

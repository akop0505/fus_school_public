<?php

use app\models\State;
use app\models\TimeZone;
use app\assets\RedactorAsset;
use kartik\widgets\ColorInput;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Institution */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
$url = Url::to(['auto-complete/city']);
$logoPic = $model->getPicBasePath('logo') . $model->getPicName('logo');
$headerPic = $model->getPicBasePath('header') . $model->getPicName('header');

RedactorAsset::register($this);
\yii\bootstrap\BootstrapPluginAsset::register($this);
?>

<div class="institution-form">

	<?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data'],'id'=>'form_id']); ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

	<?=
		$form->field($model, 'header')->label('Header (recommended 1400x400px)')->widget(FileInput::className(), [
			'pluginOptions' => [
				'showCaption' => false,
				'showRemove' => false,
				'showUpload' => false,
				'showClose' => false,
				'browseClass' => 'btn btn-primary btn-block',
				'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
				'browseLabel' =>  Yii::t('app', 'Select Photo'),
				'initialPreview' => file_exists($headerPic) ? [
					'<img src="'. $model->getPicBaseUrl('header') . $model->getPicName('header', true) .'" class="file-preview-image">'
				] : ''
			],
			'options' => ['accept' => 'image/*'],
		])
	?>

	<?=
		$form->field($model, 'logo')->label('Logo (recommended 400x400px)')->widget(FileInput::className(), [
			'pluginOptions' => [
				'showCaption' => false,
				'showRemove' => false,
				'showUpload' => false,
				'showClose' => false,
				'browseClass' => 'btn btn-primary btn-block',
				'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
				'browseLabel' =>  Yii::t('app', 'Select Photo'),
				'initialPreview' => file_exists($logoPic) ? [
					'<img src="'. $model->getPicBaseUrl('logo') . $model->getPicName('logo', true) .'" class="file-preview-image">'
				] : ''
			],
			'options' => ['accept' => 'image/*'],
		])
	?>

	<?= $form->field($model, 'schoolBanner')->checkbox(['id' => 'schoolBannerToggle']) ?>
	<fieldset id="schoolBannerSet" style="<?php if(!$model->schoolBanner) echo 'display: none;'; ?>">
	<?=
		$form->field($model, 'hasLatestPhoto', ['enableClientValidation' => false])->label('Photo (required 750x600px)')->widget(FileInput::className(), [
			'pluginOptions' => [
				'showCaption' => false,
				'showRemove' => false,
				'showUpload' => false,
				'showClose' => false,
				'browseClass' => 'btn btn-primary btn-block',
				'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
				'browseLabel' =>  Yii::t('app', 'Select Photo'),
				'initialPreview' => $model->hasLatestPhoto ? [
					'<img src="'. $model->getPicBaseUrl('hasLatestPhoto') . $model->getPicName('hasLatestPhoto', true) .'" class="file-preview-image">'
				] : ''
			],
			'options' => ['accept' => 'image/*'],
		])
	?>

	<?= $form->field($model, 'latestLink')->label('Link')->textInput(['maxlength' => 255]) ?>
	</fieldset>

	<?= $form->field($model, 'themeColor')->widget(ColorInput::className(), ['options' => ['readonly' => true]]); ?>

	<?= $form->field($model, 'aboutUsLinkColor')->widget(ColorInput::className(), ['options' => ['readonly' => true]]); ?>

	<?php

	echo $form->field($model, 'cityName')->label('City')->widget(Select2::className(), [
		'initValueText' => $model->cityId ? $model->city->name : '', // set the initial display text
		'options' => ['placeholder' => 'Search for city ...', 'class' => 'skipSelect2'],
		'pluginOptions' => [
			'tags' => true,
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
				'url' => $url,
				'dataType' => 'json',
				'data' => new JsExpression('function(params){ return {term:params.term}; }')
			],
			'templateSelection' => new JsExpression('function (city) { return city.name !== undefined ? city.name : city.text; }'),
		],
		'pluginEvents' => [
			"select2:select" => 'function (e) {
				var tmpData = e.params.data;
				$("#'. Html::getInputId($model, 'cityStateId') .'").val(tmpData.cityStateId).trigger("change");
				$("#'. Html::getInputId($model, 'cityZip') .'").val(tmpData.cityZip).trigger("change");
				$("#'. Html::getInputId($model, 'cityTimeZoneId') .'").val(tmpData.cityTimeZoneId).trigger("change");
			}',
		]
	]);
	?>

	<?= $form->field($model, 'cityStateId')->dropDownList(State::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'cityZip')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'cityTimeZoneId')->dropDownList(TimeZone::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'about')->textarea(['rows' => 6, 'maxlength' => 255, 'id' => 'about']) ?>
	<div class="form-group"><p id="charactersCount"></p></div>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
	function redactorPrepareTextForCount(html)
	{
		var text = html.replace(/<\/(.*?)>/gi, ' ');
		text = text.replace(/\u00a0/g, ' ');
		text = text.replace(/&nbsp;/gi, ' ');
		text = text.replace(/<(.*?)>/gi, '');
		text = text.replace(/\t/gi, '');
		text = text.replace(/\n/gi, ' ');
		text = text.replace(/\r/gi, ' ');
		text = text.replace(/\u200B/g, '');
		text = $.trim(text);
		return text;
	}
</script>
<?php

$this->registerJs(<<<JSCLIP

	$('#about').redactor({
		lang: 'en',
		minHeight: 300,
		linebreaks: true,
		plugins: ['alignment', 'spellchecker', 'limiter', 'counter'],
		callbacks: {
			counter: function(data)
			{
				var limit = this.opts.limiter;
				var remaining = limit - data.characters;
				if(remaining < 0) remaining = 0;
				document.getElementById("charactersCount").innerHTML =  remaining + ' characters remaining';
			},
			paste: function(pastedHtml)
			{
				var text = redactorPrepareTextForCount(this.code.get());
				var count = text.length;
				if(count + pastedHtml.length >= this.opts.limiter) return '';
				return pastedHtml;
			}
		},    
		buttons: ['format', 'bold', 'italic', 'deleted','lists', 'link', 'horizontalrule'],
		pasteImages: false,
		linkNofollow: true,
		linkify: false,
		limiter: 550
	});

	$('#schoolBannerToggle').on('click', function() {
		if($(this).prop('checked')) $("#schoolBannerSet").show();
		else $("#schoolBannerSet").hide();
	}).triggerHandler('click');
	
	$('form').on('submit',function(e){
	    $("#about").redactor("spellchecker.disable");
	});
JSCLIP
	, $this::POS_READY, 'redactor-init');



<?php

use app\assets\RedactorAsset;
use app\assets\UpdateProfileAsset;
use app\models\TimeZone;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = Yii::t('app', 'Update') . ' ' . (string)$model;
$model->prepareForForm();
RedactorAsset::register($this);
UpdateProfileAsset::register($this);
?>
<div class="user-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<div class="user-form">

		<?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>

		<?= $form->field($model, 'username', ['enableAjaxValidation' => true])->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'password')->input('password')->label('Change password') ?>
		<?= $form->field($model, 'passwordConfirm')->input('password')->label('Repeat new password') ?>

		<?= $form->field($model, 'email', ['enableAjaxValidation' => true])->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'firstName')->textInput(['maxlength' => 64]) ?>

		<?= $form->field($model, 'lastName')->textInput(['maxlength' => 64]) ?>

		<?php //$form->field($model, 'isMale')->dropDownList([0 => Yii::t('app', 'Female'), 1 => Yii::t('app', 'Male')]) ?>

		<?php //$form->field($model, 'dateOfBirth')->widget(DateControl::className(), ['type' => 'date', 'displayTimezone' => 'UTC']) ?>

		<?php //$form->field($model, 'mobilePhone')->textInput(['maxlength' => 255]) ?>

		<?= $form->field($model, 'timeZoneId')->dropDownList(TimeZone::dropDownFind()) ?>

		<?php if($model->institutionId) echo $form->field($model, 'about')->textarea(['rows' => 6, 'maxlength' => 255, 'id' => 'about']) ?>
		<?php
        $fileExplodedPath = ($model->hasPhoto || !empty($model->avatar_name)) ? explode('/',$model->getAvatar($model->hasPhoto, $model->avatar_name)):[];
        if($model->institutionId) echo $form->field($model, 'hasPhoto')->widget(FileInput::className(), [
            'name' => 'attachment_52',
			'pluginOptions' => [
                'showCaption' => false,
				'showRemove' => false,
				'showUpload' => false,
				'showClose' => false,
				'showCancel'=>false,
				'browseClass' => 'btn btn-block',
				'browseIcon' => '<i class="glyphicon glyphicon-camera"></i>',
				'browseLabel' =>  Yii::t('app', 'Select Photo'),
    				'previewFileType' => 'any',
    				'initialPreviewConfig' =>($model->hasPhoto || !empty($model->avatar_name)) ? [['caption' => end($fileExplodedPath)]]:[],
				'initialPreview' => ($model->hasPhoto || !empty($model->avatar_name)) ?
					'<img src="'. $model->getAvatar($model->hasPhoto, $model->avatar_name) .'" class="file-preview-image">'
				 : '<img src="" class="file-preview-image">'
//                'initialPreview'=>($model->hasPhoto || !empty($model->avatar_name)) ? [ 'http://localhost:8080'.$model->getAvatar($model->hasPhoto, $model->avatar_name)]:[]
			],
			'options' => ['accept' => 'image/*','multiple' => false],
		])
		?>
		<div class="form-group">
			<?= Html::button(Yii::t('app', 'Select avatar from existing list'), ['class' => 'btn btn-success', 'id' => 'select-avatar']) ?>
		</div>
		<?= $form->field($model, 'avatar_name')->hiddenInput(['id'=>'avatar-name'])->label(false); ?>

        <?php
        ?>
        <div class="form-group">
            <?= Html::checkbox("subscribe", $subscribed,[
                    'label'=>'Stay connected to Fusfoo by subscribing to our newsletter.'
            ])?>
        </div>
        <div class="form-group">
            <p>Fusfoo does not share personal information with third parties and respects privacy according to our <?= Html::a("<b>privacy policy.</b>",\yii\helpers\Url::to(["site/content","contentType"=>"privacy-policy"]),["target"=>"_blank"])?> </p>
        </div>
		<div class="form-group">
			<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>

	</div>

</div>
<?= Html::hiddenInput('avatarLink', $model->getAvatarLink(), ['id' => 'avatar-link']); ?>
<?= $this->render('_avatarModal', 
    [
	'avatars' => $model->getAvatarList(), 
	'model' => $model,
	'avatarLink' => $model->getAvatarLink()
    ]
); ?>
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

$this->registerJs(<<<JSAVATAR
	$('#select-avatar').click(function()
	{
	    $('#avatarModal').modal();
	    return false;
	});
    
	$('#userAvatar').imagepicker({});
    
	$('#selectAvatarOk').click(function()
	{
	    var avatarLink = $('#avatar-link').val();
	    $('.file-preview-frame div img').attr('src', avatarLink+$('#userAvatar').val());
	    $('#avatar-name').val($('#userAvatar').val());
	});
    
	$('#user-hasphoto').change(function()
	{
	    $('#avatar-name').val('');
	});

JSAVATAR
	, $this::POS_READY, 'avatar-init');


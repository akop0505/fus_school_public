<?php
use app\assets\RedactorAsset;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;

RedactorAsset::register($this);
$oldScenario = $model->getScenario();
?>
<div class="wrapper">
    <?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>
    <h1>Update form</h1>
    <div class="form-block-1">
        <div class="edit-form-content">
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
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <div class="form-block-2">
        <div class="form-info">
            <p><b>First Name: </b> <?= $model->first_name ?></p>
            <p><b>Last Name: </b> <?= $model->last_name ?></p>
            <p><b>Email: </b> <?= $model->email ?></p>
            <p><b>School: </b> <?= $model->school ?></p>
            <p><b>Type: </b> <?= $model->type ?></p>
        </div>
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

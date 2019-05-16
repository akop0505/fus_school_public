<?php

use app\assets\RedactorAsset;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\web\JsExpression;



RedactorAsset::register($this);
$oldScenario = $model->getScenario();
$model->prepareForForm();
?>
    <div class="post-form">
        <?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 6, 'id' => 'contest_content']) ?>

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
                'showCancel' => false,
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

        <?php
        if(Yii::$app->user->can('SuperAdmin'))
        {
            $model->channel = $options = [];
            foreach($model->channels as $one)
            {
                $model->channel[] = $one->id;
                $options[$one->id] = (string)$one->name;
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

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php

$this->registerJs(<<<JSCLIP

	$('#contest_content').redactor({
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
	    $('#contest_content').redactor("spellchecker.disable");
	});
JSCLIP
    , $this::POS_READY, 'redactor-init');

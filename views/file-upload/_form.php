<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\FileUpload */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
?>

<div class="file-upload-form">

	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

	<?=
		$form->field($model, 'fileName')->widget(FileInput::classname(), [
			'pluginOptions' => [
				'showCaption' => false,
				'showRemove' => false,
				'showUpload' => false,
				'showClose' => false,
				'browseClass' => 'btn btn-primary btn-block',
				'browseIcon' => '<i class="fa fa-file-o"></i>',
				'browseLabel' =>  Yii::t('app', 'Select File'),
			],
			'options' => ['accept' => '.pdf, .jpg, .png, .svg, .xls, .xlsx, .doc, .docx, .ppt, .pptx'],
		])
	?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Upload') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

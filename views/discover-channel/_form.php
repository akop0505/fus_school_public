<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\DiscoverChannel */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
?>

<div class="discover-channel-form">

	<?php $form = ActiveForm::begin(); ?>

	<?php
		echo $form->field($model, 'channelId')->widget(Select2::classname(), [
			'options' => ['placeholder' => 'Search for channel ...', 'class' => 'skipSelect2'],
			'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 3,
				'ajax' => [
					'url' => Url::toRoute(['auto-complete/channel', 'cond' => false, 'isSystem' => false]),
					'dataType' => 'json',
					'data' => new JsExpression('function(params) { return {term:params.term}; }'),
				],
			],
		]);
	?>

	<?= $form->field($model, 'sort')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\State;
use app\models\TimeZone;

/* @var $this yii\web\View */
/* @var $model app\models\City */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
?>

<div class="city-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'zip')->textInput() ?>

	<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'stateId')->dropDownList(State::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'lat')->textInput(['maxlength' => 9]) ?>

	<?= $form->field($model, 'lon')->textInput(['maxlength' => 9]) ?>

	<?= $form->field($model, 'timeZoneId')->dropDownList(TimeZone::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'isActive')->dropDownList($model::dropdownYesNo()) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php

use app\assets\RedactorAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */

RedactorAsset::register($this);
$model->prepareForForm();
?>

<div class="content-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'urlSlug')->textInput(['maxlength' => 64]) ?>

	<?= $form->field($model, 'title')->textInput(['maxlength' => 64]) ?>

	<?= $form->field($model, 'bodyText')->textarea(['rows' => 6, 'id' => 'bodyText']) ?>

	<?= $form->field($model, 'extraHtml')->textarea(['rows' => 6]) ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php

$this->registerJs(<<<JSCLIP

	$('#bodyText').redactor({
		lang: 'en',
		minHeight: 300,
		linebreaks: true,
		plugins: ['alignment'],
		buttons: ['format', 'bold', 'italic', 'deleted', 'lists', 'link', 'horizontalrule']
	});

JSCLIP
	, $this::POS_READY, 'redactor-init');
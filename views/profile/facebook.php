<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Institution */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::t('app', 'Facebook');
?>
	<div class="institution-update">

		<h1><?= Html::encode($this->title) ?></h1>
		<div class="institution-form">
			<?php $form = ActiveForm::begin(['id' => 'facebook']); ?>
				<?php if(isset($message)): ?>
					<h3><?= $message; ?></h3><br>
					<input type="hidden" value="1" name="deauthorize">
					<div class="form-group">
						<?= Html::submitButton(Yii::t('app', 'De-authorize'), ['class' => 'btn btn-success']) ?>
					</div>
				<?php else: ?>
					<?= $form->field($model, 'fbAppId')->textInput(['maxlength' => 30]) ?>
					<?= $form->field($model, 'fbAppSecret')->textInput(['maxlength' => 255]) ?>
					<?= $form->field($model, 'fbPageId')->textInput(['maxlength' => 30]) ?>

					<div class="form-group">
						<?= Html::submitButton(Yii::t('app', 'Authorize'), ['class' => 'btn btn-success']) ?>
					</div>
				<?php endif; ?>
			<?php ActiveForm::end(); ?>
		</div>

	</div>

<?php
$this->registerJs(<<<JSCLIP
  $(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'facebook-init');



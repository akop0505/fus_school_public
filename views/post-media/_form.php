<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Post;
/* @var $this yii\web\View */
/* @var $model app\models\PostMedia */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
?>

<div class="post-media-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'postId')->dropDownList(Post::dropDownFind(), ['prompt' => '']) ?>

	<?= $form->field($model, 'filename')->textInput(['maxlength' => 64]) ?>

	<?= $form->field($model, 'sort')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Channel;

/* @var $this yii\web\View */
/* @var $model app\models\HomepageFeaturedPost */
/* @var $form yii\widgets\ActiveForm */

$model->prepareForForm();
?>

<div class="homepage-featured-post-form">

	<?php $form = ActiveForm::begin(['method' => 'get', 'action' => Url::to(['homepage-featured-post'])]); ?>

	<?= $form->field($model, 'channelId')->dropDownList(Channel::dropDownFind(['IN', 'id', $model->getChannelsForDropDown()]), ['prompt' => '']) ?>

	<div class="form-group">
		<?= Html::submitButton(Yii::t('app', 'Select'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>

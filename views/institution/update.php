<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Institution */

$this->title = Yii::t('app', 'Update') . ' ' . (string)$model;
if(Yii::$app->controller->action->id != 'school-admin-update')
{
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Schools'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = ['label' => (string)$model, 'url' => ['view', 'id' => $model->id]];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
}
?>
<div class="institution-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

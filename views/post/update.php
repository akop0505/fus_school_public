<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$model->title = Html::decode($model->title);
$this->title = Yii::t('app', 'Update') . ' ' . (string)$model;
if(Yii::$app->controller->action->id != 'edit-post')
{
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = ['label' => (string)$model, 'url' => ['view', 'id' => $model->id]];
	$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
}
?>
<div class="post-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model
	]) ?>

</div>

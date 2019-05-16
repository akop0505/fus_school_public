<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FileUpload */

$this->title = Yii::t('app', 'Update') . ' ' . (string)$model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'File Uploads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="file-upload-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

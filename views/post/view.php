<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$model->title = Html::decode($model->title);
$this->title = (string)$model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
				'method' => 'post',
			],
		]) ?>
	</p>

	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'title:ntext',
			//'postText:ntext',
			'hasHeaderPhoto:boolean',
			'hasThumbPhoto:boolean',
			'views',
			'isActive:boolean',
			'isApproved:boolean',
			'createdAt:datetime',
			'createdBy',
			'updatedAt:datetime',
			'updatedBy',
			'dateToBePublished:datetime',
		],
	]) ?>

</div>

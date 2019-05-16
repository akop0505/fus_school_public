<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = (string)$model;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

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
			'username',
			//'authKey',
			//'passwordHash',
			//'passwordResetToken',
			'email:email',
			'emailVerified:boolean',
			'status',
			'createdAt:datetime',
			'updatedAt:datetime',
			'lastLogin:datetime',
			'firstName',
			'lastName',
			'hasPhoto:boolean',
			[
				'label' => Yii::t('app', 'Gender'),
				'value' => Yii::t('app', $model->isMale == 1 ? 'Male' : 'Female'),
			],
			'dateOfBirth:date',
			'mobilePhone',
			'timeZone',
			'institution',
			'about:ntext',
		],
	]) ?>

</div>

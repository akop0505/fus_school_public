<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FeaturedChannel */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Featured Channels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="featured-channel-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>

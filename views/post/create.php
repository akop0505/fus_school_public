<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var array $tags */
/* @var array $dataTagsUsed */

$this->title = Yii::t('app', 'Create');
if(Yii::$app->controller->action->id != 'publish-post')
{
	$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
}
?>
<div class="post-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form', [
		'model' => $model
	]) ?>

</div>

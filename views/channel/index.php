<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Channels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php Pjax::begin();
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],

			'id',
			'name',
			[
				'attribute' => 'hasPhoto',
				'format' => 'boolean',
				'filter' => $searchModel::dropdownYesNo()
			],
			[
				'attribute' => 'hasPortraitPhoto',
				'format' => 'boolean',
				'filter' => $searchModel::dropdownYesNo()
			],
			[
				'attribute' => 'isActive',
				'format' => 'boolean',
				'filter' => $searchModel::dropdownYesNo()
			],
			[
				'attribute' => 'isSystem',
				'format' => 'boolean',
				'filter' => $searchModel::dropdownYesNo()
			],
			// 'createdAt',
			// 'createdById',
			// 'updatedAt',
			// 'updatedById',

			['class' => 'yii\grid\ActionColumn'],
		],
	]);
	Pjax::end(); ?>

</div>

<?php

use app\models\FeaturedChannel;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\FeaturedChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Featured Channels');
$this->params['breadcrumbs'][] = $this->title;
$sortMax = FeaturedChannel::find()->orderBy('sort DESC')->one();
?>
<div class="featured-channel-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php Pjax::begin();
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'export' => false,
		'hover' => true,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],
			[
				'attribute' => 'channelId',
				'label' => 'Channel',
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions'=>[
					'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
							'url' => Url::toRoute(['auto-complete/channel', 'cond' => false, 'isSystem' => false]),
							'dataType' => 'json',
							'data' => new JsExpression('function(params) { return {term:params.term}; }'),
						],
					],
				],
				'filterInputOptions' => ['placeholder' => 'Select'],
				'content' => function ($item) {
					return Html::encode($item->channel->name);
				}
			],
			'sort',
			'numPost',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete} {update} {sortUp} {sortDown}',
				'buttons' => [
					'sortUp' => function ($url, $model, $key) {
						if($model->sort == 1) return false;
						return Html::a('<span class="fa fa-arrow-up"></span>', Yii::$app->urlManager->createUrl(['/featured-channel/sort-up', 'channelId' => $model->channelId, 'sort' => $model->sort, 'up' => 1]), ['title' => Yii::t('app', 'Sort up'), 'class' => 'option', 'data-pjax' => 0]);
					},
					'sortDown' => function ($url, $model, $key) use ($sortMax) {
						if($model->sort == $sortMax->sort) return false;
						return Html::a('<span class="fa fa-arrow-down"></span>', Yii::$app->urlManager->createUrl(['/featured-channel/sort-up', 'channelId' => $model->channelId, 'sort' => $model->sort, 'up' => 0]), ['title' => Yii::t('app', 'Sort down'), 'class' => 'option', 'data-pjax' => 0]);
					},
				]
			],
		],
	]);
	Pjax::end(); ?>

</div>

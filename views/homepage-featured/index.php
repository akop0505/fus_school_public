<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\HomepageFeaturedPostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Homepage Featured Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="homepage-featured-post-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a(Yii::t('app', 'Featured Channels'), ['homepage-channel'], ['class' => 'btn btn-success']) ?>
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
							'url' => Url::toRoute(['auto-complete/channel', 'cond' => true, 'isSystem' => true]),
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
			[
				'attribute' => 'postId',
				'label' => 'Post',
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions'=>[
					'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
							'url' => Url::toRoute(['auto-complete/post']),
							'dataType' => 'json',
							'data' => new JsExpression('function(params) { return {term:params.term}; }'),
						],
					],
				],
				'filterInputOptions' => ['placeholder' => 'Select'],
				'content' => function ($item) {
					return Html::encode($item->post->title);
				}
			],
			'sort',
		],
	]);
	Pjax::end(); ?>

</div>

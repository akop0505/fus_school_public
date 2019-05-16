<?php

use app\models\User;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\models\Post;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PostRepostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$canApprovePost = Yii::$app->user->can('ApprovePost');
$canApproveVideo = Yii::$app->user->can('ApproveVideo');

$this->title = Yii::t('app', 'Post Reposts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-repost-index">

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
				'attribute' => 'postTitle',
				'label' => 'Title',
				'content' => function ($item) {
					return Html::encode($item->post->title);
				}
			],
			[
				'attribute' => 'institutionId',
				'label' => 'Institution',
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions'=>[
					'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
							'url' => Url::toRoute(['auto-complete/institution']),
							'dataType' => 'json',
							'data' => new JsExpression('function(params) { return {term:params.term}; }'),
						],
					],
				],
				'filterInputOptions' => ['placeholder' => 'Select'],
				'content' => function ($item) {
					return Html::encode($item->institution);
				}
			],
			[
				'attribute' => 'isApproved',
				'format' => 'boolean',
				'filter' => $searchModel->dropdownYesNo()
			],
			//'createdAt',
			[
				'attribute' => 'createdById',
				'label' => 'Created By',
				'filter' => User::dropDownFind(),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions'=>[
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select'],
				'content' => function ($item) {
					return Html::encode($item->createdBy);
				}
			],

			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{delete} {approve} {refuse}',
				'buttons' => [
					'delete' => function ($url, $model, $key) {
						return Html::a('<span class="glyphicon glyphicon-trash"></span>', Yii::$app->urlManager->createUrl(['/post-repost/delete-post-repost', 'institutionId' => $model->institutionId, 'postId' => $model->postId]), ['title' => Yii::t('app', 'Delete'), 'data-pjax' => '0']);
					},
					'approve' => function ($url, $item, $key) use ($canApprovePost, $canApproveVideo) {
						$post = Post::findOne($item->postId);
						if($item->isApproved || ($post->video && !$canApproveVideo) || (!$post->video && !$canApprovePost)) return false;
						return Html::a('<span class="fa fa-thumbs-o-up"></span>', Yii::$app->urlManager->createUrl(['/post-repost/toggle-repost-approve', 'postId' => $item->postId, 'institutionId' => $item->institutionId, 'approve' => 1]), ['title' => Yii::t('app', 'Approve'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'refuse' => function ($url, $item, $key) use ($canApprovePost, $canApproveVideo) {
						$post = Post::findOne($item->postId);
						if(!$item->isApproved || ($post->video && !$canApproveVideo) || (!$post->video && !$canApprovePost)) return false;
						return Html::a('<span class="fa fa-thumbs-down"></span>', Yii::$app->urlManager->createUrl(['/post-repost/toggle-repost-approve', 'postId' => $item->postId, 'institutionId' => $item->institutionId, 'approve' => 0]), ['title' => Yii::t('app', 'Refuse'), 'class' => 'option', 'data-pjax' => '0']);
					},
				]
			],
		],
	]);
	Pjax::end(); ?>

</div>

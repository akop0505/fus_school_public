<?php

use app\models\User;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
$isAdmin = Yii::$app->user->can('Admin');
?>
<div class="post-index">

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
			'id',
			'title',
			// 'postText:ntext',
			// 'hasHeaderPhoto',
			// 'hasThumbPhoto',
			'views',
			[
				'attribute' => 'isActive',
				'format' => 'boolean',
				'filter' => $searchModel->dropdownYesNo()
			],
			[
				'attribute' => 'isApproved',
				'format' => 'boolean',
				'filter' => $searchModel->dropdownYesNo()
			],
			[
				'attribute' => 'isNational',
				'format' => 'boolean',
				'filter' => $searchModel->dropdownYesNo()
			],
			[
				'attribute' => 'hasVideo',
				'filter' => $searchModel->dropdownYesNo(),
				'content' => function ($item) {
					return $item->video ? 'Yes' : 'No';
				}
			],
			// 'createdAt',
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
				'attribute' => 'approvedById',
				'label' => 'Approved By',
				'filter' => User::dropDownFind(),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions'=>[
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select'],
				'content' => function ($item) {
					return Html::encode($item->approvedBy);
				}
			],
			/*array(
				'name'=>'fokus_id',
				'value'=>'GxHtml::valueEx($data->fokus)',
				'filter'=>GxHtml::listDataEx(EnumFokusPodrucje::model()->findAllAttributes(null, true)),
			),*/
			// 'updatedAt',
			//'updatedById',

			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {update} {deletePost} {video} {promote} {demote} {approve} {refuse} {makeNational} {removeFromNational} {gallery}',
				'buttons' => [
					'video' => function ($url, $model, $key) {
						if($model->video) return false;
						return Html::a('<span class="fa fa-upload"></span>', Yii::$app->urlManager->createUrl(['/post/upload-video', 'id' => $model->id]), ['title' => Yii::t('app', 'Upload Video'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'promote' => function ($url, $model, $key) {
						if($model->isActive || !$model->isApproved) return false;
						return Html::a('<span class="fa fa-level-up"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-post', 'id' => $model->id, 'promote' => 1]), ['title' => Yii::t('app', 'Publish'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'deletePost' => function ($url, $model, $key) use ($isAdmin) {
						if(!$isAdmin) return false;
						return Html::a('<span class="fa fa-trash-o"></span>', Yii::$app->urlManager->createUrl(['/post/delete-post', 'id' => $model->id]), [
							'title' => Yii::t('app', 'Delete'),
							'class' => 'option',
							'data' => [
								'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
								'method' => 'post',
							],
							'data-pjax' => '0'
						]);
					},
					'demote' => function ($url, $model, $key) {
						if(!$model->isActive) return false;
						return Html::a('<span class="fa fa-level-down"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-post', 'id' => $model->id, 'promote' => 0]), ['title' => Yii::t('app', 'UnPublish'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'approve' => function ($url, $model, $key) {
						if($model->isApproved) return false;
						return Html::a('<span class="fa fa-thumbs-o-up"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-post-approve', 'id' => $model->id, 'approve' => 1]), ['title' => Yii::t('app', 'Approve'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'refuse' => function ($url, $model, $key) {
						if(!$model->isApproved) return false;
						return Html::a('<span class="fa fa-thumbs-down"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-post-approve', 'id' => $model->id, 'approve' => 0]), ['title' => Yii::t('app', 'Refuse'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'makeNational' => function ($url, $model, $key) {
						if($model->isNational) return false;
						return Html::a('<span class="fa fa-arrow-circle-o-up"></span>', Yii::$app->urlManager->createUrl(['/post/toggle-post-national', 'id' => $model->id, 'national' => 1]), ['title' => Yii::t('app', 'Make National'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'removeFromNational' => function ($url, $model, $key) {
						if(!$model->isNational) return false;
						return Html::a('<span class="fa fa-arrow-circle-o-down"></span>', Yii::$app->urlManager->createUrl(['/post/toggle-post-national', 'id' => $model->id, 'national' => 0]), ['title' => Yii::t('app', 'Remove From National'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'gallery' => function ($url, $model, $key) {
						return Html::a('<span class="fa fa-camera"></span>', Yii::$app->urlManager->createUrl(['/post-media/view', 'postId' => $model->id]), ['title' => Yii::t('app', 'Gallery'), 'class' => 'option', 'data-pjax' => '0']);
					},
				]
			],
		],
	]);
	Pjax::end(); ?>

</div>

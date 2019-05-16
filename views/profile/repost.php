<?php

use app\models\User;
use app\models\Post;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PostRepostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user app\models\User */

$isSchoolAdmin = Yii::$app->user->can('SchoolAdmin');
$canApprovePost = Yii::$app->user->can('ApprovePost');
$canApproveVideo = Yii::$app->user->can('ApproveVideo');
$this->title = Yii::t('app', 'Reposts');
?>
<div class="post-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php Pjax::begin();
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'export' => false,
		'hover' => true,
		//'bootstrap' => false,
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
				'attribute' => 'isApproved',
				'format' => 'boolean',
				'filter' => $searchModel->dropdownYesNo()
			],
			[
				'attribute' => 'hasVideo',
				'filter' => $searchModel->dropdownYesNo(),
				'content' => function ($item) {
					return $item->post->video ? 'Yes' : 'No';
				}
			],
			// 'createdAt',
			[
				'attribute' => 'createdById',
				'label' => 'Reposted By',
				'filter' => User::dropDownFind(['institutionId' => $user->institutionId]),
				'content' => function ($item) {
					return Html::encode($item->createdBy);
				}
			],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete} {approve} {refuse}',
				'buttons' => [
					'view' => function ($url, $item, $key) {
						return Html::a('<span class="fa fa-eye"></span>', Yii::$app->urlManager->createUrl(['/site/article', 'id' => $item->postId]), ['title' => Yii::t('app', 'View'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'delete' => function ($url, $item, $key) use ($canApprovePost, $canApproveVideo){
						$post = Post::findOne($item->postId);
						if(($post->video && !$canApproveVideo) || (!$post->video && !$canApprovePost) || ($post->video && !$canApproveVideo) || (!$post->video && !$canApprovePost)) return false;
						return Html::a('<span class="fa fa-trash"></span>', Yii::$app->urlManager->createUrl(['/profile/delete-post-repost', 'postId' => $item->postId, 'institutionId' => $item->institutionId]), ['title' => Yii::t('app', 'Delete'), 'data-pjax' => '0']);
					},
					'approve' => function ($url, $item, $key) use ($canApprovePost, $canApproveVideo) {
						$post = Post::findOne($item->postId);
						if($item->isApproved || ($post->video && !$canApproveVideo) || (!$post->video && !$canApprovePost)) return false;
						return Html::a('<span class="fa fa-thumbs-o-up"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-repost-approve', 'postId' => $item->postId, 'institutionId' => $item->institutionId, 'approve' => 1]), ['title' => Yii::t('app', 'Approve'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'refuse' => function ($url, $item, $key) use ($canApprovePost, $canApproveVideo) {
						$post = Post::findOne($item->postId);
						if(!$item->isApproved || ($post->video && !$canApproveVideo) || (!$post->video && !$canApprovePost)) return false;
						return Html::a('<span class="fa fa-thumbs-down"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-repost-approve', 'postId' => $item->postId, 'institutionId' => $item->institutionId, 'approve' => 0]), ['title' => Yii::t('app', 'Refuse'), 'class' => 'option', 'data-pjax' => '0']);
					},
				]
			],
		],
	]);
	Pjax::end(); ?>

</div>

<?php
$this->registerJs(<<<JSCLIP
	$(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'repost-init');
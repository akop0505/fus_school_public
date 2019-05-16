<?php

use app\models\User;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user app\models\User */

$isSchoolAdmin = Yii::$app->user->can('SchoolAdmin');
$canApproveUser = Yii::$app->user->can('ApproveUser');
$this->title = Yii::t('app', 'Users');
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
			'username',
			'email:email',
			'firstName',
			'lastName',
			[
				'attribute' => 'status',
				'filter' => [User::STATUS_ACTIVE => User::STATUS_ACTIVE, User::STATUS_PENDING => User::STATUS_PENDING, User::STATUS_DELETED => User::STATUS_DELETED],
			],
			[
				'attribute' => 'schoolAdmin',
				'filter' => $user::dropdownYesNo(),
				'content' => function ($item) {
					return Yii::$app->authManager->checkAccess($item->id, 'SchoolAdmin') ? 'Yes' : 'No';
				}
			],
			[
				'attribute' => 'schoolAuthor',
				'filter' => $user::dropdownYesNo(),
				'content' => function ($item) use ($isSchoolAdmin) {
					if($isSchoolAdmin && !Yii::$app->authManager->checkAccess($item->id, 'SchoolAdmin'))
					{
						if(Yii::$app->authManager->checkAccess($item->id, 'SchoolAuthor'))
						{
							return Html::a('Yes', Yii::$app->urlManager->createUrl(['/user/school-author', 'id' => $item->id, 'promote' => 0]), ['title' => Yii::t('app', 'School Author'), 'class' => 'option', 'data-pjax' => '0']);
						}
						else
						{
							return Html::a('No', Yii::$app->urlManager->createUrl(['/user/school-author', 'id' => $item->id, 'promote' => 1]), ['title' => Yii::t('app', 'School Author'), 'class' => 'option', 'data-pjax' => '0']);
						}
					}
					else
					{
						return Yii::$app->authManager->checkAccess($item->id, 'SchoolAuthor') ? 'Yes' : 'No';
					}
				}
			],
			[
				'attribute' => 'approvePost',
				'header' => 'Approve Editorial',
				'filter' => $user::dropdownYesNo(),
				'content' => function ($item) use ($isSchoolAdmin) {
					if($isSchoolAdmin && !Yii::$app->authManager->checkAccess($item->id, 'SchoolAdmin'))
					{
						if(Yii::$app->authManager->checkAccess($item->id, 'ApprovePost'))
						{
							return Html::a('Yes', Yii::$app->urlManager->createUrl(['/user/approve-post', 'id' => $item->id, 'promote' => 0]), ['title' => Yii::t('app', 'Approve Post'), 'class' => 'option', 'data-pjax' => '0']);
						}
						else
						{
							return Html::a('No', Yii::$app->urlManager->createUrl(['/user/approve-post', 'id' => $item->id, 'promote' => 1]), ['title' => Yii::t('app', 'Approve Post'), 'class' => 'option', 'data-pjax' => '0']);
						}
					}
					else
					{
						return Yii::$app->authManager->checkAccess($item->id, 'ApprovePost') ? 'Yes' : 'No';
					}
				}
			],
			[
				'attribute' => 'approveVideo',
				'filter' => $user::dropdownYesNo(),
				'content' => function ($item) use ($isSchoolAdmin) {
					if($isSchoolAdmin && !Yii::$app->authManager->checkAccess($item->id, 'SchoolAdmin'))
					{
						if(Yii::$app->authManager->checkAccess($item->id, 'ApproveVideo'))
						{
							return Html::a('Yes', Yii::$app->urlManager->createUrl(['/user/approve-video', 'id' => $item->id, 'promote' => 0]), ['title' => Yii::t('app', 'Approve Video'), 'class' => 'option', 'data-pjax' => '0']);
						}
						else
						{
							return Html::a('No', Yii::$app->urlManager->createUrl(['/user/approve-video', 'id' => $item->id, 'promote' => 1]), ['title' => Yii::t('app', 'Approve Video'), 'class' => 'option', 'data-pjax' => '0']);
						}
					}
					else
					{
						return Yii::$app->authManager->checkAccess($item->id, 'ApproveVideo') ? 'Yes' : 'No';
					}
				}
			],
			[
				'attribute' => 'approveUser',
				'filter' => $user::dropdownYesNo(),
				'content' => function ($item) use ($isSchoolAdmin) {
					if($isSchoolAdmin && !Yii::$app->authManager->checkAccess($item->id, 'SchoolAdmin'))
					{
						if(Yii::$app->authManager->checkAccess($item->id, 'ApproveUser'))
						{
							return Html::a('Yes', Yii::$app->urlManager->createUrl(['/user/approve-user', 'id' => $item->id, 'promote' => 0]), ['title' => Yii::t('app', 'Approve User'), 'class' => 'option', 'data-pjax' => '0']);
						}
						else
						{
							return Html::a('No', Yii::$app->urlManager->createUrl(['/user/approve-user', 'id' => $item->id, 'promote' => 1]), ['title' => Yii::t('app', 'Approve User'), 'class' => 'option', 'data-pjax' => '0']);
						}
					}
					else
					{
						return Yii::$app->authManager->checkAccess($item->id, 'ApproveUser') ? 'Yes' : 'No';
					}
				}
			],

			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{activate} {deactivate} {promote} {demote} {archive} {remove}',
				'buttons' => [
					'activate' => function ($url, $model, $key) use ($canApproveUser) {
						if($model->status == User::STATUS_ACTIVE || !$canApproveUser) return false;
						return Html::a('<span class="fa fa-check"></span>', Yii::$app->urlManager->createUrl(['/user/toggle-status', 'id' => $model->id, 'promote' => 1]), ['title' => Yii::t('app', 'Activate'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'deactivate' => function ($url, $model, $key) use ($canApproveUser) {
						if($model->status != User::STATUS_ACTIVE || !$canApproveUser) return false;
						return Html::a('<span class="fa fa-ban"></span>', Yii::$app->urlManager->createUrl(['/user/toggle-status', 'id' => $model->id, 'promote' => 0]), ['title' => Yii::t('app', 'Deactivate'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'promote' => function ($url, $model, $key) use ($isSchoolAdmin) {
						if(!$isSchoolAdmin || Yii::$app->authManager->checkAccess($model->id, 'SchoolAdmin')) return false;
						return Html::a('<span class="fa fa-level-up"></span>', Yii::$app->urlManager->createUrl(['/user/school-admin', 'id' => $model->id, 'promote' => 1]), ['title' => Yii::t('app', 'School Admin'), 'class' => 'option', 'data-pjax' => '0']);
					},
					'demote' => function ($url, $model, $key) use ($isSchoolAdmin) {
						if(!$isSchoolAdmin || !Yii::$app->authManager->checkAccess($model->id, 'SchoolAdmin')) return false;
						return Html::a('<span class="fa fa-level-down"></span>', Yii::$app->urlManager->createUrl(['/user/school-admin', 'id' => $model->id, 'promote' => 0]), ['title' => Yii::t('app', 'School Admin'), 'class' => 'option', 'data-pjax' => '0']);
					},
                    'remove' => function ($url, $model, $key) use ($isSchoolAdmin) {
                        if(!$isSchoolAdmin) return false;
                        return Html::a('<span class="fa fa-trash-o"></span>', Yii::$app->urlManager->createUrl(['/user/delete', 'id' => $model->id]), ['title' => Yii::t('app', 'Remove'), 'class' => 'option', 'data-pjax' => '0']);
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
	, $this::POS_READY, 'users-init');

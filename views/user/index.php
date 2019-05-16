<?php

use app\models\Institution;
use app\models\User;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

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
			'username',
			//'authKey',
			//'passwordHash',
			//'passwordResetToken',
			'email:email',
			// 'emailVerified:email',
			[
				'attribute' => 'status',
				'filter' => [User::STATUS_ACTIVE => User::STATUS_ACTIVE, User::STATUS_PENDING => User::STATUS_PENDING, User::STATUS_DELETED => User::STATUS_DELETED],
			],
			// 'createdAt',
			// 'updatedAt',
			// 'lastLogin',
			'firstName',
			'lastName',
			[
				'attribute' => 'institution',
				'filter' => Institution::dropDownFind(),
				'filterType' => GridView::FILTER_SELECT2,
				'filterWidgetOptions'=>[
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'Select']
			],
			[
				'attribute' => 'schoolAdmin',
				'filter' => Institution::dropdownYesNo(),
				'content' => function ($item) {
					return Yii::$app->authManager->checkAccess($item->id, 'SchoolAdmin') ? 'Yes' : 'No';
				}
			],
			// 'isMale',
			// 'dateOfBirth',
			// 'mobilePhone',
			// 'timeZoneId:datetime',
			// 'about',

			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {update} {delete} {promote} {demote} {login}',
				'buttons' => [
					'promote' => function ($url, $model, $key) {
						if(Yii::$app->authManager->checkAccess($model->id, 'SchoolAdmin')) return false;
						return Html::a('<span class="fa fa-level-up"></span>', Yii::$app->urlManager->createUrl(['/user/school-admin', 'id' => $model->id, 'promote' => 1]), ['title' => Yii::t('app', 'Promote to institution admin'), 'class' => 'option']);
					},
					'demote' => function ($url, $model, $key) {
						if(!Yii::$app->authManager->checkAccess($model->id, 'SchoolAdmin')) return false;
						return Html::a('<span class="fa fa-level-down"></span>', Yii::$app->urlManager->createUrl(['/user/school-admin', 'id' => $model->id, 'promote' => 0]), ['title' => Yii::t('app', 'Demote to normal user'), 'class' => 'option']);
					},
					'login' => function ($url, $model, $key) {
						if($model->status != User::STATUS_ACTIVE) return false;
						return Html::a('<span class="fa fa-sign-in"></span>', Yii::$app->urlManager->createUrl(['/user/login-as-user', 'id' => $model->id]), ['title' => Yii::t('app', 'Login as user'), 'class' => 'option']);
					},
				]
			],
		],
	]);
	Pjax::end(); ?>

</div>

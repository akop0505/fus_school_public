<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\InstitutionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Schools');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="institution-index">

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
			'city',
			'address',
			'themeColor',
			// 'posts',
			// 'likes',
			// 'subscribers',
			// 'isActive',
			// 'createdAt',
			// 'createdById',
			// 'updatedAt',
			// 'updatedById',
			// 'about',

			['class' => 'yii\grid\ActionColumn'],
		],
	]);
	Pjax::end(); ?>

</div>

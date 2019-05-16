<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\FileUploadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'File Uploads');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-upload-index">

	<h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<p>
		<?= Html::a(Yii::t('app', 'Upload'), ['create'], ['class' => 'btn btn-success']) ?>
	</p>

	<?php Pjax::begin();
	echo GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			//['class' => 'yii\grid\SerialColumn'],
			'id',
			'fileName',
			//'createdAt',
			//'createdById',
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view} {delete}',
				'buttons' => [
					'view' => function ($url, $model, $key) {
						return Html::a('<span class="fa fa-eye"></span>', '/static/'. $model->fileName, ['title' => Yii::t('app', 'View'), 'class' => 'option', 'data-pjax' => '0', 'target' => '_blank']);
					},
				]
			],
		],
	]);
	Pjax::end(); ?>

</div>

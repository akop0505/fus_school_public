<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "yii\\grid\\GridView" : "yii\\widgets\\ListView" ?>;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

	<h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
<?php if(!empty($generator->searchModelClass)): ?>
<?= "\t<?php " . ($generator->indexWidgetType === 'grid' ? "// " : "") ?>echo $this->render('_search', ['model' => $searchModel]); ?>
<?php endif; ?>

	<p>
		<?php
			$modelClassTmp = Inflector::camel2words(StringHelper::basename($generator->modelClass));
			if($generator->enableI18N):
		?>
<?= "<?= " ?>Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
<?php else: ?>
<?= "<?= " ?>Html::a('Create <?= $modelClassTmp ?>', ['create'], ['class' => 'btn btn-success']) ?>
<?php endif; ?>
	</p>

<?php if ($generator->indexWidgetType === 'grid'): ?>
	<?= "<?php Pjax::begin();
	echo " ?>GridView::widget([
		'dataProvider' => $dataProvider,
		<?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n\t\t'columns' => [\n" : "'columns' => [\n"; ?>
			//['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "\t\t\t'" . $name . "',\n";
        } else {
            echo "\t\t\t// '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "\t\t\t'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "\t\t\t// '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

			['class' => 'yii\grid\ActionColumn'],
		],
	]);
	Pjax::end(); ?>
<?php else: ?>
	<?= "<?= " ?>ListView::widget([
		'dataProvider' => $dataProvider,
		'itemOptions' => ['class' => 'item'],
		'itemView' => function ($model, $key, $index, $widget) {
			return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
		},
	]) ?>
<?php endif; ?>

</div>

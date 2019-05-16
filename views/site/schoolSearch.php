<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\LoginForm */
/* @var array $states */

$this->title = 'Find a school';

$urlCity =  Url::to(['auto-complete/city']);
?>
<!-- start:header -->
<header id="header">
	<!-- start:cover -->
	<div class="cover">
		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->
	</div>
	<!-- end:cover -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">

	<!-- start:post -->
	<article class="post">

		<!-- start:cnt -->
		<div class="cnt clr">
			<section class="section user-form">
				<h2><?= Html::encode($this->title) ?></h2>

				<?php $form = ActiveForm::begin([
					'id' => 'school-search',
					'method' => 'get',
					'options' => ['class' => 'autoFocus'],
					'fieldConfig' => [
						'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
						'labelOptions' => ['class' => 'col-lg-1 control-label'],
					],
					'action' => Url::to(['site/search-institution'])
					]);
				?>
				<?= $form->field($model, 'schoolName') ?>

				<?= $form->field($model, 'stateId')->dropDownList(\app\models\State::dropDownFind(), ['prompt' => '']) ?>

				<?php
					echo $form->field($model, 'cityId')->widget(Select2::classname(), [
						'options' => ['placeholder' => 'Search for city ...', 'class' => 'skipSelect2'],
						'pluginOptions' => [
							'allowClear' => true,
							'minimumInputLength' => 3,
							'ajax' => [
								'url' => $urlCity,
								'dataType' => 'json',
								'data' => new JsExpression('function(params){ return {term:params.term}; }')
							],
							'templateSelection' => new JsExpression('function (city) { return city.name !== undefined ? city.name : city.text; }'),
						],
						'pluginEvents' => [
							"select2:select" => 'function (e) {
												var tmpData = e.params.data;
												$("#schoolsearchform-stateid").val(tmpData.cityStateId).trigger("change");
												$("#schoolsearchform-zip").val(tmpData.cityZip).trigger("change");
											}',
						]
					]);
				?>

				<?= $form->field($model, 'zip') ?>

				<div class="row">
					<button type="submit" class="button red size-60">Search</button>
				</div>

				<?php ActiveForm::end(); ?>
			</section>
		</div>
		<!-- end:cnt -->

	</article>
	<!-- end:post -->

</main>
<!-- end:main -->
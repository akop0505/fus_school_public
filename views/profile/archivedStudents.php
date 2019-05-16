<?php

use app\models\StudentsArchived;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var int $sortMax */
/* @var $user app\models\User */
/* @var $studentsArchived app\models\StudentsArchived */

$isSchoolAdmin = Yii::$app->user->can('SchoolAdmin');
$this->title = Yii::t('app', 'Archived Students');
?>

	<div class="post-featured-form">

	<h1><?= Html::encode($this->title) ?></h1>

	<?php $form = ActiveForm::begin(['id' => 'StudentsArchived', 'method' => 'post', 'action' => Url::to(['profile/save-students-archived', 'institutionId' => $user->institutionId,])]); ?>

	<div class="form-group"><table class="table kv-grid-table table-featured-post">
			<?php
			for($n = 1; $n <= $sortMax; ++$n)
			{
				$studentsArchived = StudentsArchived::findOne(['institutionId' => $user->institutionId, 'sort' => $n]);

				echo '<tr><td style="width: 50px;">'. $n .': </td><td>';
				echo Select2::widget([
					'name' => "archivedStudents[$n]",
					'value' => $studentsArchived ? $studentsArchived->userId : '',
					'initValueText' => $studentsArchived ? $studentsArchived->user->firstName . ' ' . $studentsArchived->user->lastName : '',
					'options' => [
						'placeholder' => 'Select',
						'id' => 'archivedStudents'. $n
					],
					'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 0,
						'ajax' => [
							'url' => Url::toRoute(['auto-complete/student']),
							'dataType' => 'json',
							'data' => new JsExpression('function(params) { return {term:params.term}; }'),
						]
					]
				]);
				echo '</td><td style="width: 130px;">';
				if($n != 1)
				{
					echo Html::a('<span class="fa fa-arrow-up"></span>', '#', ['rel' => $n, 'title' => Yii::t('app', 'Sort up'), 'class' => 'option sortUp']);
					echo Html::a('<span class="fa fa-arrow-circle-up"></span>', '#', ['rel' => $n, 'title' => Yii::t('app', 'Move all up'), 'class' => 'option moveUp']);
				}
				if($n != $sortMax)
				{
					echo Html::a('<span class="fa fa-arrow-down"></span>', '#', ['rel' => $n, 'title' => Yii::t('app', 'Sort down'), 'class' => 'option sortDown']);
					echo Html::a('<span class="fa fa-arrow-circle-down"></span>', '#', ['rel' => $n, 'title' => Yii::t('app', 'Move all down'), 'class' => 'option moveDown']);
				}
				echo '</td></tr>';
			}
			echo "</table></div>";

			echo '<div class="form-group">';
			echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']);
			echo '</div>';

			ActiveForm::end();
			?>
	</div>

<?php

$this->registerJs(
/** @lang JavaScript */
	<<<JSCLIP

	$('.sortUp').on('click', function (e){
		var first = parseInt($(this).attr('rel')), second = first - 1;
		var valueFirst = $("#archivedStudents"+ first).select2('data');
		var valueSecond = $("#archivedStudents"+ second).select2('data');
		$("#archivedStudents"+ first)
			.empty()
			.append('<option value="'+ valueSecond[0].id +'">'+ valueSecond[0].text +'</option>')
			.val(valueSecond[0].id)
			.trigger('change');
		$("#archivedStudents"+ second)
			.empty()
			.append('<option value="'+ valueFirst[0].id +'">'+ valueFirst[0].text +'</option>')
			.val(valueFirst[0].id)
			.trigger('change');
		return false;
	});

	$('.sortDown').on('click', function (e){
		var first = parseInt($(this).attr('rel')), second = first + 1;
		var valueFirst = $("#archivedStudents"+ first).select2('data');
		var valueSecond = $("#archivedStudents"+ second).select2('data');
		$("#archivedStudents"+ first)
			.empty()
			.append('<option value="'+ valueSecond[0].id +'">'+ valueSecond[0].text +'</option>')
			.val(valueSecond[0].id)
			.trigger('change');
		$("#archivedStudents"+ second)
			.empty()
			.append('<option value="'+ valueFirst[0].id +'">'+ valueFirst[0].text +'</option>')
			.val(valueFirst[0].id)
			.trigger('change');
		return false;
	});

	$('.moveUp').on('click', function (e){
		var first = parseInt($(this).attr('rel')), second = first - 1;
		var valueFirst = $("#archivedStudents"+ first).select2('data');

		for(i = 1; i < first; i++){
   			var id = i+1;
   			var value = $("#archivedStudents"+ id).select2('data');
   			$("#archivedStudents"+ i)
				.empty()
				.append('<option value="'+ value[0].id +'">'+ value[0].text +'</option>')
				.val(value[0].id)
				.trigger('change');
		}

		$("#archivedStudents"+ second)
			.empty()
			.append('<option value="'+ valueFirst[0].id +'">'+ valueFirst[0].text +'</option>')
			.val(valueFirst[0].id)
			.trigger('change');

		$("#archivedStudents"+ first)
			.empty()
			.append('<option value=""> </option>')
			.val('')
			.trigger('change');

		return false;
	});

	$('.moveDown').on('click', function (e){
		var first = parseInt($(this).attr('rel')), second = first + 1;
		var valueFirst = $("#archivedStudents"+ first).select2('data');

		for(i = $sortMax; i > first; i--){
   			var id = i-1;
   			var value = $("#archivedStudents"+ id).select2('data');
   			$("#archivedStudents"+ i)
				.empty()
				.append('<option value="'+ value[0].id +'">'+ value[0].text +'</option>')
				.val(value[0].id)
				.trigger('change');
		}

		$("#archivedStudents"+ second)
			.empty()
			.append('<option value="'+ valueFirst[0].id +'">'+ valueFirst[0].text +'</option>')
			.val(valueFirst[0].id)
			.trigger('change');

		$("#archivedStudents"+ first)
			.empty()
			.append('<option value=""> </option>')
			.val('')
			.trigger('change');

		return false;
	});
	
   	$(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'students-init');
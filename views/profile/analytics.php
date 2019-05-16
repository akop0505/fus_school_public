<?php

/* @var array $mostWatchedSchools */
/* @var array $mostActiveUser */
/* @var array $mostWatchedPost */
/* @var array $mostWatchedPostInSchool */
/* @var array $mostLikedSchools */
/* @var array $mostMostSubscribedUsers */
/* @var int $selectedPeriod */
/* @var string $schoolName */
/* @var string $label */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Analytics');
?>
<div class="form-group">
	<?php $form = ActiveForm::begin(['id' => 'analytics']); ?>
	<label>Period:</label>
		<?=
			Html::dropDownList('period', $selectedPeriod,
			[1 => 'Last 7 days', 2 => 'Last 30 days', 3 => 'Last 6 months', 4 => 'Last 12 months'],
			['onchange' => 'this.form.submit()']);
		?>
	<?php ActiveForm::end(); ?>
</div>

<div class="analytics clr">
	<div class="col">
		<div id="chartMostWatchedPostInSchool" class="chart"></div>
	</div>
	<div class="col">
		<div id="chartMostActiveUser" class="chart"></div>
	</div>
	<div class="col">
		<div id="chartMostWatchedSchools" class="chart"></div>
	</div>
	<div class="col">
		<div id="chartMostLikedSchools" class="chart"></div>
	</div>
	<div class="col">
		<div id="chartMostWatchedPost" class="chart"></div>
	</div>
	<div class="col">
		<div id="chartMostSubscribedUsers" class="chart"></div>
	</div>
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load("current", {packages:["corechart"]});
	google.charts.setOnLoadCallback(drawChart);
	function drawChart()
	{
		var i = 1;

		// Most Watched Schools chart
		var dataMostWatchedSchools = new google.visualization.DataTable();
		dataMostWatchedSchools.addColumn('string', 'School');
		dataMostWatchedSchools.addColumn('number', 'Number of views');
		dataMostWatchedSchools.addColumn({type: 'string', role: 'style'});

		var mostWatchedSchools = <?php echo json_encode($mostWatchedSchools) ?>;
		var numRowsMostWatchedSchools = mostWatchedSchools.length;
		for (i = 1; i < numRowsMostWatchedSchools; i++){
			dataMostWatchedSchools.addRow(mostWatchedSchools[i]);
		}
		var optionsForMostWatchedSchools = {
			title: "Most watched schools",
			titleTextStyle: {fontSize: 13},
			bar: {groupWidth: "80%"},
			legend: {position: "none" }
		};
		var chartMostWatchedSchools = new google.visualization.BarChart(document.getElementById("chartMostWatchedSchools"));
		chartMostWatchedSchools.draw(dataMostWatchedSchools, optionsForMostWatchedSchools);


		// Most Watched Post chart
		var dataMostWatchedPost = new google.visualization.DataTable();
		dataMostWatchedPost.addColumn('string', 'Article');
		dataMostWatchedPost.addColumn('number', 'Number of views');
		dataMostWatchedPost.addColumn({type: 'string', role: 'style'});

		var mostWatchedPost = <?php echo json_encode($mostWatchedPost) ?>;
		var numRowsMostWatchedPost = mostWatchedPost.length;
		for (i = 1; i < numRowsMostWatchedPost; i++){
			dataMostWatchedPost.addRow(mostWatchedPost[i]);
		}
		var optionsForMostWatchedPost = {
			title: "Most watched posts on Fusfoo",
			titleTextStyle: {fontSize: 13},
			bar: {groupWidth: "80%"},
			legend: {position: "none" }
		};
		var chartMostWatchedPost = new google.visualization.BarChart(document.getElementById("chartMostWatchedPost"));
		chartMostWatchedPost.draw(dataMostWatchedPost, optionsForMostWatchedPost);


		// Most Active User chart
		var dataMostActiveUser = new google.visualization.DataTable();
		dataMostActiveUser.addColumn('string', 'Name');
		dataMostActiveUser.addColumn('number', 'Number of posts');
		dataMostActiveUser.addColumn({type: 'string', role: 'style'});

		var mostActiveUser = <?php echo json_encode($mostActiveUser) ?>;
		var numRowsMostActiveUser = mostActiveUser.length;
		for (i = 1; i < numRowsMostActiveUser; i++){
			dataMostActiveUser.addRow(mostActiveUser[i]);
		}
		var optionsMostActiveUser = {
			title: "Most active school members",
			titleTextStyle: {fontSize: 13},
			bar: {groupWidth: "80%"},
			legend: {position: "none" }
		};
		var chartMostActiveUser = new google.visualization.BarChart(document.getElementById("chartMostActiveUser"));
		chartMostActiveUser.draw(dataMostActiveUser, optionsMostActiveUser);


		// Most Watched Post in selected School chart
		var dataMostWatchedPostInSchool = new google.visualization.DataTable();
		dataMostWatchedPostInSchool.addColumn('string', 'School');
		dataMostWatchedPostInSchool.addColumn('number', 'Number of views');
		dataMostWatchedPostInSchool.addColumn({type: 'string', role: 'style'});

		var mostWatchedPostInSchool = <?php echo json_encode($mostWatchedPostInSchool) ?>;
		var numRowsMostWatchedPostInSchool = mostWatchedPostInSchool.length;
		for (i = 1; i < numRowsMostWatchedPostInSchool; i++){
			dataMostWatchedPostInSchool.addRow(mostWatchedPostInSchool[i]);
		}
		var optionsForMostWatchedPostInSchool = {
			title: "Most watched post in " + <?php echo json_encode($schoolName) ?>,
			titleTextStyle: {fontSize: 13},
			bar: {groupWidth: "80%"},
			legend: {position: "none" }
		};
		var chartMostWatchedPostInSchool = new google.visualization.BarChart(document.getElementById("chartMostWatchedPostInSchool"));
		chartMostWatchedPostInSchool.draw(dataMostWatchedPostInSchool, optionsForMostWatchedPostInSchool);


		// Most Liked Schools chart
		var dataMostLikedSchools = new google.visualization.DataTable();
		dataMostLikedSchools.addColumn('string', 'School');
		dataMostLikedSchools.addColumn('number', 'Number of likes');
		dataMostLikedSchools.addColumn({type: 'string', role: 'style'});

		var mostLikedSchools = <?php echo json_encode($mostLikedSchools) ?>;
		var numRowsMostLikedSchools = mostLikedSchools.length;
		for (i = 1; i < numRowsMostLikedSchools; i++){
			dataMostLikedSchools.addRow(mostLikedSchools[i]);
		}
		var optionsForMostLikedSchools = {
			title: "Most liked schools",
			titleTextStyle: {fontSize: 13},
			bar: {groupWidth: "80%"},
			legend: {position: "none" }
		};
		var chartMostLikedSchools = new google.visualization.BarChart(document.getElementById("chartMostLikedSchools"));
		chartMostLikedSchools.draw(dataMostLikedSchools, optionsForMostLikedSchools);


		// Most Subscribed Users chart
		var dataMostSubscribedUsers = new google.visualization.DataTable();
		dataMostSubscribedUsers.addColumn('string', 'School');
		dataMostSubscribedUsers.addColumn('number', 'Number of likes');
		dataMostSubscribedUsers.addColumn({type: 'string', role: 'style'});

		var mostSubscribedUsers = <?php echo json_encode($mostMostSubscribedUsers) ?>;
		var numRowsMostSubscribedUsers = mostSubscribedUsers.length;
		for (i = 1; i < numRowsMostSubscribedUsers; i++){
			dataMostSubscribedUsers.addRow(mostSubscribedUsers[i]);
		}
		var optionsForMostSubscribedUsers = {
			title: "Most subscribed users",
			titleTextStyle: {fontSize: 13},
			bar: {groupWidth: "80%"},
			legend: {position: "none" }
		};
		var chartMostSubscribedUsers = new google.visualization.BarChart(document.getElementById("chartMostSubscribedUsers"));
		chartMostSubscribedUsers.draw(dataMostSubscribedUsers, optionsForMostSubscribedUsers);
	}
</script>
<?php
$this->registerJs(<<<JSCLIP
	$(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'analytics-init');
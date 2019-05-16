<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var array $model */
/* @var app\models\Post $article */
/* @var string $title */
/* @var string $class */
/* @var int|null $institutionId */

if(isset($class) && $class) $classSection = $class;
else $classSection = 'videos';
if(!isset($institutionId)) $institutionId = 0;
?>
<section class="<?= $classSection; ?>">

	<!-- start:cnt -->
	<div class="cnt">

		<h2><?= $title; ?></h2>

		<!-- start:list -->
		<div class="list six clr">
			<?php foreach($model as $article): ?>
				<!-- start:video item -->
					<?= $this->render('_searchBox', [
							'data' => $article,
							'institutionId' => $institutionId
						]);
					?>
				<!-- end:video item -->
			<?php endforeach; ?>
		</div>
		<!-- end:list -->

	</div>
	<!-- end:cnt -->

</section>

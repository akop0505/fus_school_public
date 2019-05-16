<?php

use yii\helpers\Html;

/* @var app\models\Post $data */

?>

<!-- start:videos -->
<div class="videos">
	<!-- start:list -->
	<div class="list four clr">
		<?php foreach($data as $article): ?>
			<!-- start:video item -->
			<?php echo $this->render('_searchBox', ['data' => $article]); ?>
			<!-- end:video item -->
		<?php endforeach; ?>
	</div>
	<!-- end:list -->
</div>
<!-- end:videos -->
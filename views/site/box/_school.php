<?php

/* @var yii\web\View $this */
/* @var app\models\Institution $data */

?>
<!-- start:video item -->
<article class="video-item">
	<a href="<?= $data->getUrl(); ?>" class="thumbnail" style="background-image: url('<?= $data->getPicBaseUrl('logo') . $data->getPicName('logo', true); ?>');"></a>
	<h3>
		<a href="<?= $data->getUrl(); ?>"><?= $data->name; ?></a>
	</h3>
	<p>
		<?= $data->address . ', ' . $data->city->name; ?>
	</p>
</article>
<!-- end:video item -->
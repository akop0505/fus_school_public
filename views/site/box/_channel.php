<?php

/* @var yii\web\View $this */
/* @var app\models\Channel $data */

?>
<!-- start:video item -->
<article class="video-item">
	<a href="<?= $data->getUrl(); ?>" class="thumbnail" style="background-image: url('<?= $data->getPicBaseUrl('hasPhoto') . $data->getPicName('hasPhoto', true); ?>');"></a>
	<h3>
		<a href="<?= $data->getUrl(); ?>"><?= $data->name; ?></a>
	</h3>
	<p>
		<?= $data->description; ?>
	</p>
</article>
<!-- end:video item -->
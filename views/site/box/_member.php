<?php

/* @var yii\web\View $this */
/* @var app\models\User $data */

?>
<!-- start:video item -->
<article class="video-item">
	<a href="<?= $data->getUrl(); ?>" class="thumbnail" style="background-image: url('<?= $data->getPicBaseUrl('hasPhoto') . $data->getPicName('hasPhoto', true); ?>');"></a>
	<h3>
		<a href="<?= $data->getUrl(); ?>"><?= $data->firstName . ' ' . $data->lastName; ?></a>
	</h3>
	<?php if($data->institutionId): ?>
	<p>
		<?= $data->institution->name; ?>
	</p>
	<?php endif; ?>
</article>
<!-- end:video item -->
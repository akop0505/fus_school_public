<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var app\models\Post $data */
/* @var int $institutionId */
?>
<!-- start:video item -->
<article class="video-item">
	<a href="<?= $data->getUrl(); ?>">
		<span class="thumbnail" style="background-image: url('<?= $data->getPicBaseUrl('hasThumbPhoto') . $data->getPicName('hasThumbPhoto', true); ?>');">
            <?php if($data->video != ''): ?>
                <i class="icon play" style="background-image: url('/images/play.png');display: block; position: absolute;top: 15px;left: 15px;width: 20px;height: 20px;background-size: cover;"></i>
            <?php else: ?>
                <i class="icon pen" style="background-image: url('/images/pen.png');display: block;position: absolute;top: 15px;left: 15px;width: 20px;height: 20px;background-size: cover;"></i>
            <?php endif; ?>
		<?php if($data->isNational || ($institutionId && $data->createdBy->institutionId != $institutionId)):?>
			<span class="ribbon clr">
				<?php if($data->isNational):?>
					<i class="national" data-title="Made it to National"></i>
				<?php endif;  ?>
				<?php if($institutionId && $data->createdBy->institutionId != $institutionId):?>
					<i class="repost" data-title="Repost"></i>
				<?php endif; ?>
			</span>
		<?php endif;  ?>
		</span>
	</a>
	<p>
		<?= Html::encode($data->createdBy->getUserFullName()) . ', '; ?>
		<?= Html::encode($data->createdBy->institution->name); ?>
	</p>
	<h3>
		<a href="<?= $data->getUrl(); ?>"><?= Html::encode($data->title); ?></a>
	</h3>
</article>
<!-- end:video item -->
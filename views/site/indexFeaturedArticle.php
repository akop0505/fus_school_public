<?php

use yii\helpers\Html;

/* @var app\models\Post $article */
/* @var string $class */

?>

<!-- strt:article -->
<article class="<?= $class; ?>">
	<a href="<?= $article->getUrl(); ?>">
		<span class="thumbnail" style="background-image: url('<?= $article->getPicBaseUrl('hasThumbPhoto') . $article->getPicName('hasThumbPhoto', true); ?>');">
			<?php if($article->video != ''):?>
				<i class="icon play"></i>
			<?php else: ?>
				<i class="icon pen"></i>
			<?php endif; ?>
			<?php if($article->isNational):?>
			<span class="ribbon clr">
				<i class="national" data-title="Made it to National"></i>
			</span>
			<?php endif; ?>
		</span>
		<div class="bottom">
			<h3><?= Html::encode($article->title); ?></h3>
			<p>
				<?= Html::encode($article->createdBy->getUserFullName()) . ', '; ?>
				<?= Html::encode($article->createdBy->institution->name); ?>
			</p>
		</div>
	</a>
</article>
<!-- end:article -->

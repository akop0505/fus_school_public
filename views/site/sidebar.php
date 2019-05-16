<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var app\models\Channel $one */
/* @var app\models\Post $post */
/* @var array $channels */

?>
<aside class="sidebar">

	<?php if(isset($post) && $post->createdBy->institutionId == 1): ?>
		<div class="ad" style="display: block;">
			<?php if(mt_rand(0,1)): ?>
			<a href="http://www.acceptedtoschool.com/" target="_blank"><img src="/images/upload/ad/webad1.jpg"></a>
			<?php else: ?>
			<a href="http://www.ripleygrier.com/" target="_blank"><img src="/images/upload/ad/ripleygrier.jpg"></a>
			<?php endif; ?>
		</div>
	<?php else: ?>
	<div class="ad" style="display: none;">
		<img src="/images/temp/ad/adidas.jpg" alt="Adidas">
	</div>
	<?php endif; ?>

	<?php if(isset($channels[0])): ?>

	<header class="clr">
		<h4>Suggested channels</h4>
	</header>

	<!-- start:list -->
	<div class="list">
		<?php foreach($channels as $one): ?>
			<!-- start:article -->
			<article>
				<a href="<?= $one->getUrl(); ?>" class="thumbnail" style="background-image: url('<?= $one->getPicBaseUrl('hasPhoto') . $one->getPicName('hasPhoto', true); ?>');"></a>
				<h5>
					<a href="<?= $one->getUrl(); ?>"><?= Html::encode($one->name); ?></a>
				</h5>
				<p>
					<?= $one->description; ?>
				</p>
				<span><?= $one->numPosts . ' Posts'; ?></span>
			</article>
			<!-- end:article -->
		<?php endforeach; ?>
	</div>
	<!-- end:list -->

	<?php endif; ?>
</aside>

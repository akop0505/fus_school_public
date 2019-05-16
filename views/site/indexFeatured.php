<?php

use yii\helpers\Html;
use \app\models\Channel;

/* @var array $data */
/* @var int $channelId */
/* @var int $numPost */
/* @var boolean $withHeader */
/* @var string $listClass */
/* @var string $titleMain */
/* @var string $url */

$channel = Channel::findOne($channelId);
$counter = 1;
?>

<!-- start:featured channel -->
<section class="featured-channel">

	<!-- start:cnt -->
	<div class="cnt">

		<?php if($withHeader): ?>
			<!-- start:with header -->
			<div class="with-header">

				<!-- start:header -->
				<header class="header channel">
					<a href="<?= $channel->getUrl(); ?>" class="tbl" <?php if($channel->hasPhoto): ?> style="background-image: url('<?= $channel->getPicBaseUrl('hasPortraitPhoto') . $channel->getPicName('hasPortraitPhoto', true); ?>');"<?php endif; ?>>
						<h2 class="tcell vertical-middle"><?= Html::encode($titleMain); ?></h2>
					</a>
					<a href="<?= $channel->getUrl(); ?>" class="button red size-30">View Channel</a>
				</header>
				<!-- end:header -->

				<!-- start:list -->
				<div class="<?= $listClass; ?>">

					<?php foreach($data as $article): ?>
						<?php if(isset($numPost) && $numPost == 5 && $counter == 1): ?>
							<?= $this->render('indexFeaturedArticle', ['article' => $article, 'class' => 'article size-2x2']); ?>
						<?php else: ?>
							<?= $this->render('indexFeaturedArticle', ['article' => $article, 'class' => 'article size-1x1']); ?>
						<?php endif; ?>
					<?php $counter++; endforeach; ?>

				</div>
				<!-- end:list -->

			</div>
			<!-- end:with header -->
		<?php else: ?>

			<!-- start:header -->
			<header class="header center">
				<h2>
					<span><?= Html::encode($titleMain); ?></span>
				</h2>
			</header>
			<!-- end:header -->

			<!-- start:list -->
			<div class="<?= $listClass; ?>">

				<?php foreach($data as $article): ?>
					<?= $this->render('indexFeaturedArticle', ['article' => $article, 'class' => 'article size-1x1']); ?>
				<?php endforeach; ?>

			</div>
			<!-- end:list -->
		<?php endif; ?>

	</div>
	<!-- end:cnt -->

</section>
<!-- end:featured channel -->
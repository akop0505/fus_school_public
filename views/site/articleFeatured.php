<?php

use yii\helpers\Html;

/* @var app\models\Post $article */
/* @var array $data */
/* @var array $articleClass */
/* @var int|null $institutionId */
/* @var string $class */
/* @var string $latestPictureUrl */
/* @var string $title */
/* @var string $titleMain */
/* @var string $url */
/* @var string $latestUrl */
/* @var string $headerExtra */
/* @var boolean $addSection */

if(!isset($institutionId)) $institutionId = 0;

?>
<section class="<?= $class; ?>">

	<!-- start:cnt -->
	<div class="cnt clr">

		<!-- start:header -->
		<header class="transparent clr" <?= isset($headerExtra) ? $headerExtra : '' ?>>

			<h2><?= Html::encode($titleMain); ?></h2>

			<?php if($url): ?><a href="<?= $url; ?>"><?= Html::encode($title); ?></a><?php endif; ?>

		</header>
		<!-- end:header -->

		<!-- start:list -->
		<div class="list clr">

			<?php $counter = 0;
				if(isset($latestPictureUrl) && $latestPictureUrl):
			?>
				<!-- start:article -->
				<article class="<?= $articleClass[$counter] ?>">
					<a href="<?= $latestUrl; ?>"<?php if($latestUrl != '#') echo ' target="_blank"'; ?>>
						<span class="thumbnail school-latest-photo" style="background-image: url('<?= $latestPictureUrl; ?>');"></span>
					</a>
				</article>
				<!-- end:article -->

			<?php
				$counter++;
				endif;
				foreach($data as $article):
			?>
				<!-- start:article -->
				<article class="<?= $articleClass[$counter]?>">
					<a href="<?= $article->getUrl(); ?>">
						<span class="thumbnail" style="background-image: url('<?= $article->getPicBaseUrl('hasThumbPhoto') . $article->getPicName('hasThumbPhoto', true); ?>');">
							<?php if($article->video != ''):?>
							   <i class="icon play"></i>
							<?php else: ?>
							   <i class="icon pen"></i>
							<?php endif;  ?>
							<?php if($article->isNational || ($institutionId && $article->createdBy->institutionId != $institutionId)):?>
								<span class="ribbon clr">
									<?php if($article->isNational):?>
										<i class="national" data-title="Made it to National"></i>
									<?php endif;  ?>
									<?php if($institutionId && $article->createdBy->institutionId != $institutionId):?>
										<i class="repost" data-title="Repost"></i>
									<?php endif;  ?>
								</span>
							<?php endif;  ?>
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

			<?php $counter++; endforeach; ?>

			<?php if($addSection && 0): ?>

				<!-- strt:ad -->
				<div class="ad">
					<a href="">
						<img src="/images/temp/ad/01.jpg" alt="Ad">
					</a>
				</div>
				<!-- end:ad -->

				<!-- strt:ad -->
				<div class="ad">
					<a href="">
						<img src="/images/temp/ad/02.jpg" alt="Ad">
					</a>
				</div>
				<!-- end:ad -->

				<!-- strt:ad -->
				<div class="ad">
					<a href="">
						<img src="/images/temp/ad/03.jpg" alt="Ad">
					</a>
				</div>
				<!-- end:ad -->

				<!-- strt:ad -->
				<div class="ad">
					<a href="">
						<img src="/images/temp/ad/04.jpg" alt="Ad">
					</a>
				</div>
				<!-- end:ad -->

			<?php endif; ?>
		</div>
		<!-- end:list -->

	</div>
	<!-- end:cnt -->

</section>

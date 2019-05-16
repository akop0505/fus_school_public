<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var app\models\Content $model */
/* @var string $page */

$this->title .= ' - '. $model->title;

$urlTerms = Url::toRoute(['site/content', 'contentType' => 'terms']);
$urlContact = Url::toRoute(['site/content', 'contentType' => 'contact']);
$urlPrivacy = Url::toRoute(['site/content', 'contentType' => 'privacy-policy']);
$urlAbout = Url::toRoute(['site/content', 'contentType' => 'about']);
$urlHelp = Url::toRoute(['site/content', 'contentType' => 'help']);
$urlDMCA = Url::toRoute(['site/content', 'contentType' => 'dmca']);
?>
<!-- start:header -->
<header id="header">

	<!-- start:cover -->
	<div class="cover">

		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->

	</div>
	<!-- end:cover -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">

	<!-- start:general -->
	<section class="general">

		<!-- start:cnt -->
		<div class="cnt clr">

			<!-- start:column -->
			<div class="column">

				<header class="clr">

					<h2><?= Html::encode($model->title); ?></h2>

				</header>

				<!-- start:typogrpahy -->
				<div class="entry typography">
					<p><?= $model->bodyText; ?></p>
				</div>
				<!-- end:typography -->

			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<div class="sidebar">

				<ul>
					<li class="<?php if($page == 'about') echo 'current'; ?>">
						<a href="<?= $urlAbout; ?>">
							<i class="fa fa-fw fa-info-circle margin-5-right"></i>
							About Fusfoo
						</a>
					</li>
					<li class="<?php if($page == 'terms') echo 'current'; ?>">
						<a href="<?= $urlTerms; ?>">
							<i class="fa fa-fw fa-file-text-o margin-5-right"></i>
							Terms of Use
						</a>
					</li>
					<li class="<?php if($page == 'privacy-policy') echo 'current';?>">
						<a href="<?= $urlPrivacy; ?>">
							<i class="fa fa-fw fa-lock margin-5-right"></i>
							Privacy Policy
						</a>
					</li>
					<li class="<?php if($page == 'dmca') echo 'current';?>">
						<a href="<?= $urlDMCA; ?>">
							<i class="fa fa-fw fa-copyright margin-5-right"></i>
							DMCA Policy
						</a>
					</li>
					<li class="<?php if($page == 'contact') echo 'current';?>">
						<a href="<?= $urlContact; ?>">
							<i class="fa fa-fw fa-envelope margin-5-right"></i>
							Contact Us
						</a>
					</li>
				</ul>

			</div>
			<!-- end:sidebar -->

		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:general -->

</main>
<!-- end:main -->
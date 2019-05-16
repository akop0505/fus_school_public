<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var app\models\Content $model */

$urlResourcesOverview = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-overview']);
$urlResourcesProduct = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-product']);
$urlResourcesPresentation = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-presentation']);
$urlResourcesFaq = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-faq']);
$urlResourcesSwag = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-swag']);
$urlResourcesPhotos = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-photos']);
$urlResourcesAgreement = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-agreement']);
$urlResourcesTestimonials = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-testimonials']);
$urlResourcesHelpfulDocuments = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-helpful-documents']);
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

				<!-- start:typography -->
				<div class="entry typography">

					<?= $model->bodyText; ?>

				</div>
				<!-- end:typography -->

				<?= $model->extraHtml; ?>


			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<div class="sidebar">

				<ul>
					<li class="<?php if($page == 'resources-overview') echo 'current'; ?>">
						<a href="<?= $urlResourcesOverview; ?>">
							<i class="fa fa-video-camera fa-fw margin-5-right"></i>
							Overview Video
						</a>
					</li>
					<li class="<?php if($page == 'resources-presentation') echo 'current'; ?>">
						<a href="<?= $urlResourcesPresentation; ?>">
							<i class="fa fa-tv fa-fw margin-5-right"></i>
							Presentation
						</a>
					</li>
					<li class="<?php if($page == 'resources-faq') echo 'current'; ?>">
						<a href="<?= $urlResourcesFaq; ?>">
							<i class="fa fa-support fa-fw margin-5-right"></i>
							FAQs
						</a>
					</li>
					<li class="<?php if($page == 'resources-swag') echo 'current'; ?>">
						<a href="<?= $urlResourcesSwag; ?>">
							<i class="fa fa-child fa-fw margin-5-right"></i>
							SWAG
						</a>
					</li>
					<li class="<?php if($page == 'resources-photos') echo 'current'; ?>">
						<a href="<?= $urlResourcesPhotos; ?>">
							<i class="fa fa-image fa-fw margin-5-right"></i>
							Photos
						</a>
					</li>
					<li class="<?php if($page == 'resources-helpful-documents') echo 'current'; ?>">
						<a href="<?= $urlResourcesHelpfulDocuments; ?>">
							<i class="fa fa-file-text-o fa-fw margin-5-right"></i>
							Helpful Documents
						</a>
					</li>
					<li class="<?php if($page == 'resources-testimonials') echo 'current'; ?>">
						<a href="<?= $urlResourcesTestimonials; ?>">
							<i class="fa fa-files-o fa-fw margin-5-right"></i>
							Testimonials
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

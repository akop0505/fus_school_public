<?php

use yii\helpers\Url;

$urlResourcesOverview = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-overview']);
$urlResourcesProduct = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-product']);
$urlResourcesPresentation = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-presentation']);
$urlResourcesFaq = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-faq']);
$urlResourcesSwag = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-swag']);
$urlResourcesPhotos = Url::toRoute(['site/resources-partial', 'contentType' => 'resources-photos']);
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

	<!-- start:resources -->
	<section class="resources">

		<!-- start:cnt -->
		<div class="cnt clr">

			<!-- start:heading -->
			<header class="heading">
				<h2>Resources</h2>
			</header>
			<!-- end:heading -->

			<!-- start:links -->
			<ul class="links clr">
				<li>
					<a href="<?= $urlResourcesOverview; ?>" class="tbl">
						<span class="tcell vertical-middle">Overview Video</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesProduct; ?>" class="tbl">
						<span class="tcell vertical-middle">Product Overview</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesPresentation; ?>" class="tbl">
						<span class="tcell vertical-middle">Presentation</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesFaq; ?>" class="tbl">
						<span class="tcell vertical-middle">FAQs</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesSwag; ?>" class="tbl">
						<span class="tcell vertical-middle">SWAG</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesPhotos; ?>" class="tbl">
						<span class="tcell vertical-middle">Photos</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesHelpfulDocuments; ?>" class="tbl">
						<span class="tcell vertical-middle">Helpful Documents</span>
					</a>
				</li>
				<li>
					<a href="<?= $urlResourcesTestimonials; ?>" class="tbl">
						<span class="tcell vertical-middle">Testimonials</span>
					</a>
				</li>
			</ul>
			<!-- end:links -->

		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:resources -->

</main>
<!-- end:main -->
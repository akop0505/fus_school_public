<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
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
			<div class="site-error">

				<h1><?= Html::encode($this->title) ?></h1>

				<div class="alert alert-danger">
					<?= nl2br(Html::encode($message)) ?>
				</div>

				<p>
					The above error occurred while the Web server was processing your request.
				</p>
				<p>
					Please contact us if you think this is a server error. Thank you.
				</p>

			</div>
		</div>
		<!-- end:cnt -->

	</section>
	<!-- end:general -->

</main>
<!-- end:main -->
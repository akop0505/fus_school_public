<?php

use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var array $dataResults */
/* @var int $numResults */
/* @var yii\data\Pagination $pagination */

$this->title = '';

?>
<!-- start:header -->
<header id="header">

	<!-- start:cover -->
	<div class="cover">
		<!-- start:top -->
		<?= $this->render('top'); ?>
		<!-- end:top -->
	</div >
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

					<h2>
						Search results for schools:<strong> <?= $numResults; ?> </strong><?= $numResults == 1 ? 'result' : 'results'; ?>
					</h2>

					<ul class="options clr">
						<li class="current">
							<a href="#">
								<i class="fa fa-th"></i>
							</a>
						</li>
						<li>
							<a href="#">
								<i class="fa fa-th-list"></i>
							</a>
						</li>
					</ul>

				</header>

				<!-- start:videos -->
				<div class="videos">
					<?php if(!$message): ?>
						<!-- start:list -->
						<div class="list four clr">
							<?php foreach($dataResults as $data)
							{
								echo $this->render('_searchBox', ['data' => $data]);
							}
							?>
						</div>
						<!-- end:list -->
						<?php
						echo LinkPager::widget([
							'pagination' => $pagination,
							'options' => [
								'class' => 'pagination clr',
							]
						]);
						?>
					<?php else: ?>
						<div class="list four clr">
							<?php echo $message; ?>
						</div>
					<?php endif; ?>
				</div>
				<!-- end:videos -->

			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<div class="sidebar">

				<ul>
					<li class="current">
						<a href="">
							<i class="fa fa-flag fa-fw margin-5-right"></i>
							All results
							<span><?= $numResults; ?></span>
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
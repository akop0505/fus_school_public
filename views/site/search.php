<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var array $dataResults */
/* @var array $search */
/* @var string $page */
/* @var string $searchTerm */
/* @var string $message */
/* @var int $totalCount */
/* @var int $numResults */
/* @var int $searchForArticleCount */
/* @var int $searchForVideoCount */
/* @var int $searchForMembersCount */
/* @var int $searchForChannelCount */
/* @var int $searchForInstitutionCount */
/* @var yii\data\Pagination $pagination */

$this->title = '';

$urlSchool = Url::toRoute(['site/search', 'term' => $searchTerm, 'searchType' => 'school']);
$urlVideo = Url::toRoute(['site/search', 'term' => $searchTerm, 'searchType' => 'video']);
$urlArticle = Url::toRoute(['site/search', 'term' => $searchTerm, 'searchType' => 'article']);
$urlChannel = Url::toRoute(['site/search', 'term' => $searchTerm, 'searchType' => 'channel']);
$urlMembers = Url::toRoute(['site/search', 'term' => $searchTerm, 'searchType' => 'members']);
$urlAll = Url::toRoute(['site/search', 'term' => $searchTerm, 'searchType' => 'default']);

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

				<?php $form = ActiveForm::begin(['action' => ['site/search'], 'method' => 'GET']); ?>
					<input type="text" name="term" placeholder="Enter search term" class="form-control" value="<?= Html::encode($searchTerm) ?>">
					<br>
				<?php ActiveForm::end(); ?>

				<?php if($searchTerm): ?>
				<header class="clr">

					<h2>
						Search results:<strong> <?= $numResults; ?> </strong> results for <strong> <?= '‘' . Html::encode($searchTerm) . '‘'; ?></strong>
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
				<?php endif; ?>
			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<div class="sidebar">

				<ul>
					<li class="<?php if($page == 'default') echo 'current'; ?>">
						<a href="<?= $urlAll; ?>">
							<i class="fa fa-flag fa-fw margin-5-right"></i>
							All results
							<span><?= $totalCount; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'school') echo 'current'; ?>">
						<a href="<?= $urlSchool; ?>">
							<i class="fa fa-graduation-cap fa-fw margin-5-right"></i>
							Schools
							<span><?= $searchForInstitutionCount; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'video') echo 'current'; ?>">
						<a href="<?= $urlVideo; ?>">
							<i class="fa fa-film fa-fw margin-5-right"></i>
							Videos
							<span><?= $searchForVideoCount; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'article') echo 'current'; ?>">
						<a href="<?= $urlArticle; ?>">
							<i class="fa fa-pencil fa-fw margin-5-right"></i>
							Article
							<span><?= $searchForArticleCount; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'channel') echo 'current'; ?>">
						<a href="<?= $urlChannel; ?>">
							<i class="fa fa-indent fa-fw margin-5-right"></i>
							Channels
							<span><?= $searchForChannelCount; ?></span>
						</a>
					</li>
					<li class="<?php if($page == 'members') echo 'current'; ?>">
						<a href="<?= $urlMembers; ?>">
							<i class="fa fa-user fa-fw margin-5-right"></i>
							Members
							<span><?= $searchForMembersCount; ?></span>
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
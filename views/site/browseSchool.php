<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var array $model */
/* @var string $page */

$urlBrowseSchool = Url::toRoute(['site/browse-school', 'searchType' => 'default']);
$urlBrowseCity = Url::toRoute(['site/browse-school', 'searchType' => 'city']);
$urlBrowseState = Url::toRoute(['site/browse-school', 'searchType' => 'state']);
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

	<div id="pageId" style="display: none;" data-page="<?= $page; ?>"></div>
	<!-- start:general -->
	<section class="general">

		<!-- start:cnt -->
		<div class="cnt clr">

			<!-- start:column -->
			<div class="column clr">

				<ul class="alphabet clr">
				<?php foreach(range('A', 'Z') as $letter):?>
					<li id="<?= 'allLetters' . $letter ; ?>" class="allLetters">
						<a href="#" data-letter="<?= $letter; ?>" class="_letter"><?= $letter; ?></a>
					</li>
				<?php endforeach;?>
				</ul>
				<div class="alphabet-list">
					<?php sort($model); ?>
					<?php foreach($model as $school): ?>

						<ul class="allSchools<?= $school['class']; ?>">
							<li>
								<h3><?= Html::encode($school['name']); ?></h3>
								<ul>
									<?php foreach($school['schools'] as $one):
										/* @var  app\models\Institution $one */
									 ?>
									<li>
										<a href="<?= $one->getUrl(); ?>"><?= Html::encode($one->name); ?></a> | <?= Html::encode($one->city->name) . ', ' . Html::encode($one->city->stateId); ?>
									</li>
									<?php endforeach; ?>
								</ul>
							</li>
						</ul>

					<?php endforeach; ?>
				</div>

				<div class="alphabet-list" id="message" style="display: none">
					<p>No matches found.</p>
				</div>
			</div>
			<!-- end:column -->

			<!-- start:sidebar -->
			<div class="sidebar">

				<ul>
					<li class="<?php if($page == 'default') echo 'current'; ?>">
						<a href="<?= $urlBrowseSchool; ?>">
							<i class="fa fa-university fa-fw margin-5-right"></i>
							By school name
						</a>
					</li>
					<li class="<?php if($page == 'city') echo 'current'; ?>">
						<a href="<?= $urlBrowseCity; ?>">
							<i class="fa fa-map-pin fa-fw margin-5-right"></i>
							By city
						</a>
					</li>
					<li class="<?php if($page == 'state') echo 'current'; ?>">
						<a href="<?= $urlBrowseState; ?>">
							<i class="fa fa-map-o fa-fw margin-5-right"></i>
							By state
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

<?php
	$this->registerJs(<<<JSCLIP
	$('._letter').on('click', function(){
		var letter = $(this).data('letter');
		var page = $('#pageId').data('page');
		
		if(page == 'default'){
			$('.allSchools').hide();
			$('.default' + letter).show();
			/*if($('.default' + letter).length > 0 ){
				$('#message').hide();
			}
			else{
				$('#message').show();
			}*/
		}
		else if(page == 'state'){
			$('.allSchools').hide();
			$('.state' + letter).show();
		}
		else if(page == 'city'){
			$('.allSchools').hide();
			$('.city' + letter).show();
		}
		
		if($('.' + page + letter).length > 0 ){
			$('#message').hide();
		}
		else{
			$('#message').show();
		}
		
		$('.allLetters.current').removeClass("current");
    	$(this).parent().addClass("current");
		return false;
	});
	
JSCLIP
		, $this::POS_READY, 'browse-init');

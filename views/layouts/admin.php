<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAdminAsset;

AppAdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
	<?php
	NavBar::begin(
		[
			'brandLabel' => 'FUSFOO',
			'brandUrl' => Yii::$app->homeUrl,
			'options' => [
				'class' => 'navbar-inverse navbar-fixed-top',
			],
		]
	);
	$items = [];
	if(Yii::$app->getUser()->can('user.index')) $items[] = ['label' => Yii::t('app', 'Users'), 'url' => ['/user/index']];
	if(Yii::$app->getUser()->can('institution.index')) $items[] = ['label' => Yii::t('app', 'Schools'), 'url' => ['/institution/index']];
	if(Yii::$app->getUser()->can('channel.index')) $items[] = ['label' => Yii::t('app', 'Channels'), 'url' => ['/channel/index']];
	if(Yii::$app->getUser()->can('tag.index')) $items[] = ['label' => Yii::t('app', 'Tags'), 'url' => ['/tag/index']];
	if(Yii::$app->getUser()->can('post.index')) $items[] = ['label' => Yii::t('app', 'Posts'), 'url' => ['/post/index']];
	if(Yii::$app->getUser()->can('postChannel.index')) $items[] = ['label' => Yii::t('app', 'Post Channel'), 'url' => ['/post-channel/index']];
	if(Yii::$app->getUser()->can('content.index')) $items[] = ['label' => Yii::t('app', 'Content'), 'url' => ['/content/index']];
	if(Yii::$app->getUser()->can('homepageFeatured.index')) $items[] = ['label' => Yii::t('app', 'Homepage Featured'), 'url' => ['/homepage-featured/index']];
	$items[] = [
		'label' => 'Other',
		'items' => [
			Yii::$app->getUser()->can('discoverChannel.index') ? ['label' => Yii::t('app', 'Discover Channels'), 'url' => ['/discover-channel/index']] : '',
			Yii::$app->getUser()->can('featuredChannel.index') ? ['label' => Yii::t('app', 'Featured Channels'), 'url' => ['/featured-channel/index']] : '',
			Yii::$app->getUser()->can('fileUpload.index') ? ['label' => Yii::t('app', 'Files'), 'url' => ['/file-upload/index']] : '',
			Yii::$app->getUser()->can('postRepost.index') ? ['label' => Yii::t('app', 'Repost'), 'url' => ['/post-repost/index']] : '',
			Yii::$app->getUser()->can('city.index') ? ['label' => Yii::t('app', 'Cities'), 'url' => ['/city/index']] : ''
		],
	];
	$items[] = (
		'<li>'
		. Html::beginForm(['/site/logout'], 'post')
		. Html::submitButton(
			'Logout (' . Yii::$app->user->identity->username . ')',
			['class' => 'btn btn-link']
		)
		. Html::endForm()
		. '</li>'
	);
		echo Nav::widget(
		[
			'options' => ['class' => 'navbar-nav navbar-right'],
			'items' => $items,
		]
	);
	NavBar::end();
	?>

	<div class="container">
		<?php echo Alert::widget(); ?>
		<?= Breadcrumbs::widget(
			[
				'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
			]
		) ?>
		<?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; Fusfoo <?= date('Y') ?></p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

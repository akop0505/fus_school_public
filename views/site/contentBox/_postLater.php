<?php

use yii\helpers\Html;
use app\models\Post;

/* @var app\models\UserActivity $data */

$post = Post::findOne($data->activityTypeFk);

if($data->isRemove == 0) $later = ' added to watch later ';
else $later = ' removed from watch later ';
?>
	<li>
		<i class="fa fa-fw fa-clock-o margin-5-right"></i>
		<a href="<?= $data->createdBy->getUrl(); ?>"><?= Html::encode($data->createdBy->getUserFullName()); ?></a>
		<?= $later; ?>
		<?php if($post->isActive == 1): ?><a href="<?= $post->getUrl(); ?>"><?= Html::encode($post->title); ?></a>
		<?php else: ?> <?= Html::encode($post->title); ?>
		<?php endif; ?>
		by
		<a href="<?= $post->createdBy->institution->getUrl(); ?>"><?= Html::encode($post->createdBy->institution); ?></a>
		<?= $data->dateDiff(); ?>
	</li>

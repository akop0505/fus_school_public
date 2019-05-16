<?php

use yii\helpers\Html;
use app\models\Post;

/* @var app\models\UserActivity $data */

$post = Post::findOne($data->activityTypeFk);

if($data->isRemove == 0) $liked = ' liked ';
else $liked = ' unliked ';
?>
	<li>
		<i class="fa fa-fw fa-heart margin-5-right"></i>
		<a href="<?= $data->createdBy->getUrl(); ?>"><?= Html::encode($data->createdBy->getUserFullName()); ?></a>
		<?= $liked; ?>
		<?php if($post->isActive == 1): ?><a href="<?= $post->getUrl(); ?>"><?= Html::encode($post->title); ?></a>
		<?php else: ?> <?= Html::encode($post->title); ?>
		<?php endif; ?>
		by
		<a href="<?= $post->createdBy->institution->getUrl(); ?>"><?= Html::encode($post->createdBy->institution); ?></a>
		<?= $data->dateDiff(); ?>
	</li>

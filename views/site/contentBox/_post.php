<?php

use yii\helpers\Html;
use app\models\Post;

/* @var app\models\UserActivity $data */

$post = Post::findOne($data->activityTypeFk);
?>
	<li>
		<i class="fa fa-fw fa-pencil margin-5-right"></i>
		<a href="<?= $data->createdBy->getUrl(); ?>"><?= Html::encode($data->createdBy->getUserFullName()); ?></a>
		published
		<?php if($post->isActive == 1): ?><a href="<?= $post->getUrl(); ?>"><?= Html::encode($post->title); ?></a>
		<?php else: ?> <?= Html::encode($post->title); ?>
		<?php endif; ?>
		<?= $data->dateDiff(); ?>
	</li>



<?php

use app\models\Tag;

/* @var app\models\UserActivity $data */

$tag = Tag::findOne($data->activityTypeFk);

if($data->isRemove == 0) $subscribed = ' subscribed to ';
else $subscribed = ' unsubscribed from ';
?>

<li>
	<i class="fa fa-fw fa-tag margin-5-right"></i>
	<a href="<?= $data->createdBy->getUrl(); ?>"><?= $data->createdBy; ?></a><?= $subscribed; ?><a href="<?= $tag->getUrl(); ?>"><?= $tag->name; ?></a> <?= $data->dateDiff(); ?>
</li>
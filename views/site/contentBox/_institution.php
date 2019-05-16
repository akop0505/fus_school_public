<?php

use app\models\Institution;

/* @var app\models\UserActivity $data */

$institution = Institution::findOne($data->activityTypeFk);

if($data->isRemove == 0) $liked = ' liked ';
else $liked = ' unliked ';
?>

<li>
	<i class="fa fa-fw fa-heart margin-5-right"></i>
	<a href="<?= $data->createdBy->getUrl(); ?>"><?= $data->createdBy; ?></a><?= $liked; ?><a href="<?= $institution->getUrl(); ?>"><?= $institution->name; ?></a> <?= $data->dateDiff(); ?>
</li>

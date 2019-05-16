<?php
use app\models\Channel;

/* @var app\models\UserActivity $data */

$channel = Channel::findOne($data->activityTypeFk);

if($channel->userId != '')
{
	$name = $channel->user;
	$class = 'fa fa-fw fa-user margin-5-right';
}
elseif($channel->institutionId != '')
{
	$name = $channel->institution;
	$class = 'fa fa-fw fa-university margin-5-right';
}
else $class = $name = '';

if($data->isRemove == 0) $subscribed = ' subscribed to ';
else $subscribed = ' unsubscribed from ';
?>

<li>
	<i class="<?= $class; ?>"></i>
	<a href="<?= $data->createdBy->getUrl(); ?>"><?= $data->createdBy; ?></a><?= $subscribed; ?><a href="<?= $name->getUrl(); ?>"><?= $name; ?></a> <?= $data->dateDiff(); ?>
</li>

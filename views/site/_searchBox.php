<?php

use app\models\Post;
use app\models\Channel;
use app\models\Institution;
use app\models\User;
use yii\base\Exception;

/* @var yii\web\View $this */
/* @var array $data */
/* @var int|null $institutionId */

if(!isset($institutionId)) $institutionId = 0;
switch(true)
{
	case $data instanceof Post:
		echo $this->render('box/_article', ['data' => $data, 'institutionId' => $institutionId]);
		break;
	case $data instanceof Channel:
		echo $this->render('box/_channel', ['data' => $data]);
		break;
	case $data instanceof Institution:
		echo $this->render('box/_school', ['data' => $data]);
		break;
	case $data instanceof User:
		echo $this->render('box/_member', ['data' => $data]);
		break;
	default:
		throw new Exception('Error');
		break;
}

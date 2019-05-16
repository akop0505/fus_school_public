<?php

use yii\base\Exception;

/* @var yii\web\View $this */
/* @var array $data */

switch($data['activityType'])
{
	case 'Post':
		echo $this->render('contentBox/_post', ['data' => $data]);
		break;
	case 'PostLike':
		echo $this->render('contentBox/_postLike', ['data' => $data]);
		break;
	case 'PostFavorite':
		echo $this->render('contentBox/_postFavorite', ['data' => $data]);
		break;
	case 'PostLater':
		echo $this->render('contentBox/_postLater', ['data' => $data]);
		break;
	case 'ChannelSubscribe':
		echo $this->render('contentBox/_channel', ['data' => $data]);
		break;
	case 'TagSubscribe':
		echo $this->render('contentBox/_tag', ['data' => $data]);
		break;
	case 'InstitutionLike':
		echo $this->render('contentBox/_institution', ['data' => $data]);
		break;
	default:
		throw new Exception('Error');
		break;
}
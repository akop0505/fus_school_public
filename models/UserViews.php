<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "UserViews".
 */
class UserViews extends base\UserViews
{
	const VIEWTYPE_POST = 'Post';
	const VIEWTYPE_SCHOOL  = 'School';
	const VIEWTYPE_PROFILE = 'Profile';
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}

	/**
	 * @param $id
	 * @param $viewType
	 * @return bool
	 */
	public static function updateViews($id, $viewType)
	{
		$views = new UserViews();
		if(Yii::$app->user->id) $views->createdById = Yii::$app->user->id;
		$views->viewType = $viewType;
		$views->viewTypeFk = $id;
		$views->createdAt = new Expression('UTC_TIMESTAMP()');
		if(!$views->save()) return true;
		else return false;
	}

	/**
	 * @param integer $id
	 * @param string $viewType
	 * @return bool
	 */
	public static function checkAndUpdateViews($id, $viewType)
	{
		if($viewType == UserViews::VIEWTYPE_POST) $sessionType = 'postsViewed';
		elseif($viewType == UserViews::VIEWTYPE_SCHOOL) $sessionType = 'schoolsViewed';
		else $sessionType = 'profilesViewed';

		$session = Yii::$app->session;
		if(isset($session[$sessionType][$id])) return false;
		else
		{
			UserViews::updateViews($id, $viewType);
			if($session[$sessionType])
			{
				$postViewed = $session[$sessionType];
				$postViewed[$id] = $id;
				$session[$sessionType] = $postViewed;
			}
			else $session[$sessionType] = [$id => $id];
			return true;
		}
	}
}
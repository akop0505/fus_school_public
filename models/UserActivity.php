<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "UserActivity".
 */
class UserActivity extends base\UserActivity
{
	const ACTIVITYTYPE_POST = 'Post';
	const ACTIVITYTYPE_POSTLIKE  = 'PostLike';
	const ACTIVITYTYPE_POSTLATER = 'PostLater';
	const ACTIVITYTYPE_POSTFAVORITE = 'PostFavorite';
	const ACTIVITYTYPE_CHANNELSUBSCRIBE = 'ChannelSubscribe';
	const ACTIVITYTYPE_TAGSUBSCRIBE = 'TagSubscribe';
	const ACTIVITYTYPE_INSTITUTIONLIKE = 'InstitutionLike';

	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}

	public function dateDiff()
	{
		$createdAt = new \DateTime($this->createdAt);
		$today = new \DateTime();
		$interval = $createdAt->diff($today);

		if($interval->y == 0) $year = '';
		elseif($interval->y == 1) $year = $interval->y . ' year';
		else $year = $interval->y . ' years';


		if($interval->m == 0) $month = '';
		elseif($interval->m == 1) $month = $interval->m . ' month';
		else $month = $interval->m . ' months';

		if($interval->d == 0) $day = '';
		elseif($interval->d == 1) $day = $interval->d . ' day';
		else
		{

			if($interval->d > 1 && $interval->d < 7) $day = $interval->d . ' days';
			elseif($interval->d >= 7 && $interval->d < 14)
			{
				$weekDiff = $interval->d - 7;
				if($weekDiff == 0) $day = '1 week';
				elseif($weekDiff == 1) $day = '1 week and ' . $weekDiff . ' day';
				else $day = '1 week and ' . $weekDiff . ' days';
			}
			elseif($interval->d >= 14 && $interval->d < 21)
			{
				$weekDiff = $interval->d - 14;
				if($weekDiff == 0) $day = '2 weeks';
				elseif($weekDiff == 1) $day = '2 weeks and ' . $weekDiff . ' day';
				else $day = '2 weeks and ' . $weekDiff . ' days';
			}
			else
			{
				$weekDiff = $interval->d - 21;
				if($weekDiff == 0) $day = '3 weeks';
				elseif($weekDiff == 1) $day = '3 weeks and ' . $weekDiff . ' day';
				else $day = '3 weeks and ' . $weekDiff . ' days';
			}
		}

		if($year && !$month && !$day) $diff = $year . ' ago';
		elseif($year && $month && !$day) $diff = $year . ' and ' . $month . ' ago';
		elseif($year && $month && $day) $diff = $year . ' and ' . $month . ' and ' . $day . ' ago';
		elseif(!$year && $month && $day) $diff = $month . ' and ' . $day . ' ago';
		elseif(!$year && $month && !$day) $diff = $month . ' ago';
		elseif(!$year && !$month && $day) $diff = $day . ' ago';
		else $diff = 'today';

		return $diff;
	}
}

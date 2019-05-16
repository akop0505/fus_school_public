<?php

namespace app\controllers;

use Yii;
use app\models\Channel;
use app\models\Post;
use app\models\Tag;
use app\models\City;
use app\models\Institution;
use app\models\User;
use app\controllers\common\BaseController;
use yii\helpers\Json;

/**
 * Class AutoCompleteController
 * @package app\controllers
 */
class AutoCompleteController extends BaseController
{
	public function actionCity($term)
	{
		$cities = City::find()->with('state')->where(['LIKE', 'name', $term])->andWhere(['isActive' => 1])->asArray()->all();
		$out = $dataArray = [];
		foreach($cities as $city)
		{
			$dataArray[] = [
				'id' => $city['name'],
				'name' => $city['name'],
				'text' =>  $city['name'] . ' ' . $city['zip'] . ' (' . $city['state']['name'] .')',
				'cityZip' => $city['zip'],
				'cityTimeZoneId' => $city['timeZoneId'],
				'cityStateId' => $city['state']['code']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionInstitution($term)
	{
		$institutions = Institution::find()->where(['LIKE', 'name', $term])->andWhere(['isActive' => 1])->asArray()->all();
		$out = $dataArray = [];
		foreach($institutions as $one)
		{
			$dataArray[] = [
				'id' => $one['id'],
				'text' => $one['name'],
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionUser($term)
	{
		$institutions = User::find()->where(['LIKE', 'firstName', $term])
			->orWhere(['LIKE', 'lastName', $term])
			->orWhere(['LIKE', 'username', $term])
			->asArray()->all();
		$out = $dataArray = [];
		foreach($institutions as $one)
		{
			$dataArray[] = [
				'id' => $one['id'],
				'text' => $one['firstName'] . ' ' . $one['lastName'] . ' (' . $one['email'] . ')',
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionTag($term, $setWithId = false)
	{
		$tag = Tag::find()->where(['LIKE', 'name', $term])->andWhere(['isActive' => 1])->asArray()->all();
		$out = $dataArray = [];
		foreach($tag as $one)
		{
			if($setWithId) $id = $one['id'];
			else $id = $one['name'];
			$dataArray[] = [
				'id' => $id,
				'text' => $one['name']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionChannel($term, $cond = false, $isSystem = false)
	{
		$query = Channel::find()->where(['LIKE', 'name', $term])
			->andWhere(['isActive' => 1]);

		if($cond)
		{
			if($isSystem)$query = $query->andWhere(['isSystem' => 1]);
			else $query = $query->andWhere(['isSystem' => 0]);
		}
		$channels = $query->asArray()->all();

		$out = $dataArray = [];
		foreach($channels as $channel)
		{
			$dataArray[] = [
				'id' => $channel['id'],
				'text' => $channel['name']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionCityByState($term, $stateId)
	{
		$cities = City::find()->where(['LIKE', 'name', $term])
			->andWhere(['isActive' => 1])
			->andWhere(['stateId' => $stateId])
			->asArray()->all();
		$out = $dataArray = [];
		foreach($cities as $city)
		{
			$dataArray[] = [
				'id' => $city['id'],
				'text' =>  $city['name']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionRepost($term)
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;
		$post = Post::find()->innerJoinWith('postReposts')
			->where(['LIKE', 'title', $term])
			->andWhere(['isActive' => 1]);

		if(Yii::$app->user->can('ApprovePost') || Yii::$app->user->can('ApproveVideo'))
		{
			$post = $post->andWhere(['institutionId' => $user->institutionId]);
		}
		else
		{
			$post = $post->andWhere(['PostRepost.createdById' => $user->getId()]);
		}

		$post = $post->asArray()->all();
		$out = $dataArray = [];
		foreach($post as $one)
		{
			$dataArray[] = [
				'id' => $one['id'],
				'text' => $one['title']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionPost($term)
	{
		$post = Post::find()->where(['LIKE', 'title', $term])
			->andWhere(['isActive' => 1])->asArray()->all();

		$out = $dataArray = [];
		foreach($post as $one)
		{
			$dataArray[] = [
				'id' => $one['id'],
				'text' => $one['title']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionFeatured($channelId, $term)
	{
		$post = Post::find()->innerJoinWith('postChannels')->where(['LIKE', 'title', $term])
			->andWhere(['isActive' => 1])->andWhere(['channelId' => $channelId])->asArray()->all();

		$out = $dataArray = [];
		foreach($post as $one)
		{
			$dataArray[] = [
				'id' => $one['id'],
				'text' => $one['title']
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	public function actionStudent($term = false)
	{
		$students = User::find()->where(['institutionId' => Yii::$app->user->identity->institutionId, 'status' => 'active']);
		if($term)
		{
			$students->andFilterWhere(['or',
				['LIKE', 'firstName', $term],
				['LIKE', 'lastName', $term],
				['LIKE', 'username', $term]]
			);
		}
		$students = $students->asArray()->all();
		$out = $dataArray = [];
		foreach($students as $one)
		{
			$dataArray[] = [
				'id' => $one['id'],
				'text' => $one['firstName'] . ' ' . $one['lastName'] . ' (' . $one['email'] . ')',
			];
		}
		$out['results'] = $dataArray;
		echo Json::encode($out);
	}

	/**
	 * @inheritdoc
	 */ 	 
	protected function findModel($id)
	{
	}
}
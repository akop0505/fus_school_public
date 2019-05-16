<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PostChannel as PostChannelModel;

/**
 * PostChannel represents the model behind the search form about `app\models\PostChannel`.
 */
class PostChannel extends PostChannelModel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['channelId', 'postId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		// bypass behaviors() implementation in the parent class
		return Model::behaviors();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = PostChannelModel::find()->joinWith('channel');
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'channelId' => $this->channelId,
			'postId' => $this->postId,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
		]);

		return $dataProvider;
	}
}
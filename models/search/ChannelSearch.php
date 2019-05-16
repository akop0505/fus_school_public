<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Channel;

/**
 * ChannelSearch represents the model behind the search form about `app\models\Channel`.
 */
class ChannelSearch extends Channel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'institutionId', 'userId', 'hasPhoto', 'hasPortraitPhoto', 'numPosts', 'numSubscribers', 'isActive', 'isSystem', 'createdById', 'updatedById'], 'integer'],
			[['name', 'description', 'createdAt', 'updatedAt'], 'safe'],
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
		$query = Channel::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'id' => $this->id,
			'institutionId' => $this->institutionId,
			'userId' => $this->userId,
			'hasPhoto' => $this->hasPhoto,
			'hasPortraitPhoto' => $this->hasPortraitPhoto,
			'numPosts' => $this->numPosts,
			'numSubscribers' => $this->numSubscribers,
			'isActive' => $this->isActive,
			'isSystem' => $this->isSystem,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
			'updatedAt' => $this->updatedAt,
			'updatedById' => $this->updatedById,
		]);

		$query->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'description', $this->description]);

		return $dataProvider;
	}
}
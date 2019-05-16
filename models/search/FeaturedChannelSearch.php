<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FeaturedChannel;

/**
 * FeaturedChannelSearch represents the model behind the search form about `app\models\FeaturedChannel`.
 */
class FeaturedChannelSearch extends FeaturedChannel
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['channelId', 'sort', 'numPost'], 'integer'],
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
		$query = FeaturedChannel::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'channelId' => $this->channelId,
			'sort' => $this->sort,
			'numPost' => $this->numPost,
		]);

		return $dataProvider;
	}
}
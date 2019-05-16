<?php

namespace app\models\search;

use app\models\PostFeatured;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostFeaturedSearch represents the model behind the search form about `app\models\PostFeatured`.
 */
class PostFeaturedSearch extends PostFeatured
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['institutionId', 'postId', 'sort'], 'integer'],
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
		$query = PostFeatured::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'institutionId' => $this->institutionId,
			'postId' => $this->postId,
			'sort' => $this->sort,
		]);

		return $dataProvider;
	}
}
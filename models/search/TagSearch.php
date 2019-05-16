<?php

namespace app\models\search;

use app\models\Tag;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TagSearch represents the model behind the search form about `app\models\Tag`.
 */
class TagSearch extends Tag
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'isActive'], 'integer'],
			[['name'], 'safe'],
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
		$query = Tag::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'id' => $this->id,
			'isActive' => $this->isActive,
		]);

		$query->andFilterWhere(['like', 'name', $this->name]);

		return $dataProvider;
	}
}
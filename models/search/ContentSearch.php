<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Content;

/**
 * ContentSearch represents the model behind the search form about `app\models\Content`.
 */
class ContentSearch extends Content
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'createdById', 'updatedById'], 'integer'],
			[['urlSlug', 'title', 'bodyText', 'createdAt', 'updatedAt', 'extraHtml'], 'safe'],
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
		$query = Content::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'id' => $this->id,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
			'updatedAt' => $this->updatedAt,
			'updatedById' => $this->updatedById,
		]);

		$query->andFilterWhere(['like', 'urlSlug', $this->urlSlug])
			->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'bodyText', $this->bodyText])
			->andFilterWhere(['like', 'extraHtml', $this->extraHtml]);

		return $dataProvider;
	}
}
<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Institution;

/**
 * InstitutionSearch represents the model behind the search form about `app\models\Institution`.
 */
class InstitutionSearch extends Institution
{
	public $city;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'cityId', 'numLikes', 'isActive', 'createdById', 'updatedById'], 'integer'],
			[['name', 'address', 'themeColor', 'createdAt', 'updatedAt', 'city'], 'safe'],
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
		$query = Institution::find();
		$query->joinWith(['city']);
		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->sort->attributes['city'] = [
			'asc' => ['City.name' => SORT_ASC],
			'desc' => ['City.name' => SORT_DESC],
		];

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'Institution.id' => $this->id,
			'cityId' => $this->cityId,
			'numLikes' => $this->numLikes,
			'isActive' => $this->isActive,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
			'updatedAt' => $this->updatedAt,
			'updatedById' => $this->updatedById,
			'hasLatestPhoto' => $this->hasLatestPhoto
		]);

		$query->andFilterWhere(['like', 'Institution.name', $this->name])
			->andFilterWhere(['like', 'address', $this->address])
			->andFilterWhere(['like', 'City.name', $this->city])
			->andFilterWhere(['like', 'themeColor', $this->themeColor])
			->andFilterWhere(['like', 'about', $this->about]);

		return $dataProvider;
	}
}
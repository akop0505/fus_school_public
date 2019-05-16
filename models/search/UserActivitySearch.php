<?php

namespace app\models\search;

use app\models\UserActivity;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserActivitySearch represents the model behind the search form about `app\models\UserActivity`.
 */
class UserActivitySearch extends UserActivity
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'activityTypeFk', 'createdById'], 'integer'],
			[['activityType', 'createdAt'], 'safe'],
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
		$query = UserActivity::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'id' => $this->id,
			'activityTypeFk' => $this->activityTypeFk,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
		]);

		$query->andFilterWhere(['like', 'activityType', $this->activityType]);

		return $dataProvider;
	}
}
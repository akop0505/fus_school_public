<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PostMedia;

/**
 * PostMediaSearch represents the model behind the search form about `app\models\PostMedia`.
 */
class PostMediaSearch extends PostMedia
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'postId', 'sort'], 'integer'],
			[['filename'], 'safe'],
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
		$query = PostMedia::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'id' => $this->id,
			'postId' => $this->postId,
			'sort' => $this->sort,
		]);

		$query->andFilterWhere(['like', 'filename', $this->filename]);

		return $dataProvider;
	}
}
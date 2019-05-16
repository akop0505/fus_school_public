<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FileUpload;

/**
 * FileUploadSearch represents the model behind the search form about `app\models\FileUpload`.
 */
class FileUploadSearch extends FileUpload
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'createdById'], 'integer'],
			[['fileName', 'createdAt'], 'safe'],
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
		$query = FileUpload::find();
		$dataProvider = new ActiveDataProvider(['query' => $query]);

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'id' => $this->id,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
		]);

		$query->andFilterWhere(['like', 'fileName', $this->fileName]);

		return $dataProvider;
	}
}
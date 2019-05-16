<?php

namespace app\models\search;

use app\models\PostRepost;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostRepostSearch represents the model behind the search form about `app\models\PostRepost`.
 */
class PostRepostSearch extends PostRepost
{
	public $hasVideo;
	public $postTitle;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['postId', 'institutionId', 'isApproved', 'createdById'], 'integer'],
			[['createdAt', 'hasVideo', 'postTitle'], 'safe'],
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
		$query = PostRepost::find();
		$query->joinWith(['post']);
		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->sort->attributes['hasVideo'] = [
			'asc' => ['Post.video' => SORT_ASC],
			'desc' => ['Post.video' => SORT_DESC],
		];
		$dataProvider->sort->attributes['postTitle'] = [
			'asc' => ['Post.title' => SORT_ASC],
			'desc' => ['Post.title' => SORT_DESC],
		];

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'postId' => $this->postId,
			'institutionId' => $this->institutionId,
			'PostRepost.isApproved' => $this->isApproved,
			'PostRepost.createdAt' => $this->createdAt,
			'PostRepost.createdById' => $this->createdById,
		]);

		if($this->hasVideo === '0') $query->andWhere(['=', 'video', '']);
		elseif($this->hasVideo === '1') $query->andWhere(['!=', 'video', '']);

		$query->andFilterWhere(['like', 'title', $this->postTitle]);

		return $dataProvider;
	}
}
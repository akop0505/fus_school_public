<?php

namespace app\models\search;

use app\models\Post;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;
/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class PostSearch extends Post
{
	public $institutionId;
	public $hasVideo;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'hasHeaderPhoto', 'hasThumbPhoto', 'views', 'isActive', 'isApproved', 'createdById', 'updatedById', 'isNational', 'institutionId', 'approvedById'], 'integer'],
			[['title', 'postText', 'createdAt', 'updatedAt', 'hasVideo', 'datePublished'], 'safe'],
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
		$query = Post::find();
		$query->joinWith(['createdBy']);
		$query->joinWith(['approvedBy ab']);
		$query->joinWith(['updatedBy ub']);
		$dataProvider = new ActiveDataProvider(['query' => $query, 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]]);
		$dataProvider->sort->attributes['hasVideo'] = [
			'asc' => ['Post.video' => SORT_ASC],
			'desc' => ['Post.video' => SORT_DESC],
		];

		if(!($this->load($params) && $this->validate())) return $dataProvider;

		$query->andFilterWhere([
			'Post.id' => $this->id,
			'hasHeaderPhoto' => $this->hasHeaderPhoto,
			'hasThumbPhoto' => $this->hasThumbPhoto,
			'views' => $this->views,
			'isActive' => $this->isActive,
			'isApproved' => $this->isApproved,
			'isNational' => $this->isNational,
			'createdAt' => $this->createdAt,
			'createdById' => $this->createdById,
			'updatedAt' => $this->updatedAt,
			'updatedById' => $this->updatedById,
			'approvedById' => $this->approvedById,
			'datePublished' => $this->datePublished,
			'User.institutionId' => $this->institutionId
		]);

		if($this->hasVideo === '0') $query->andWhere(['=', 'video', '']);
		elseif($this->hasVideo === '1') $query->andWhere(['!=', 'video', '']);

		$query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'postText', $this->postText]);

		return $dataProvider;
	}
}
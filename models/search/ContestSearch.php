<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contest;

/**
 * ContestSearch represents the model behind the search form about `app\models\Contest`.
 */
class ContestSearch extends Contest
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'content','datePublished','createdAt', 'updatedAt'], 'safe'],
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
        $query = Contest::find();
        $query->orderBy(["Contest.datePublished"=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        if(!($this->load($params) && $this->validate())) return $dataProvider;
        $query->andFilterWhere([
            'Contest.id' => $this->id,
            'datePublished' => $this->datePublished,
            'createdAt' => $this->createdAt,
            'createdById' => $this->createdById,
            'updatedAt' => $this->updatedAt,
        ]);
//
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
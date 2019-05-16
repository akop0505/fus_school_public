<?php

namespace app\models\search;

use app\models\Form;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * PostSearch represents the model behind the search form about `app\models\Post`.
 */
class FormSearch extends Form
{
    public $hasVideo;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','isApproved', 'approvedById'], 'integer'],
            [['first_name','last_name','email', 'createdAt', 'updatedAt'], 'safe'],
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
        $query = Form::find();
        $query->joinWith(['approvedBy ab']);
        $dataProvider = new ActiveDataProvider(['query' => $query, 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]]);

        if(isset($params['id'])) {
            $query->andFilterWhere(['contest_id' => $params['id']]);
        }
        if(!($this->load($params) && $this->validate())) return $dataProvider;

        $query->andFilterWhere([
            'Form.id' => $this->id,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'isApproved'=> $this->isApproved,
            'approvedById' => $this->approvedById,
        ]);

        $query
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'Form.email', $this->email])
            ->andFilterWhere(['like', 'school', $this->school]);

        return $dataProvider;
    }
}
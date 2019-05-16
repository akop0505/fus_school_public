<?php

namespace app\models\search;

use app\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * UserSearch represents the model behind the search form about `app\models\User`.
 */
class UserSearch extends User
{
	public $institution;
	public $schoolAdmin;
	public $schoolAuthor;
	public $approvePost;
	public $approveVideo;
	public $approveUser;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'emailVerified', 'isMale', 'timeZoneId', 'institutionId'], 'integer'],
			[['username', 'authKey', 'passwordHash', 'passwordResetToken', 'email', 'status', 'createdAt', 'updatedAt', 'lastLogin', 'firstName', 'lastName', 'dateOfBirth', 'mobilePhone', 'institution', 'schoolAdmin', 'schoolAuthor', 'approvePost', 'approveVideo', 'approveUser'], 'safe'],
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
	 * @inheritdoc
	 */
	public function beforeValidate()
	{
		return Model::beforeValidate();
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
		$query = User::find();
		$query->joinWith(['institution']);
		$dataProvider = new ActiveDataProvider(['query' => $query]);
		$dataProvider->sort->attributes['institution'] = [
			'asc' => ['Institution.name' => SORT_ASC],
			'desc' => ['Institution.name' => SORT_DESC],
		];

		if(!($this->load($params) && $this->validate()))
		{
			$query->andFilterWhere(['!=', 'status', self::STATUS_DELETED]);
			return $dataProvider;
		}
		elseif(!$this->status) $query->andFilterWhere(['!=', 'status', self::STATUS_DELETED]);

		if($this->schoolAdmin != '')
		{
			if($this->schoolAdmin > 0)
			{
				$query->join(
					'JOIN',
					'auth_assignment as aa1',
					['aa1.user_id' => new Expression('`User`.`id`'), 'aa1.item_name' => 'SchoolAdmin']
				);
			}
			else
			{
				$query->join(
					'LEFT JOIN',
					'auth_assignment as aa1',
					['aa1.user_id' => new Expression('`User`.`id`'), 'aa1.item_name' => 'SchoolAdmin']
				);
				$query->andWhere('aa1.user_id is null');
			}
		}
		if($this->schoolAuthor != '')
		{
			if($this->schoolAuthor > 0)
			{
				$query->join(
					'JOIN',
					'auth_assignment as aa2',
					['aa2.user_id' => new Expression('`User`.`id`'), 'aa2.item_name' => 'SchoolAuthor']
				);
			}
			else
			{
				$query->join(
					'LEFT JOIN',
					'auth_assignment as aa2',
					['aa2.user_id' => new Expression('`User`.`id`'), 'aa2.item_name' => 'SchoolAuthor']
				);
				$query->andWhere('aa2.user_id is null');
			}
		}
		if($this->approvePost != '')
		{
			if($this->approvePost > 0)
			{
				$query->join(
					'JOIN',
					'auth_assignment as aa3',
					['aa3.user_id' => new Expression('`User`.`id`'), 'aa3.item_name' => 'ApprovePost']
				);
			}
			else
			{
				$query->join(
					'LEFT JOIN',
					'auth_assignment as aa3',
					['aa3.user_id' => new Expression('`User`.`id`'), 'aa3.item_name' => 'ApprovePost']
				);
				$query->andWhere('aa3.user_id is null');
			}
		}
		if($this->approveVideo != '')
		{
			if($this->approveVideo > 0)
			{
				$query->join(
					'JOIN',
					'auth_assignment as aa4',
					['aa4.user_id' => new Expression('`User`.`id`'), 'aa4.item_name' => 'ApproveVideo']
				);
			}
			else
			{
				$query->join(
					'LEFT JOIN',
					'auth_assignment as aa4',
					['aa4.user_id' => new Expression('`User`.`id`'), 'aa4.item_name' => 'ApproveVideo']
				);
				$query->andWhere('aa4.user_id is null');
			}
		}
		if($this->approveUser != '')
		{
			if($this->approveUser > 0)
			{
				$query->join(
					'JOIN',
					'auth_assignment as aa5',
					['aa5.user_id' => new Expression('`User`.`id`'), 'aa5.item_name' => 'ApproveUser']
				);
			}
			else
			{
				$query->join(
					'LEFT JOIN',
					'auth_assignment as aa5',
					['aa5.user_id' => new Expression('`User`.`id`'), 'aa5.item_name' => 'ApproveUser']
				);
				$query->andWhere('aa5.user_id is null');
			}
		}

		$query->andFilterWhere([
			'User.id' => $this->id,
			'emailVerified' => $this->emailVerified,
			'createdAt' => $this->createdAt,
			'updatedAt' => $this->updatedAt,
			'lastLogin' => $this->lastLogin,
			'isMale' => $this->isMale,
			'dateOfBirth' => $this->dateOfBirth,
			'timeZoneId' => $this->timeZoneId,
			'institutionId' => $this->institution,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'authKey', $this->authKey])
			->andFilterWhere(['like', 'passwordHash', $this->passwordHash])
			->andFilterWhere(['like', 'passwordResetToken', $this->passwordResetToken])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'status', $this->status])
			->andFilterWhere(['like', 'firstName', $this->firstName])
			->andFilterWhere(['like', 'lastName', $this->lastName])
			->andFilterWhere(['like', 'mobilePhone', $this->mobilePhone]);
			
		return $dataProvider;
	}
}
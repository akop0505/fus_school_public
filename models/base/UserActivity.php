<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as UserActivityBaseActiveRecord;
use \app\models\User as UserActivityUser;

/**
 * This is the base-model class for table "UserActivity".
 *
 * @property string $id
 * @property string $activityType
 * @property integer $activityTypeFk
 * @property string $createdAt
 * @property integer $createdById
 * @property integer $isRemove
 *
 * @property UserActivityUser $createdBy
 */
class UserActivity extends UserActivityBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'UserActivity';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{UserActivity} other{UserActivities}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById'], 'safe'];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['activityType'], 'string'],
			[['activityTypeFk', 'createdAt', 'createdById'], 'required'],
			[['activityTypeFk', 'createdById', 'isRemove'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => UserActivityUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			['activityType', 'in', 'range' => [
					static::ACTIVITYTYPE_POST,
					static::ACTIVITYTYPE_POSTLIKE,
					static::ACTIVITYTYPE_POSTLATER,
					static::ACTIVITYTYPE_POSTFAVORITE,
					static::ACTIVITYTYPE_CHANNELSUBSCRIBE,
					static::ACTIVITYTYPE_TAGSUBSCRIBE,
					static::ACTIVITYTYPE_INSTITUTIONLIKE,
				]
			]
		];
		if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
		return $r;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		$ret = [
			'id' => Yii::t('models', 'ID'),
			'activityType' => Yii::t('models', 'Activity Type'),
			'activityTypeFk' => Yii::t('models', 'Activity Type Fk'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
			'isRemove' => Yii::t('models', 'Is Remove'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'createdBy' => array('BELONGS_TO', UserActivityUser::className(), 'createdById'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(UserActivityUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\UserActivity|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

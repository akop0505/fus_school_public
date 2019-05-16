<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as UserViewsBaseActiveRecord;
use \app\models\User as UserViewsUser;

/**
 * This is the base-model class for table "UserViews".
 *
 * @property string $id
 * @property string $viewType
 * @property integer $viewTypeFk
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property UserViewsUser $createdBy
 */
class UserViews extends UserViewsBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'UserViews';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{UserViews} other{UserViewss}}", ["n" =>  $n]);
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
			[['viewType'], 'string'],
			[['viewTypeFk', 'createdAt'], 'required'],
			[['viewTypeFk', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => UserViewsUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			['viewType', 'in', 'range' => [
					static::VIEWTYPE_POST,
					static::VIEWTYPE_SCHOOL,
					static::VIEWTYPE_PROFILE,
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
			'viewType' => Yii::t('models', 'View Type'),
			'viewTypeFk' => Yii::t('models', 'View Type Fk'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
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
			'createdBy' => array('BELONGS_TO', UserViewsUser::className(), 'createdById'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(UserViewsUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\UserViews|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

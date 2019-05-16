<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as InstitutionLikeBaseActiveRecord;
use \app\models\User as InstitutionLikeUser;
use \app\models\Institution as InstitutionLikeInstitution;

/**
 * This is the base-model class for table "InstitutionLike".
 *
 * @property integer $institutionId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property InstitutionLikeUser $createdBy
 * @property InstitutionLikeInstitution $institution
 */
class InstitutionLike extends InstitutionLikeBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'InstitutionLike';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{InstitutionLike} other{InstitutionLikes}}", ["n" =>  $n]);
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
			[['institutionId', 'createdAt', 'createdById'], 'required'],
			[['institutionId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => InstitutionLikeUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => InstitutionLikeInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']]
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
			'institutionId' => Yii::t('models', 'Institution ID'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
			$ret['institutionId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'createdBy' => array('BELONGS_TO', InstitutionLikeUser::className(), 'createdById'),
			'institution' => array('BELONGS_TO', InstitutionLikeInstitution::className(), 'institutionId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(InstitutionLikeUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(InstitutionLikeInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\InstitutionLike|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

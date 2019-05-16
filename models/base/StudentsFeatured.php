<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as StudentsFeaturedBaseActiveRecord;
use \app\models\Institution as StudentsFeaturedInstitution;
use \app\models\User as StudentsFeaturedUser;

/**
 * This is the base-model class for table "StudentsFeatured".
 *
 * @property integer $institutionId
 * @property integer $userId
 * @property integer $sort
 *
 * @property StudentsFeaturedInstitution $institution
 * @property StudentsFeaturedUser $user
 */
class StudentsFeatured extends StudentsFeaturedBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'StudentsFeatured';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{StudentsFeatured} other{StudentsFeatureds}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['institutionId', 'userId', 'sort'], 'required'],
			[['institutionId', 'userId', 'sort'], 'integer'],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => StudentsFeaturedInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['userId'], 'exist', 'skipOnError' => true, 'targetClass' => StudentsFeaturedUser::className(), 'targetAttribute' => ['userId' => 'id']]
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
			'userId' => Yii::t('models', 'User ID'),
			'sort' => Yii::t('models', 'Sort'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['institutionId'] = false;
			$ret['userId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'institution' => array('BELONGS_TO', StudentsFeaturedInstitution::className(), 'institutionId'),
			'user' => array('BELONGS_TO', StudentsFeaturedUser::className(), 'userId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(StudentsFeaturedInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(StudentsFeaturedUser::className(), ['id' => 'userId']);
	}
}

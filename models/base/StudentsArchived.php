<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as StudentsArchivedBaseActiveRecord;
use \app\models\Institution as StudentsArchivedInstitution;
use \app\models\User as StudentsArchivedUser;

/**
 * This is the base-model class for table "StudentsArchived".
 *
 * @property integer $institutionId
 * @property integer $userId
 * @property integer $sort
 *
 * @property StudentsArchivedInstitution $institution
 * @property StudentsArchivedUser $user
 */
class StudentsArchived extends StudentsArchivedBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'StudentsArchived';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{StudentsArchived} other{StudentsArchiveds}}", ["n" =>  $n]);
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
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => StudentsArchivedInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['userId'], 'exist', 'skipOnError' => true, 'targetClass' => StudentsArchivedUser::className(), 'targetAttribute' => ['userId' => 'id']]
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
			'institution' => array('BELONGS_TO', StudentsArchivedInstitution::className(), 'institutionId'),
			'user' => array('BELONGS_TO', StudentsArchivedUser::className(), 'userId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(StudentsArchivedInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(StudentsArchivedUser::className(), ['id' => 'userId']);
	}
}

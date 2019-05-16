<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as FileUploadBaseActiveRecord;
use \app\models\User as FileUploadUser;

/**
 * This is the base-model class for table "FileUpload".
 *
 * @property integer $id
 * @property string $fileName
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property FileUploadUser $createdBy
 */
class FileUpload extends FileUploadBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'FileUpload';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{FileUpload} other{FileUploads}}", ["n" =>  $n]);
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
			[['fileName', 'createdAt', 'createdById'], 'required'],
			[['createdAt'], 'safe'],
			[['createdById'], 'integer'],
			[['fileName'], 'string', 'max' => 255],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => FileUploadUser::className(), 'targetAttribute' => ['createdById' => 'id']]
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
			'fileName' => Yii::t('models', 'File Name'),
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
			'createdBy' => array('BELONGS_TO', FileUploadUser::className(), 'createdById'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(FileUploadUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\FileUpload|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

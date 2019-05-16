<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as ContentBaseActiveRecord;
use \app\models\User as ContentUser;

/**
 * This is the base-model class for table "Content".
 *
 * @property integer $id
 * @property string $urlSlug
 * @property string $title
 * @property string $bodyText
 * @property string $createdAt
 * @property integer $createdById
 * @property string $updatedAt
 * @property integer $updatedById
 * @property string $extraHtml
 *
 * @property ContentUser $createdBy
 * @property ContentUser $updatedBy
 */
class Content extends ContentBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Content';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{Content} other{Contents}}", ["n" =>  $n]);
	}

	/**
	 * Return rule to mark attributes as unsafe or false if none
	 * @return bool|array
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById', '!updatedAt', '!updatedById'], 'safe'];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['urlSlug', 'title', 'bodyText', 'createdAt', 'createdById', 'updatedAt', 'updatedById'], 'required'],
			[['bodyText', 'extraHtml'], 'string'],
			[['createdAt', 'updatedAt'], 'safe'],
			[['createdById', 'updatedById'], 'integer'],
			[['urlSlug', 'title'], 'string', 'max' => 64],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => ContentUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => ContentUser::className(), 'targetAttribute' => ['updatedById' => 'id']]
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
			'urlSlug' => Yii::t('models', 'Url Slug'),
			'title' => Yii::t('models', 'Title'),
			'bodyText' => Yii::t('models', 'Body Text'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
			'updatedAt' => Yii::t('models', 'Updated At'),
			'updatedById' => Yii::t('models', 'Updated By ID'),
			'updatedBy' => Yii::t('models', 'Updated By'),
			'extraHtml' => Yii::t('models', 'Extra Html'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
			$ret['updatedById'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'createdBy' => array('BELONGS_TO', ContentUser::className(), 'createdById'),
			'updatedBy' => array('BELONGS_TO', ContentUser::className(), 'updatedById'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(ContentUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUpdatedBy()
	{
		return $this->hasOne(ContentUser::className(), ['id' => 'updatedById']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\Content|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

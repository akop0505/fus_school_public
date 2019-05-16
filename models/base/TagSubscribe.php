<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as TagSubscribeBaseActiveRecord;
use \app\models\User as TagSubscribeUser;
use \app\models\Tag as TagSubscribeTag;

/**
 * This is the base-model class for table "TagSubscribe".
 *
 * @property integer $tagId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property TagSubscribeUser $createdBy
 * @property TagSubscribeTag $tag
 */
class TagSubscribe extends TagSubscribeBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TagSubscribe';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{TagSubscribe} other{TagSubscribes}}", ["n" =>  $n]);
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
			[['tagId', 'createdAt', 'createdById'], 'required'],
			[['tagId', 'createdById'], 'integer'],
			[['createdAt'], 'safe'],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => TagSubscribeUser::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => TagSubscribeTag::className(), 'targetAttribute' => ['tagId' => 'id']]
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
			'tagId' => Yii::t('models', 'Tag ID'),
			'createdAt' => Yii::t('models', 'Created At'),
			'createdById' => Yii::t('models', 'Created By ID'),
			'createdBy' => Yii::t('models', 'Created By'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['createdById'] = false;
			$ret['tagId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'createdBy' => array('BELONGS_TO', TagSubscribeUser::className(), 'createdById'),
			'tag' => array('BELONGS_TO', TagSubscribeTag::className(), 'tagId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBy()
	{
		return $this->hasOne(TagSubscribeUser::className(), ['id' => 'createdById']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTag()
	{
		return $this->hasOne(TagSubscribeTag::className(), ['id' => 'tagId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\TagSubscribe|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

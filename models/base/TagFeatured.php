<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as TagFeaturedBaseActiveRecord;
use \app\models\Institution as TagFeaturedInstitution;
use \app\models\Tag as TagFeaturedTag;

/**
 * This is the base-model class for table "TagFeatured".
 *
 * @property integer $institutionId
 * @property integer $tagId
 * @property integer $sort
 *
 * @property TagFeaturedInstitution $institution
 * @property TagFeaturedTag $tag
 */
class TagFeatured extends TagFeaturedBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TagFeatured';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{TagFeatured} other{TagFeatureds}}", ["n" =>  $n]);
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
			[['institutionId', 'tagId', 'sort'], 'required'],
			[['institutionId', 'tagId', 'sort'], 'integer'],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => TagFeaturedInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => TagFeaturedTag::className(), 'targetAttribute' => ['tagId' => 'id']]
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
			'tagId' => Yii::t('models', 'Tag ID'),
			'sort' => Yii::t('models', 'Sort'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['institutionId'] = false;
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
			'institution' => array('BELONGS_TO', TagFeaturedInstitution::className(), 'institutionId'),
			'tag' => array('BELONGS_TO', TagFeaturedTag::className(), 'tagId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(TagFeaturedInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTag()
	{
		return $this->hasOne(TagFeaturedTag::className(), ['id' => 'tagId']);
	}
}

<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostFeaturedBaseActiveRecord;
use \app\models\Institution as PostFeaturedInstitution;
use \app\models\Post as PostFeaturedPost;

/**
 * This is the base-model class for table "PostFeatured".
 *
 * @property integer $institutionId
 * @property integer $postId
 * @property integer $sort
 *
 * @property PostFeaturedInstitution $institution
 * @property PostFeaturedPost $post
 */
class PostFeatured extends PostFeaturedBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostFeatured';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostFeatured} other{PostFeatureds}}", ["n" =>  $n]);
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
			[['institutionId', 'postId', 'sort'], 'required'],
			[['institutionId', 'postId', 'sort'], 'integer'],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => PostFeaturedInstitution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostFeaturedPost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'postId' => Yii::t('models', 'Post ID'),
			'sort' => Yii::t('models', 'Sort'),
		];
		if($this->getScenario() == 'formModel')
		{
			$ret['institutionId'] = false;
			$ret['postId'] = false;
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'institution' => array('BELONGS_TO', PostFeaturedInstitution::className(), 'institutionId'),
			'post' => array('BELONGS_TO', PostFeaturedPost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getInstitution()
	{
		return $this->hasOne(PostFeaturedInstitution::className(), ['id' => 'institutionId']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostFeaturedPost::className(), ['id' => 'postId']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\PostFeatured|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

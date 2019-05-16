<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as TagBaseActiveRecord;
use \app\models\PostTag as TagPostTag;
use \app\models\Post as TagPost;
use \app\models\TagSubscribe as TagTagSubscribe;
use \app\models\User as TagUser;

/**
 * This is the base-model class for table "Tag".
 *
 * @property integer $id
 * @property string $name
 * @property integer $isActive
 *
 * @property TagPostTag[] $postTags
 * @property TagPost[] $posts
 * @property TagTagSubscribe[] $tagSubscribes
 * @property TagUser[] $createdBies
 */
class Tag extends TagBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Tag';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{Tag} other{Tags}}", ["n" =>  $n]);
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
			[['name'], 'required'],
			[['isActive'], 'integer'],
			[['name'], 'string', 'max' => 255],
			[['name'], 'unique']
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
			'name' => Yii::t('models', 'Name'),
			'isActive' => Yii::t('models', 'Is Active'),
		];
		if($this->getScenario() == 'formModel')
		{
		}
		return $ret;
	}

	/**
	 * @relations
	 */
	public function relations()
	{
		return [
			'postTags' => array('HAS_MANY', TagPostTag::className(), ''),
			'posts' => array('HAS_MANY', TagPost::className(), ''),
			'tagSubscribes' => array('HAS_MANY', TagTagSubscribe::className(), ''),
			'createdBies' => array('HAS_MANY', TagUser::className(), ''),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostTags()
	{
		return $this->hasMany(TagPostTag::className(), ['tagId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPosts()
	{
		return $this->hasMany(TagPost::className(), ['id' => 'postId'])->viaTable('PostTag', ['tagId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTagSubscribes()
	{
		return $this->hasMany(TagTagSubscribe::className(), ['tagId' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreatedBies()
	{
		return $this->hasMany(TagUser::className(), ['id' => 'createdById'])->viaTable('TagSubscribe', ['tagId' => 'id']);
	}

	/**
	 * @inheritdoc
	 * @return \app\models\Tag|null ActiveRecord instance matching the condition, or `null` if nothing matches.
	 */
	public static function findOne($condition)
	{
		return parent::findOne($condition);
	}
}

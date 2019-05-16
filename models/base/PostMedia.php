<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as PostMediaBaseActiveRecord;
use \app\models\Post as PostMediaPost;

/**
 * This is the base-model class for table "PostMedia".
 *
 * @property integer $id
 * @property integer $postId
 * @property string $filename
 * @property integer $sort
 *
 * @property PostMediaPost $post
 */
class PostMedia extends PostMediaBaseActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'PostMedia';
	}

	/**
	 * @inheritdoc
	 */
	public static function label($n = 1)
	{
		return Yii::t("app", "{n, plural, =1{PostMedia} other{PostMedias}}", ["n" =>  $n]);
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
			[['postId', 'filename'], 'required'],
			[['postId', 'sort'], 'integer'],
			[['filename'], 'string', 'max' => 64],
			[['postId'], 'exist', 'skipOnError' => true, 'targetClass' => PostMediaPost::className(), 'targetAttribute' => ['postId' => 'id']]
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
			'postId' => Yii::t('models', 'Post ID'),
			'filename' => Yii::t('models', 'Filename'),
			'sort' => Yii::t('models', 'Sort'),
		];
		if($this->getScenario() == 'formModel')
		{
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
			'post' => array('BELONGS_TO', PostMediaPost::className(), 'postId'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPost()
	{
		return $this->hasOne(PostMediaPost::className(), ['id' => 'postId']);
	}
}

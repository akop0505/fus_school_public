<?php

namespace app\models;

use Yii;
use app\models\common\TraitCityForm;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "Institution".
 */
class Institution extends base\Institution
{
	use TraitCityForm {
		rules as traitRules;
	}

	public $logo;
	public $header;
	public $schoolBanner = false;

	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'name';
	}

	/**
	 * @inheritdoc
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById', '!updatedAt', '!updatedById', '!numLikes', '!hasLatestPhoto', '!fbPageToken', 'schoolBanner'], 'safe'];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = $this->traitRules();
		$r[] = [['fbAppId', 'fbPageId', 'fbAppSecret'], 'required', 'on' => 'fbConnect'];
		return $r;
	}

	/**
	 * @inheritdoc
	 */
	public function transactions()
	{
		return [
			'default' => self::OP_INSERT
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if($insert)
		{
			$channel = new Channel();
			$channel->institutionId = $this->id;
			$channel->name = $this->name;
			$channel->isSystem = 1;
			$channel->hasPhoto = 1;
			if(!$channel->save()) throw new BadRequestHttpException(Yii::t('app', 'Institution channel save failed!'));
		}
	}

	/**
	 * Return institution url
	 * @param bool $scheme
	 * @return string
	 */
	public function getUrl($scheme = false)
	{
		return Url::toRoute(['/site/school', 'item' => $this], $scheme);
	}

	/**
	 * Return institution's channel
	 * @return Channel
	 */
	public function getChannel()
	{
		return Channel::findOne(['institutionId' => $this->id]);
	}

	/**
	 * Implementations should return base url path to view images in; by default constructed from model and attribute names
	 * @param string $attributeName
	 * @return string
	 */
	public function getPicBaseUrl($attributeName)
	{
		if($attributeName[0] == 'h' && $attributeName[1] == 'a' && $attributeName[2] == 's') $attributeName = substr($attributeName, 3);
		$baseClass = strtolower(substr(strrchr('\\'. get_called_class(), '\\'), 1));
		return Yii::getAlias('@web/images/upload/'. $baseClass .'/'. strtolower($attributeName) .'/'. $this->id .'/');
	}
	
	/**
	 * Return image filename
	 * @param string $attributeName
	 * @param bool $forDisplay
	 * @return string
	 */
	public function getPicName($attributeName, $forDisplay = false)
	{
		$ext = '';
		//if($forDisplay && $this->hasAttribute('updatedAt')) $ext = '-'. str_replace([' ', ':', '-'], '', !is_object($this->updatedAt) ? $this->updatedAt : date('YmdHis'));
		return '1'. $ext .'.jpg';
	}

	public function updatingNumberOfViews()
	{
		if(Yii::$app->user->id && Yii::$app->user->identity->institutionId == $this->id && Yii::$app->user->can('SchoolAdmin')) return false;
		else
		{
			$try = UserViews::checkAndUpdateViews($this->id, UserViews::VIEWTYPE_SCHOOL);
			if($try) return true;
			else return false;
		}
	}
}

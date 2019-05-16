<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

/**
 * SchoolSearchForm is the model behind the school search form.
 */
class SchoolSearchForm extends Model
{
	public $schoolName;
	public $stateId;
	public $cityId;
	public $zip;

	/**
	 * @return array customized attribute labels
	 */
	public function attributeLabels()
	{
		return [
			'schoolName' => Yii::t('app', 'School Name'),
			'stateId' => Yii::t('app', 'State'),
			'cityId' => Yii::t('app', 'City'),
			'zip' => Yii::t('app', 'Zip'),
		];
	}
}
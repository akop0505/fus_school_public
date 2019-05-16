<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "FeaturedChannel".
 */
class FeaturedChannel extends base\FeaturedChannel
{
	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return null;
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'numPost' => Yii::t('app', 'Number of Posts'),
			)
		);
	}
}

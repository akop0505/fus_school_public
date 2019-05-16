<?php

namespace app\i18n;

use Zelenin\yii\modules\I18n\components\I18N as BaseI18N;

class I18N extends BaseI18N
{
	public function init()
	{
		parent::init();
		$this->translations['app']['enableCaching'] = \Yii::$app->db->enableSchemaCache;
		$this->translations['app']['cachingDuration'] = 3600;
	}
}
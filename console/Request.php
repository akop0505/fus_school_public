<?php

namespace app\console;

class Request extends \yii\console\Request
{
	public function getUserIP()
	{
		return '127.0.0.1';
	}
}
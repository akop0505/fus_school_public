<?php

namespace app\common;

/**
 * Class Pagination
 * @package app\common
 */
class Pagination extends \yii\data\Pagination
{
	public function __construct($config = [])
	{
		if(!isset($config['forcePageParam'])) $config['forcePageParam'] = false;
		if(!isset($config['defaultPageSize']) && isset($config['pageSize'])) $config['defaultPageSize'] = $config['pageSize'];
		parent::__construct($config);
	}
}
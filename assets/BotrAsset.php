<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class BotrAsset
 * @package app\assets
 */
class BotrAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web/js';
	public $js = [
		'botr.js'
	];
	public $depends = [
		'yii\web\YiiAsset'
	];
}

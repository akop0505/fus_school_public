<?php
namespace app\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
	public $sourcePath = '@bower/bootstrap/dist';
	public $css = [
		'css/bootstrap.min.css',
	];
	public $js = [
		'js/bootstrap.min.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
}

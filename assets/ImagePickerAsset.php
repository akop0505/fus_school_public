<?php
namespace app\assets;

use yii\web\AssetBundle;

class ImagePickerAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/imagepicker/imagepicker.css',
	];
	public $js = [
		'js/imagepicker/imagepicker.js',
	];
	public $depends = [
		'yii\web\YiiAsset',
	];
	
}

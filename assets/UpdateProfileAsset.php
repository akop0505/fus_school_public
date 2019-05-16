<?php
namespace app\assets;

use yii\web\AssetBundle;

class UpdateProfileAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/updateProfile.css',
	];
	public $depends = [
		'yii\web\YiiAsset',
		'app\assets\BootstrapAsset',
		'app\assets\ImagePickerAsset',
	];
}

<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAdminAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/site.css',
		'css/fixes-20160605.css',
		'//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,600,600italic,700,700italic,900,900italic',
		'css/minified/fontawesome.min.css',
	];
	public $js = [
		/*'js/jquery.flexslider.min.js',
		'js/perfectscrollbar.jquery.min.js',
		'js/required.js',*/
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}

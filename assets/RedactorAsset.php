<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class RedactorAsset
 * @package app\assets
 */
class RedactorAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web/js/redactor';
	public $css = [
		'redactor.min.css'
	];
	public $js = [
		'jquery.spellchecker.min.js',
		'redactor.min.js',
		'plugins/alignment/alignment.js',
		'plugins/limiter/limiter.js',
		'plugins/counter/counter.js',
		'plugins/spellchecker.js',
	];
	public $depends = [
		'yii\web\YiiAsset'
	];
}

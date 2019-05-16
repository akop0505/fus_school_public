<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class DropZoneAsset
 * @package app\assets
 */
class DropZoneAsset extends AssetBundle
{
	public $sourcePath = '@vendor/enyo/dropzone/dist/min';

	public $css = [
		'basic.min.css',
		'dropzone.min.css',
	];
	public $js = [
		'dropzone.min.js',
	];

	public $depends = [
		'yii\web\YiiAsset',
	];
}

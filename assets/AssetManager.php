<?php
namespace app\assets;

use yii\web\AssetManager as YiiAssetManager;

class AssetManager extends YiiAssetManager
{
	/**
	 * Add closure config support
	 * @inheritdoc
	 */
	public function getBundle($name, $publish = true)
	{
		if(isset($this->bundles[$name]) && $this->bundles[$name] instanceof \Closure)
		{
			return $this->bundles[$name] = $this->loadBundle($name, $this->bundles[$name](), $publish);
		}
		return parent::getBundle($name, $publish);
	}
}
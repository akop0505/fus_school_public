<?php
namespace app\filters;

use yii;
use yii\base\ActionFilter;
use app\controllers\common\BaseController;

class PreloadModelFilter extends ActionFilter
{
	public function beforeAction($action)
	{
		$mainId = null;
		if(isset($_GET['id'])) $mainId = $_GET['id'];
		elseif(isset($_POST['id'])) $mainId = $_POST['id'];
		// ID present, we are in update / delete / similar
		if($mainId !== null)
		{
			/**
			 * @var BaseController $controller
			 */
			$controller = $action->controller;
			$controller->loadModel($mainId);
		}
		return true;
	}
} 
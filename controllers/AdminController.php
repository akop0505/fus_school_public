<?php

namespace app\controllers;

use app\controllers\common\BaseAdminController;

/**
 * AdminController
 */
class AdminController extends BaseAdminController
{
	/**
	 * Lists all City models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		return $this->redirect(['user/index']);
	}
	
	/**
	 * @inheritdoc
	 */	 	
	protected function findModel($id)
	{
		return null;
	}
}

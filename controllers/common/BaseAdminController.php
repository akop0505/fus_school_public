<?php
namespace app\controllers\common;

use app\auth\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class BaseAdminController - every page that requires admin privileges should extend this controller
 * @package app\controllers\common
 */
abstract class BaseAdminController extends BaseUserController
{
	public $layout = '@app/views/layouts/admin';

	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'actions' => ['index', 'delete', 'create', 'update'],
						'allow' => true,
						'roles' => ['ContentAdmin'],
					],
				],
			],
		]);
	}
}
<?php
namespace app\controllers\common;

use app\auth\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Class BaseUserController - every page that requires logged in user should extend this controller
 * @package app\controllers\common
 */
abstract class BaseUserController extends BaseController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => false,
						'roles' => ['?'],
					],
				]
			],
		]);
	}
}
<?php
namespace app\auth;

use Yii;
use yii\base\Action;
use yii\filters\AccessControl as yiiAccessControl;
use yii\base\Controller;
use yii\filters\AccessRule;
use yii\web\ForbiddenHttpException;

/**
 * Class AccessControl
 * @package app\auth
 */
class AccessControl extends yiiAccessControl
{
	/**
	 * @var array name-value pairs that would be passed to business rules associated
	 * with the tasks and roles assigned to the user.
	 */
	public $params = [];
	/**
	 * @var callback a callback that will be called if the access should be denied
	 * to the current user. If not set, [[denyAccess()]] will be called.
	 *
	 * The signature of the callback should be as follows:
	 *
	 * ~~~
	 * function ($item, $action)
	 * ~~~
	 *
	 * where `$item` is this item name, and `$action` is the current [[Action|action]] object.
	 */
	public $denyCallback;
	/**
	 * @var string
	 */
	private $separator = '.';

	/**
	 * @param Controller|Action $component
	 * @return string
	 */
	private function getItemName($component)
	{
		return strtr($component->getUniqueId(), '/', $this->separator);
	}

	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * You may override this method to do last-minute preparation for the action.
	 *
	 * @param Action $action the action to be executed.
	 * @return boolean whether the action should continue to be executed.
	 */
	public function beforeAction($action)
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->getUser();
		$request = Yii::$app->getRequest();
		/* @var $rule AccessRule */
		// classic rules
		foreach($this->rules as $rule)
		{
			$allow = $rule->allows($action, $user, $request);
			if($allow) return true;
			elseif($allow === false)
			{
				if(isset($rule->denyCallback)) call_user_func($rule->denyCallback, $rule, $action);
				elseif(isset($this->denyCallback)) call_user_func($this->denyCallback, $rule, $action);
				else $this->denyAccess($user);
				return false;
			}
		}
		// advanced privileges
		if($user->can($this->getItemName($action->controller) . $this->separator .'*', $this->params)) return true;
		$itemName = $this->getItemName($action);
		//die($itemName);
		if($user->can($itemName, $this->params)) return true;

		if(isset($this->denyCallback)) call_user_func($this->denyCallback, $itemName, $action);
		else $this->denyAccess($user);
		return false;
	}

	/**
	 * Denies the access of the user.
	 * The default implementation will redirect the user to the login page if he is a guest;
	 * if the user is already logged, a 403 HTTP exception will be thrown.
	 *
	 * @param User $user the current user
	 * @throws ForbiddenHttpException if the user is already logged in.
	 */
	protected function denyAccess($user)
	{
		if($user->getIsGuest()) $user->loginRequired();
		else throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
	}
}
<?php
namespace app\auth;

use yii\web\IdentityInterface;
use yii\web\User as BaseUser;
use yii\db\Expression;

/**
 * Class User
 * @package app\auth
 *
 * @property \app\models\User|null $identity
 */
class User extends BaseUser
{
	/**
	 * @var array
	 */
	public $superAdmins = [];

	/**
	 * @inheritdoc
	 * @param \app\models\User|IdentityInterface|null $identity
	 */
	protected function afterLogin($identity, $cookieBased, $duration)
	{
		parent::afterLogin($identity, $cookieBased, $duration);
		if($identity)
		{
			$identity->setScenario(self::EVENT_AFTER_LOGIN);
			$identity->setAttribute('lastLogin', new Expression('UTC_TIMESTAMP()'));
			$identity->save(false);
		}
	}

	/**
	 * Return if current user is superadmin
	 * @return bool
	 */
	public function getIsSuperAdmin()
	{
		if($this->isGuest) return false;
		return isset($this->superAdmins[$this->identity->username]);
	}

	/**
	 * Return if current user has permission for given op
	 * @param string $operation
	 * @param array $params
	 * @param bool $allowCaching
	 * @return bool
	 */
	public function can($operation, $params = [], $allowCaching = true)
	{
		if($this->getIsSuperAdmin()) return true;
		return parent::can($operation, $params, $allowCaching);
	}
}
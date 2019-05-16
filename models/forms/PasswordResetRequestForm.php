<?php
namespace app\models\forms;

use Yii;
use app\models\User;
use yii\base\Model;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
	public $email;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email']
		];
	}

	/**
	 * Sends an email with a link, for resetting the password.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail()
	{
		/* @var $user User */
		$user = User::findOne(['email' => $this->email]);
		if($user && $user->status == User::STATUS_ACTIVE)
		{
			if(!$user->isPasswordResetTokenValid($user->passwordResetToken))
			{
				$user->generatePasswordResetToken();
				$user->updateAttributes(['passwordResetToken' => $user->passwordResetToken]);
			}
			$user->sendPasswordResetEmail();
			return true;
		}
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return ['email' => Yii::t('app', 'Email')];
	}
}
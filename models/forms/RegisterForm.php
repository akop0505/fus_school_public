<?php

namespace app\models\forms;

use app\services\mailchimp\MailChimpService;
use Yii;
use app\models\Institution;
use app\models\User;
use yii\base\Model;

/**
 * RegisterForm is the model behind the register form.
 */
class RegisterForm extends Model
{
	public $username;
	public $email;
	public $password;
	public $school;
	public $verifyCode;
	public $firstName;
	public $lastName;
	public $passwordRepeat;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// name, email, subject and body are required
			[['username', 'email', 'password', 'firstName', 'lastName'], 'required'],
			// email has to be a valid email address
			['email', 'email'],
			[['username', 'email'], 'filter', 'filter' => 'trim'],
			[['username'], 'string', 'min' => 1, 'max' => 255],
			[['firstName', 'lastName'], 'string', 'min' => 1, 'max' => 64],
			[['password'], 'safe'],
			['passwordRepeat', 'required'],
			['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => "Passwords don't match"],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha'],
			[['email', 'username'], 'unique', 'targetClass' => 'app\models\User'],
			[['school'], 'exist', 'skipOnError' => true, 'targetClass' => Institution::className(), 'targetAttribute' => ['school' => 'id']],
		];
	}

	/**
	 * @return array customized attribute labels
	 */
	public function attributeLabels()
	{
		return [
			'verifyCode' => Yii::t('app', 'Verification Code'),
			'username' => Yii::t('app', 'Username'),
			'email' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'school' => Yii::t('app', 'School'),
		];
	}

	/**
	 * @return int - 0 if failed 1 ok with school -1 ok without school
	 */
	public function register()
	{
		if($this->validate())
		{
			$model = new User();
			$model->username = $this->username;
			$model->email = $this->email;
			$model->password = $model->passwordConfirm = $this->password;
			$model->institutionId = $this->school;
			$model->firstName = $this->firstName;
			$model->lastName = $this->lastName;
			if($model->institutionId)
			{
				$school = Institution::findOne($model->institutionId);
				if(!$school) return false;
				$model->timeZoneId = $school->city->timeZoneId;
			}
			//Set timezone to default America/New_York
			else $model->timeZoneId = 21;

			if($model->save())
			{
                $subscribe = Yii::$app->request->post('subscribe');
                if(isset($subscribe) && (integer)$subscribe === 1)
                {
                    (new MailChimpService())->subscribeUser($model);
                }
				Yii::$app->authManager->assign(Yii::$app->authManager->getRole('BasicUser'), $model->id);
				if(!$model->institutionId)
				{
					$email = $model->sendConfirmEmail();
					if($email) return -1;
				}
				else return 1;
			}
			//else die(print_r($model,1));
		}
		return 0;
	}
}

<?php

namespace app\models\forms;

use app\models\State;
use Yii;
use yii\base\Model;

/**
 * ContactFusFooForm is the model behind the contact form.
 */
class ContactFusFooForm extends Model
{
	public $firstName;
	public $lastName;
	public $highSchool;
	public $stateId;
	public $schoolPosition;
	public $email;
	public $phone;
	public $message;
	public $verifyCode;
	public $referral;

	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// name, email, subject and body are required
			[['firstName', 'lastName', 'highSchool', 'stateId', 'schoolPosition', 'email'], 'required'],
			[['firstName', 'lastName'], 'string', 'min' => 1, 'max' => 64],
			[['highSchool', 'schoolPosition', 'referral', 'message'], 'string', 'min' => 1, 'max' => 255],
			['phone', 'safe'],
			// email has to be a valid email address
			['email', 'email'],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha'],
		];
	}

	/**
	 * @return array customized attribute labels
	 */
	public function attributeLabels()
	{
		return [
			'stateId' => Yii::t('app', 'State'),
			'verifyCode' => 'Verification Code',
			'referral' => 'How did you find out about Fusfoo?'
		];
	}

	/**
	 * Sends an email to the specified email address using the information collected by this model.
	 * @param  string  $email the target email address
	 * @return boolean whether the model passes validation
	 */
	public function contact($email)
	{
		if($this->validate())
		{
			$state = State::findOne($this->stateId);

			$body = 'Name: ' .  $this->firstName . ' ' . $this->lastName ."\n";
			$body .= 'E-mail: ' . $this->email ."\n";
			$body .= 'High School: ' .  $this->highSchool ."\n";
			$body .= 'State: ' .  $state->name ."\n";
			$body .= 'School position: ' . $this->schoolPosition ."\n";
			if($this->phone) $body .= 'Phone: ' .  $this->phone ."\n";
			if($this->referral) $body .= 'Referral: ' .  $this->referral ."\n";
			if($this->message) $body .= 'Message: ' .  $this->message;

			Yii::$app->mailer->compose()
				->setTo($email)
				->setReplyTo([$this->email => $this->firstName . ' ' . $this->lastName])
				->setFrom(Yii::$app->params['adminEmail'])
				->setSubject('New Contact')
				->setTextBody(strip_tags($body))
				->send();
			return true;
		}
		return false;
	}
}

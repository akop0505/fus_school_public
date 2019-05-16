<?php

namespace app\models\forms;

use Yii;

/**
 * SchoolContactForm is the model behind the SchoolContactForm form.
 */
class SchoolContactForm extends ContactForm
{
	/**
	 * Sends an email to the specified email address using the information collected by this model.
	 * @param  string  $email the target email address
	 * @return boolean whether the model passes validation
	 */
	public function contact($email)
	{
		if($this->validate())
		{
			Yii::$app->mailer->compose()
				->setTo($email)
				->setReplyTo([$this->email => $this->name])
				->setFrom(Yii::$app->params['adminEmail'])
				->setSubject($this->subject)
				->setTextBody($this->body)
				->send();
			return true;
		}
		return false;
	}
}
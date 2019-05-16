<?php

namespace app\components;

use yii\base\Component;

/**
 * Component EmailNotification
 */
class EmailNotification extends Component
{
    /**
     * Action on post event
     */
    const EVENT_ACTION_ON_POST	= 'post_event';
    /**
     * Notification action type constants
     */
    const ACTION_LIKE		    = 'like';
    const ACTION_SUBSCRIBER	    = 'subscriber';
    const ACTION_FAVORITE	    = 'favorite';
    const ACTION_UNLIKE		    = 'unlike';
    const ACTION_UNSUBSCRIBER	    = 'unsubscriber';
    const ACTION_UNFAVORITE	    = 'unfavorite';
    const ACTION_TOGGLE_NATIONAL    = 'toggle-national';
    /**
     * View file for email
     */
    public $viewFile;
    
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
	parent::__construct([
	    'viewFile'	    => 'postNotificationEmail',
	]);
    }
    
    /**
     * Sends email notification to user
     * @param \app\models\User $user
     * @param \app\models\Post|null $post
     * @param string $actionType
     * @return 
     */
    public function sendNotification($user, $post = null, $actionType)
    {
	$mailData = [
	    'user'	    => $user,
	    'actionType'    => $actionType
	];
	/*
	 * If email notification is sent for toggle favorite
	 */
	if($actionType === static::ACTION_TOGGLE_NATIONAL) {
	    $this->viewFile = 'toggleNationalNotificationEmail';
	}
	
	/*
	 * If email notification is not sent for subscribe
	 */
	if(!is_null($post))
	    $mailData['post'] = $post;
	
	\Yii::$app->mailer->compose($this->viewFile, $mailData)
		->setFrom(\Yii::$app->params['noReplyEmail'])
		->setTo($user->email)
		->setSubject(\Yii::t('app', 'Post Notification'))
		->send();
    }
}
?>


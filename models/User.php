<?php
namespace app\models;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\helpers\Url;

class User extends base\User implements IdentityInterface
{
	const STATUS_ACTIVE = 'active';
	const STATUS_PENDING = 'pending';
	const STATUS_DELETED = 'deleted';
	const STATUS_ARCHIVED = 'archived';

	/**
	 * @var null|string
	 */
	public $_socialProvider = null;
	/**
	 * @var null
	 */
	public $_socialProfile = null;
	/**
	 * @var null|string
	 */
	public $password = null;
	/**
	 * @var null|string
	 */
	public $photoUpload = null;
	/**
	 * @var null|string
	 */
	public $passwordConfirm = null;
	
	/**
	 * $var bool
	 */
	public $avatarLoaded = 1;

	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'username';
	}

	public function __toString()
	{
		$ret = $this->getUserFullName();
		if(!$ret) $ret = $this->username;
		return $ret;
	}

	/**
	 * @return string
	 */
	public function getUserFullName()
	{
		if(!$this->firstName && !$this->lastName) return '';
		return $this->firstName . ' ' . $this->lastName;
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios['socialRegister'] = $scenarios['default'];
		if(Yii::$app->getUser()->can('UserAdmin'))
		{
			$scenarios['create'] = $scenarios['passReset'] = $scenarios['update'] = $scenarios['default'];
		}
		else
		{
			$scenarios['update'] = $scenarios['passReset'] = [
				'!username',
				'!authKey',
				'!passwordHash',
				'!email',
				'!createdAt',
				'!updatedAt',
				'!emailVerified',
				'!status',
				'!lastLogin',
				'!passwordResetToken',
				'!institutionId',
				'!hasPhoto',
				'firstName',
				'lastName',
				'mobilePhone',
				'password',
				'isMale',
				'dateOfBirth',
				'timeZoneId',
				'about',
				'avatar_name'
			];
			$scenarios['create'] = ['username', 'email', 'password', 'institutionId'];
		}
		return $scenarios;
	}

	/**
	 * @inheritdoc
	 */
	public function transactions()
	{
		return [
			'socialRegister' => self::OP_INSERT,
			'default' => self::OP_INSERT
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);

		if($insert)
		{
			$channel = new Channel();
			$channel->userId = $this->id;
			$channel->name = ($this->getUserFullName() != '' ? $this->getUserFullName() : $this->username) . ' ' . $this->id;
			$channel->isSystem = 1;
			$channel->createdById = $channel->updatedById = 1;
			if(!$channel->save()) throw new BadRequestHttpException();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		$r = [
			[['username', 'authKey', 'passwordHash', 'email', 'createdAt', 'updatedAt', 'timeZoneId'], 'required'],
			[['emailVerified', 'isMale', 'timeZoneId', 'institutionId'], 'integer'],
			[['status', 'about'], 'string'],
			[['createdAt', 'updatedAt', 'lastLogin', 'dateOfBirth', 'hasPhoto'], 'safe'],
			[['username', 'email', 'mobilePhone', 'avatar_name'], 'string', 'max' => 255],
			[['authKey'], 'string', 'max' => 32],
			[['passwordHash', 'passwordResetToken'], 'string', 'max' => 128],
			[['firstName', 'lastName'], 'string', 'max' => 64],
			[['username'], 'unique'],
			[['email'], 'unique'],
			[['institutionId'], 'exist', 'skipOnError' => true, 'targetClass' => Institution::className(), 'targetAttribute' => ['institutionId' => 'id']],
			[['timeZoneId'], 'exist', 'skipOnError' => true, 'targetClass' => TimeZone::className(), 'targetAttribute' => ['timeZoneId' => 'id']],
			['status', 'in', 'range' => [
				static::STATUS_PENDING,
				static::STATUS_ACTIVE,
				static::STATUS_DELETED,
				static::STATUS_ARCHIVED
			]],
			[['username', 'email'], 'filter', 'filter' => 'trim'],
			[['email'], 'email'],
			[['about'], 'safe', 'when' => function($model) {
				/**
				 * @var User $model
				 */
				return (!$model->isNewRecord && $model->institutionId && $model->getScenario() != 'passReset');
			}],
			[['hasPhoto'], 'validateHasPhoto'],
			[['username'], 'string', 'min' => 1, 'max' => 255],
			[['password', 'passwordConfirm'], 'safe'],
			//[['photoUpload'], 'file', 'extensions' => 'jpg', 'mimeTypes' => 'image/jpeg'],
			[['dateOfBirth', 'password', 'passwordConfirm'], 'default', 'value' => null],
			['password', 'validatePasswordChange'],
			[['email', 'username'], 'unique'],
		];
		if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
		return $r;
	}
	
	/**
	 * Check if photo or avatar has been selected
	* @param string $attribute the attribute currently being validated
	* @param mixed $params the value of the "params" given in the rule
	* @param \yii\validators\InlineValidator $validator related InlineValidator instance.
	* This parameter is available since version 2.0.11.
	*/
	public function validateHasPhoto($attribute, $params, $validator)
	{
	   if(!$this->avatarLoaded && !$this->hasPhoto && !$this->isNewRecord && empty($this->avatar_name) && $this->institutionId && $this->getScenario() != 'passReset')
	   {
		$this->addError($attribute, 'Photo or avatar should be selected');
	   }
	}

	/**
	 * Confirm passwords match
	 */
	public function validatePasswordChange()
	{
		if($this->password != $this->passwordConfirm)
		{
			$this->addError('password', 'Passwords are not the same.');
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!updatedAt', '!hasPhoto'], 'safe'];
	}

	/**
	 * @inheritdoc
	 * @return User
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_ARCHIVED]]);
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if(!static::isPasswordResetTokenValid($token))
		{
			return null;
		}

		return static::findOne(
			[
				'passwordResetToken' => $token,
				'status' => self::STATUS_ACTIVE,
			]
		);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return boolean
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if(empty($token))
		{
			return false;
		}

		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		$parts = explode('_', $token);
		$timestamp = (int)end($parts);

		return $timestamp + $expire >= time();
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * @inheritdoc
	 */

	public function getAuthKey()
	{
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return boolean if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		// unfinished reset password
		if($this->passwordHash == 'xxx') return false;
		return Yii::$app->security->validatePassword($password, $this->passwordHash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->passwordHash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->authKey = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->passwordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->passwordResetToken = null;
	}

	/**
	 * @return bool
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate())
		{
			if($this->isNewRecord)
			{
				$this->generateAuthKey();
				if(!$this->username)
				{
					$this->username = $this->email;
				}
				$this->setPassword($this->password);
			}
			else
			{
				if($this->password)
				{
					$this->setPassword($this->password);
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Sends confirm email mail
	 * @return bool
	 */
	public function sendConfirmEmail()
	{
		return Yii::$app->mailer->compose(['html' => 'confirmEmail'], ['user' => $this])
			->setFrom(Yii::$app->params['noReplyEmail'])
			->setTo($this->email)
			->setSubject(Yii::t('app', 'Fusfoo - Account activation'))
			->send();
	}

	/**
	 * Sends confirm account activation
	 * @return bool
	 */
	public function sendAccountActivationConfirmation()
	{
		return Yii::$app->mailer->compose(['html' => 'accountActivated'], ['user' => $this])
			->setFrom(Yii::$app->params['noReplyEmail'])
			->setTo($this->email)
			->setSubject(Yii::t('app', 'Fusfoo - Account activated'))
			->send();
	}

	/**
	 * Sends password reset mail
	 * @return bool
	 */
	public function sendPasswordResetEmail()
	{
		return Yii::$app->mailer->compose(['html' => 'passwordResetEmail'], ['user' => $this])
			->setFrom(Yii::$app->params['noReplyEmail'])
			->setTo($this->email)
			->setSubject(Yii::t('app', 'Password reset subject'))
			->send();
	}

	/**
	 * Return image path relative to web folder
	 * @return bool|string
	 */
	public function getPhotoPath()
	{
		if(!$this->id) return false;
		$path = Yii::getAlias('@webroot/images/upload/user');
		if(!$path) return false;
		return $path. '/' . $this->id . '.jpg';
	}

	/**
	 * Return full image path relative to web folder
	 * @return bool|string
	 */
	public function getPhotoURL()
	{
		$path = Yii::getAlias('@web/images/upload/user');
		if(!$path) return false;
		return $path. '/' . $this->id . '.jpg';
	}
	
	/**
	 * Return list of available avatars
	 * @return array
	 */
	public function getAvatarList()
	{
		$path = Yii::getAlias('@webroot/images/avatars');
		$avatarsList = [];
		foreach(glob($path.'/*.*') as $filename){
		    $filenameArr = explode('/', $filename);
		    $avatarsList[] = $filenameArr[count($filenameArr)-1];
		}
		return $avatarsList;
	}
	
	/**
	 * Return link to avatars
	 * @return string
	 */
	public function getAvatarLink()
	{
		return Yii::getAlias('@web/images/avatars/');
	}
	
	/**
	 * Return users's avatar link (either picture or preselected existing avatar)
	 * @return string
	 */
	public function getAvatar($hasPhoto=0, $avatarName='')
	{
	    $avatar = '';
	    if(!empty($avatarName)) {
		$avatar = $this->getAvatarLink() . $avatarName;
	    }
	    elseif($hasPhoto) {
		$avatar = $this->getPicBaseUrl('hasPhoto') . $this->getPicName('hasPhoto', true);
	    }
	    return $avatar;
	}

	/**
	 * Return array of available statuses
	 * @return array
	 */
	public function getStatusForDropdown()
	{
		return [self::STATUS_PENDING => Yii::t('app', 'Pending'), self::STATUS_ACTIVE => Yii::t('app', 'Active'), self::STATUS_DELETED => Yii::t('app', 'Deleted'), self::STATUS_ARCHIVED => Yii::t('app', 'Archived')];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		$r = parent::attributeLabels();
		$r['isMale'] = Yii::t('app', 'Gender');
		$r['password'] = Yii::t('app', 'Password');
		$r['hasPhoto'] = Yii::t('app', 'Photo');
		return $r;
	}

	/**
	 * Return thumb image filename
	 * @param string $imageName
	 * @return string
	 */
	public function getThumbFilename($imageName)
	{
		return substr_replace($imageName, '_thumb', strrpos($imageName, '.'), 0);
	}

	/**
	 * Return image dimensions [w, h]
	 * @return array
	 */
	public function getImageDimensions()
	{
		return [65, 65];
	}

	/**
	 * Return user url
	 * @param bool $scheme
	 * @return string
	 */
	public function getUrl($scheme = false)
	{
		return Url::toRoute(['/site/profile', 'item' => $this], $scheme);
	}

	/**
	 * @inheritdoc
	 */
	protected function getPicPlaceholder($attributeName)
	{
		return 'user.png';
	}

	/**
	 * Return number of post for user to watch later
	 * @return int|string
	 */
	public function countWatchLater()
	{
		return PostLater::find()->innerJoinWith('post', false)->where(['PostLater.createdById' => $this->id, 'Post.isActive' => 1])->count();
	}

	/**
	 * Return number of post user liked
	 * @return int|string
	 */
	public function countPostLike()
	{
		return PostLike::find()->innerJoinWith('post', false)->where(['PostLike.createdById' => $this->id, 'Post.isActive' => 1])->count();
	}

	/**
	 * Return number of post user has for favorite
	 * @return int|string
	 */
	public function countPostFavorite()
	{
		return PostFavorite::find()->innerJoinWith('post', false)->where(['PostFavorite.createdById' => $this->id, 'Post.isActive' => 1])->count();
	}

	/**
	 * Return number of post user created
	 * @return int|string
	 */
	public function countPosts()
	{
		return Post::find()->where(['createdById' => $this->id, 'isActive' => 1])->count();
	}

	/**
	 * Return number of subscriptions for user
	 * @return int|string
	 */
	public function countSubscriptions()
	{
		$countSubscriptionsSchools = Institution::find()->innerJoinWith('channels.channelSubscribes', false)->where(['ChannelSubscribe.createdById' => $this->id, 'Institution.isActive' => 1])->andWhere(['IS NOT', 'institutionId', NULL])->count();
		$countSubscriptionsUsers = User::find()->innerJoinWith('channels1.channelSubscribes', false)->with('institution')->where(['ChannelSubscribe.createdById' => $this->id, 'User.status' => User::STATUS_ACTIVE])->andWhere(['IS NOT', 'userId', NULL])->count();
		$countSubscriptionsTags = TagSubscribe::find()->innerJoinWith('tag', false)->where(['TagSubscribe.createdById' => $this->id, 'Tag.isActive' => 1])->count();

		return $countSubscriptionsSchools + $countSubscriptionsUsers + $countSubscriptionsTags;
	}

	/**
	 * Return sum activity for user
	 * @return int|string
	 */
	public function countActivity()
	{
		return UserActivity::find()->where(['createdById' => $this->id])->count();
	}

	/**
	 * Return number of all counts
	 * @return int|string
	 */
	public function getUserCounts()
	{
		$posts = $this->countPosts();
		$later = $this->countWatchLater();
		$like = $this->countPostLike();
		$favorite =  $this->countPostFavorite();
		$subscriptions = $this->countSubscriptions();

		return [
			'posts' => $posts,
			'later' => $later,
			'like' => $like,
			'favorite' => $favorite,
			'subscriptions' => $subscriptions,
			'all' => $posts + $later + $like + $favorite + $subscriptions
		];
	}

	public function updatingNumberOfViews()
	{
		if(Yii::$app->user->id && $this->id == Yii::$app->user->id) return false;
		else
		{
			$try = UserViews::checkAndUpdateViews($this->id, UserViews::VIEWTYPE_PROFILE);
			if($try) return true;
			else return false;
		}
	}

    /**
     * @return string
     */
    public function getUserInitials()
	{
		$tmp = '';
		if($this->firstName) $tmp .= $this->firstName[0];
		if($this->lastName) $tmp .= $this->lastName[0];
		return $tmp;
	}
}

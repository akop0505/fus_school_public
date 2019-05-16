<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use app\models\Channel;
use app\models\PostChannel;
use app\components\EmailNotification;
/**
 * This is the model class for table "Post".
 */
class Post extends base\Post
{
	public $tag;
	public $channel;

	/**
	 * @inheritdoc
	 */
	public function representingColumn()
	{
		return 'title';
	}

	public function attributeLabels()
	{
		return array_merge(
			parent::attributeLabels(),
			array(
				'hasHeaderPhoto' => Yii::t('app', 'Header Photo'),
				'hasThumbPhoto' => Yii::t('app', 'Thumb Photo'),
				'isActive' => Yii::t('app', 'Published'),
				'isApproved' => Yii::t('app', 'Approved'),
			)
		);
	}

	/**
	 * @inheritdoc
	 */
	protected function getUnsafeRule()
	{
		return [['!createdAt', '!createdById', '!updatedAt', '!updatedById', '!hasHeaderPhoto', '!hasThumbPhoto', '!views', '!video', '!isNational', '!datePublished'], 'safe'];
	}

	public function rules()
	{
		$r = [
			[['title', 'postText', 'createdAt', 'createdById', 'updatedAt', 'updatedById'], 'required'],
			[['postText'], 'string'],
			[['views', 'isActive', 'isApproved', 'createdById', 'updatedById', 'isNational'], 'integer'],
			[['hasHeaderPhoto', 'hasThumbPhoto'], 'required', 'on' => 'insert'],
			[['createdAt', 'updatedAt', 'dateToBePublished'], 'safe'],
			[['title', 'video'], 'string', 'max' => 255],
			['title', 'filter', 'filter' => function ($value) {
				return trim(strip_tags($value));
			}],
			[['approvedById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approvedById' => 'id']],
			[['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['createdById' => 'id']],
			[['updatedById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updatedById' => 'id']]
		];
		if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
		return $r;
	}

	/**
	 * Return post url
	 * @param bool $scheme
	 * @return string
	 */
	public function getUrl($scheme = false)
	{
		return Url::toRoute(['/site/article', 'item' => $this], $scheme);
	}

	/**
	 * @inheritdoc
	 */
	public function transactions()
	{
		return [
			'default' => self::OP_INSERT | self::OP_UPDATE | self::OP_DELETE
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
			$userActivity = new UserActivity();
			$userActivity->activityType = 'Post';
			$userActivity->activityTypeFk = $this->id;
			if(!$userActivity->save()) throw new BadRequestHttpException(Yii::t('app', 'User Activity save failed!'));
		}

		$postData = Yii::$app->request->post('Post', []);
		if(isset($postData['tag']))
		{
			$tagList = $postData['tag'];
			PostTag::deleteAll(['postId' => $this->id]);
			if($tagList)
			{
				$tagIdUsed = [];
				foreach($tagList as $attr)
				{
					$attr = strip_tags($attr);
					$checkIfTagExist = Tag::findOne(['name' => $attr]);
					if(!$checkIfTagExist)
					{
						$tag = new Tag();
						$tag->name = $attr;
						if(!$tag->save()) throw new BadRequestHttpException(Yii::t('app', 'Post tag save failed!'));
						$tagId = $tag->id;
					}
					else $tagId = $checkIfTagExist->id;

					if(isset($tagIdUsed[$tagId])) continue;
					else
					{
						$postTag = new PostTag();
						$postTag->tagId = $tagId;
						$postTag->postId = $this->id;
						if(!$postTag->save()) throw new BadRequestHttpException(Yii::t('app', 'Post tag save failed!'));
						else $tagIdUsed[$tagId] = $tagId;
					}
				}
			}
		}

		if(isset($postData['channel']))
		{
			$channelList = $postData['channel'];
			$checkIfSystem = PostChannel::find()->joinWith('channel')->where(['postId' => $this->id])->all();
			foreach($checkIfSystem as $one)
			{
				if($one->channel->isSystem == 1) continue;
				else
				{
					$num = PostChannel::deleteAll(['channelId' => $one->channelId, 'postId' => $this->id]);
					Channel::updateAllCounters(['numPosts' => -$num], ['id' => $one->channelId]);
				}
	
			}
	
			if($channelList)
			{
				$channelIdUsed = [];
				foreach($channelList as $attr)
				{
					$channel = Channel::findOne(['id' => $attr]);
					if($channel->isSystem == 1) continue;

					if(isset($channelIdUsed[$attr])) continue;
					else
					{
						$postChannel = new PostChannel();
						$postChannel->channelId = $attr;
						$postChannel->postId = $this->id;
						if(!$postChannel->save()) throw new BadRequestHttpException(Yii::t('app', 'Post channel save failed!'));
						else
						{
							$channelIdUsed[$postChannel->channelId] = $postChannel->channelId;
							Channel::updateAllCounters(['numPosts' => 1], ['id' => $postChannel->channelId]);
						}
					}

				}
			}
		}

		$this->fullTextContent = $this->getFulltextContent(isset($tagList) ? $tagList : null);
		$this->updateAttributes(['fullTextContent' => $this->fullTextContent]);
		$this->updateDefaultChannels();
	}

	/**
	 * Get fulltext content
	 * @param null|array $tagList
	 * @return string
	 */
	public function getFulltextContent($tagList = null)
	{
		$content = $this->title;
		$content .= '|';
		$content .= strip_tags(htmlspecialchars_decode($this->postText));
		$content .= '|';
		$content .= $this->createdBy->firstName . ' ' . $this->createdBy->lastName;
		$content .= '|';
		$content .= $this->createdBy->institution->name;
		if(isset($tagList) && $tagList && is_array($tagList))
		{
			$content .= '|'. implode(' ', $tagList);
		}
		return $content;
	}

	/**
	 * @inheritdoc
	 */
	public function afterDelete()
	{
		parent::afterDelete();

		$userActivity = new UserActivity();
		$userActivity->activityType = 'Post';
		$userActivity->activityTypeFk = $this->id;
		$userActivity->isRemove = 1;
		if(!$userActivity->save()) throw new BadRequestHttpException(Yii::t('app', 'User Activity save failed!'));
	}

	/**
	 * Assign/remove from default channels
	 * @throws BadRequestHttpException
	 * @throws \Exception
	 */
	public function updateDefaultChannels()
	{
		$institutionChannelId = Channel::findOne(['institutionId' => $this->createdBy->institutionId]);
		$userChannelId = Channel::findOne(['userId' => $this->createdById]);

		$findHomeLatest = PostChannel::findOne(['postId' => $this->id, 'channelId' => Channel::CHANNEL_HOME_LATEST]);
		$findInstitutionChannel = PostChannel::findOne(['postId' => $this->id, 'channelId' => $institutionChannelId->id]);
		$findUserChannel = PostChannel::findOne(['postId' => $this->id, 'channelId' => $userChannelId->id]);

		if($this->isActive == 1)
		{
			/*if(!$findHomeLatest)
			{
				$latestChannel = new PostChannel();
				$latestChannel->postId = $this->id;
				$latestChannel->channelId = Channel::CHANNEL_HOME_LATEST;
				if(!$latestChannel->save()) throw new BadRequestHttpException(Yii::t('app', 'Post channel save failed!'));
				else Channel::updateAllCounters(['numPosts' => 1], ['id' => Channel::CHANNEL_HOME_LATEST]);
			}*/
			if(!$findInstitutionChannel)
			{
				$institutionChannel = new PostChannel();
				$institutionChannel->postId = $this->id;
				$institutionChannel->channelId = $institutionChannelId->id;
				if(!$institutionChannel->save()) throw new BadRequestHttpException(Yii::t('app', 'Post channel save failed!'));
				else Channel::updateAllCounters(['numPosts' => 1], ['id' => $institutionChannelId->id]);
			}
			if(!$findUserChannel)
			{
				$userChannel = new PostChannel();
				$userChannel->postId = $this->id;
				$userChannel->channelId = $userChannelId->id;
				if(!$userChannel->save()) throw new BadRequestHttpException(Yii::t('app', 'Post channel save failed!'));
				else Channel::updateAllCounters(['numPosts' => 1], ['id' => $userChannelId->id]);
			}
		}
		else
		{
			if($findHomeLatest)
			{
				$findHomeLatest->delete();
				Channel::updateAllCounters(['numPosts' => -1], ['id' => Channel::CHANNEL_HOME_LATEST]);
			}
			if($findInstitutionChannel)
			{
				$findInstitutionChannel->delete();
				Channel::updateAllCounters(['numPosts' => -1], ['id' => $institutionChannelId->id]);
			}
			if($findUserChannel)
			{
				$findUserChannel->delete();
				Channel::updateAllCounters(['numPosts' => -1], ['id' => $userChannelId->id]);
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function getPicPlaceholder($attributeName)
	{
		return $attributeName == 'hasThumbPhoto' ? 'post.jpg' : 'cover.jpg';
	}

	/**
	 * Trims article text
	 * @param int $numberOfCharacters
	 * @return string
	 */
	public function getDescription($numberOfCharacters)
	{
		$line = trim(substr(html_entity_decode(strip_tags($this->postText)), 0, $numberOfCharacters));
		if(preg_match('/^.{1,'.  $numberOfCharacters .'}\b/s', $line, $match))
		{
			$line = $match[0];
		}
		return $line . '...';
	}

	public function updatingNumberOfViews()
	{
		if(Yii::$app->user->id && $this->createdById == Yii::$app->user->id) return false;
		else
		{
			$try = UserViews::checkAndUpdateViews($this->id, UserViews::VIEWTYPE_POST);
			if($try)
			{
				$this->updateCounters(['views' => 1]);
				return true;
			}
			else return false;
		}
	}

	/**
	 * @return bool|string
	 */
	public function getGalleryBasePath()
	{
		return Yii::getAlias('@webroot/images/upload/post/gallery/'. $this->id .'/');
	}

	/**
	 * @return bool|string
	 */
	public function getGalleryBaseUrl()
	{
		return Yii::getAlias('@web/images/upload/post/gallery/'. $this->id .'/');
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
	 * Return thumbnail dimensions [w, h]
	 * @return array
	 */
	public function getThumbDimensions()
	{
		return [130, 130];
	}

	/**
	 * Return max gallery image dimensions [w, h]
	 * @return array
	 */
	public function getGalleryMaxDimensions()
	{
		return [1000, 1000];
	}

	/**
	 * @return string
	 */
	public function addGalleryToContent()
	{
		$addGallery = '';
		$gallery = PostMedia::find()->where(['postId' => $this->id])->all();
		if($gallery)
		{
			$addGallery = '<div class="post-slider"><ul class="slides clr">';
			foreach($gallery as $picture)
			{
				$addGallery .= '<li style="background-image: url(' . $this->getGalleryBaseUrl() . $picture->filename .');"></li>';
			}
			$addGallery .= 	'</ul></div>';
		}

		return str_ireplace('[GALLERY]', $addGallery, $this->postText);
	}

	/**
	 * Check permissions for editing/deleting post and uploading/removing pictures from gallery
	 * @throws ForbiddenHttpException
	 */
	public function checkPostPermission()
	{
		if($this->createdBy->institutionId != Yii::$app->user->identity->institutionId && !Yii::$app->user->can('ContentAdmin')) throw new ForbiddenHttpException();
		if(!Yii::$app->user->can('SchoolAdmin') && (!Yii::$app->user->can('SchoolAuthor') || $this->createdById != Yii::$app->user->id)) throw new ForbiddenHttpException();
	}
	
	/**
	 * Prepares data to pass sendNotification() to send email for like and favorite
	 * @param int $postId
	 * @param string $actionType
	 */
	public static function sendLikeFavoriteEmailNotification($postId, $actionType)
	{
	    $emailNotification  = new EmailNotification();
	    $post		= Post::findOne(['id' => $postId]);
	    $postAuthor		= $post->createdBy;
	    
	    $emailNotification->sendNotification($postAuthor, $post, $actionType);
	    
	    /*$postChannel	= PostChannel::findOne(['postId' => $postId]);
	    $channel		= Channel::findOne(['id' => $postChannel->channelId]);
	    if(!is_null($channel->institution)) {
		$schoolAdmin	= $channel->institution->updatedBy;
	    } else {
		$schoolAdmin	= $channel->createdBy;
	    }
	    if(isset($schoolAdmin) && !is_null($schoolAdmin))
		$emailNotification->sendNotification($schoolAdmin, $post, $actionType);
	     */
	}
	
	/**
	 * Prepares data to pass sendNotification() to send email for subscribe
	 * @param int $channelId
	 * @param string $actionType
	 */
	public static function sendSubscribeEmailNotification($channelId, $actionType)
	{
	    $emailNotification  = new EmailNotification();
	    $channel		= Channel::findOne(['id' => $channelId]);
	    if(!is_null($channel->user)) {
		$user		= $channel->user;
	    } elseif(!is_null($channel->institution)) {
		$user		= $channel->institution->updatedBy;
	    }
	    if(isset($user) && !is_null($user))
		$emailNotification->sendNotification($user, null, $actionType);
	}
	
	/**
	 * Prepares data to pass sendNotification() to send email for toggle national
	 * @param int $postId
	 */
	public static function sendNationalNotification($postId)
	{
	    $emailNotification	    = new EmailNotification();
	    $post		    = Post::findOne(['id' => $postId]);
	    $postAuthor		    = $post->createdBy;
	    
	    $emailNotification->sendNotification($postAuthor, $post, EmailNotification::ACTION_TOGGLE_NATIONAL);
	    
	    $nationalInstitution    = Institution::findOne(4);
	    $schoolAdmin	    = $nationalInstitution->createdBy;
	    if(isset($schoolAdmin) && !is_null($schoolAdmin))
		$emailNotification->sendNotification($schoolAdmin, $post, EmailNotification::ACTION_TOGGLE_NATIONAL);
	}
}

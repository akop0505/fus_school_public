<?php

namespace app\controllers;

use Yii;
use app\controllers\common\BaseUserController;
use app\models\ChannelSubscribe;
use app\models\Institution;
use app\models\Channel;
use app\models\InstitutionLike;
use app\models\Post;
use app\models\PostFavorite;
use app\models\PostLater;
use app\models\PostLike;
use app\models\PostRepost;
use app\models\TagSubscribe;
use app\models\User;
use app\components\EmailNotification;

/**
 * Class AjaxActionsController
 * @package app\controllers
 */
class AjaxActionsController extends BaseUserController
{
	/**
	 * @inheritdoc
	 */
	protected function findModel($id)
	{
	}

	public function actionSubscribe()
	{
		$response = '0';
		if($_POST['subscribed'] == 0)
		{
			$channelUnSubscribe = ChannelSubscribe::findOne(['channelId' => $_POST['channelId'], 'createdById' => Yii::$app->user->id]);
			if(!$channelUnSubscribe)
			{
				$channelSubscribe = new ChannelSubscribe();
				$channelSubscribe->channelId = $_POST['channelId'];
				if($channelSubscribe->save())
				{
				    Channel::updateAllCounters(['numSubscribers' => 1], ['id' => $channelSubscribe->channelId]);
				    Post::sendSubscribeEmailNotification($_POST['channelId'], EmailNotification::ACTION_SUBSCRIBER);
				    $response = '1';
				}
			}
			else $response = '1';
		}
		elseif($_POST['subscribed'] == 1)
		{
			$channelUnSubscribe = ChannelSubscribe::findOne(['channelId' => $_POST['channelId'], 'createdById' => Yii::$app->user->id]);
			if($channelUnSubscribe)
			{
				if($channelUnSubscribe->delete())
				{
				    Channel::updateAllCounters(['numSubscribers' => -1], ['id' => $channelUnSubscribe->channelId]);
				    //Post::sendSubscribeEmailNotification($_POST['channelId'], EmailNotification::ACTION_UNSUBSCRIBER);
				    $response = '1';
				}
			}
			else $response = '1';
		}
		echo $response;
	}

	public function actionLike()
	{
		$response = '0';
		if($_POST['liked'] == 0)
		{
			$postUnLike = PostLike::findOne(['postId' => $_POST['postId'], 'createdById' => Yii::$app->user->id]);
			if(!$postUnLike)
			{
				$postLike = new PostLike();
				$postLike->postId = $_POST['postId'];
				if($postLike->save())
				{
				    Post::sendLikeFavoriteEmailNotification($_POST['postId'], EmailNotification::ACTION_LIKE);
				    $response = '1';
				}
			}
			else $response = '1';
		}
		elseif($_POST['liked'] == 1)
		{
			$postUnLike = PostLike::findOne(['postId' => $_POST['postId'], 'createdById' => Yii::$app->user->id]);
			if($postUnLike)
			{
				if($postUnLike->delete())
				{
				    //Post::sendLikeFavoriteEmailNotification($_POST['postId'], EmailNotification::ACTION_UNLIKE);
				    $response = '1';
				}
			}
			else $response = '1';
		}
		echo $response;
	}

	public function actionSchoolLike()
	{
		$response = '0';
		if($_POST['liked'] == 0)
		{
			$postUnLike = InstitutionLike::findOne(['institutionId' => $_POST['institutionId'], 'createdById' => Yii::$app->user->id]);
			if(!$postUnLike)
			{
				$postLike = new InstitutionLike();
				$postLike->institutionId = $_POST['institutionId'];
				if($postLike->save())
				{
					$model = Institution::findOne($postLike->institutionId);
					$model->updateCounters(['numLikes' => 1]);
					$response = '1';
				}
			}
			else $response = '1';
		}
		elseif($_POST['liked'] == 1)
		{
			$postUnLike = InstitutionLike::findOne(['institutionId' => $_POST['institutionId'], 'createdById' => Yii::$app->user->id]);
			if($postUnLike)
			{
				if($postUnLike->delete())
				{
					$model = Institution::findOne($postUnLike->institutionId);
					$model->updateCounters(['numLikes' => -1]);
					$response = '1';
				}
			}
			else $response = '1';
		}
		echo $response;
	}

	public function actionFavorite()
	{
		$response = '0';
		if($_POST['favorite'] == 0)
		{
			$removeFromFavorite = PostFavorite::findOne(['postId' => $_POST['postId'], 'createdById' => Yii::$app->user->id]);
			if(!$removeFromFavorite)
			{
			    $postFavorite = new PostFavorite();
			    $postFavorite->postId = $_POST['postId'];
			    if($postFavorite->save())
			    {
				Post::sendLikeFavoriteEmailNotification($_POST['postId'], EmailNotification::ACTION_FAVORITE);
				$response = '1';
			    }
			}
			else $response = '1';
		}
		elseif($_POST['favorite'] == 1)
		{
			$removeFromFavorite = PostFavorite::findOne(['postId' => $_POST['postId'], 'createdById' => Yii::$app->user->id]);
			if($removeFromFavorite)
			{
			    if($removeFromFavorite->delete())
			    {
				//Post::sendLikeFavoriteEmailNotification($_POST['postId'], EmailNotification::ACTION_UNFAVORITE);
				$response = '1';
			    }
			}
			else $response = '1';
		}
		echo $response;
	}

	public function actionLater()
	{
		$response = '0';
		if($_POST['later'] == 0)
		{
			$removeFromWatchLater = PostLater::findOne(['postId' => $_POST['postId'], 'createdById' => Yii::$app->user->id]);
			if(!$removeFromWatchLater)
			{
				$postLater = new PostLater();
				$postLater->postId = $_POST['postId'];
				if($postLater->save())
				{
					$response = '1';
				}
			}
			else $response = '1';
		}
		elseif($_POST['later'] == 1)
		{
			$removeFromWatchLater = PostLater::findOne(['postId' => $_POST['postId'], 'createdById' => Yii::$app->user->id]);
			if($removeFromWatchLater)
			{
				if($removeFromWatchLater->delete())
				{
					$response = '1';
				}
			}
			else $response = '1';
		}
		echo $response;
	}

	public function actionRepost()
	{
		$response = '0';

		if($_POST['postId'] != 0)
		{
			$canApprovePost = Yii::$app->user->can('ApprovePost');
			$canApproveVideo = Yii::$app->user->can('ApproveVideo');

			$canApprove = false;
			$checkForVideo = Post::findOne($_POST['postId']);
			if(($checkForVideo->video && $canApproveVideo) || (!$checkForVideo->video && $canApprovePost)) $canApprove = true;

			/**
			 * @var User $user
			 */
			$user = Yii::$app->user->identity;
			$postRepost = PostRepost::findOne(['institutionId' => $user->institutionId, 'postId' => $_POST['postId']]);
			if(!$postRepost)
			{
				$rePost = new PostRepost();
				$rePost->postId = $_POST['postId'];
				$rePost->institutionId = $user->institutionId;
				if($canApprove) $rePost->isApproved = 1;
				if($rePost->save()) $response = '1';
			}
			else $response = '1';
		}
		echo $response;
	}

	public function actionTag()
	{
		$response = '0';
		if($_POST['tag'] == 0)
		{
			$tagUnSubscribe = TagSubscribe::findOne(['tagId' => $_POST['tagId'], 'createdById' => Yii::$app->user->id]);
			if(!$tagUnSubscribe)
			{
				$tagSubscribe = new TagSubscribe();
				$tagSubscribe->tagId = $_POST['tagId'];
				if($tagSubscribe->save()) $response = '1';
			}
			else $response = '1';
		}
		elseif($_POST['tag'] == 1)
		{
			$tagUnSubscribe = TagSubscribe::findOne(['tagId' => $_POST['tagId'], 'createdById' => Yii::$app->user->id]);
			if($tagUnSubscribe)
			{
				if($tagUnSubscribe->delete()) $response = '1';
			}
			else $response = '1';
		}
		echo $response;
	}
}
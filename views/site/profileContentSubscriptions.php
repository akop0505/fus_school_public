<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Post;

/* @var $this yii\web\View */
/* @var app\models\User[] $subscriptionUsers */
/* @var app\models\Institution[] $subscriptionSchools */
/* @var app\models\Tag[] $tags */

$urlChannelSubscribe = Url::to(['ajax-actions/subscribe']);

foreach($subscriptionSchools as $one)
{
	$channel = $one->getChannel();
	$post = Post::find()->innerJoinWith('postChannels', false)->with('createdBy.institution')->where(['channelId' => $channel->id, 'isActive' => 1])->limit(8)->orderBy('id desc')->all();
	echo '<header class="clr"><h2><a href="' . $one->getUrl() .'">' . Html::encode($one->name) .'</a></h2></header>';
	echo $this->render('profileContentSubscriptionsArticle', ['data' => $post]);
}

foreach($subscriptionUsers as $one)
{
	$post = Post::find()->where(['isActive' => 1, 'createdById' => $one->id])->limit(8)->orderBy('id desc')->all();
	echo '<header class="clr"><h2><a href="' . $one->getUrl() .'">' . Html::encode($one->getUserFullName()) .'</a></h2></header>';
	echo $this->render('profileContentSubscriptionsArticle', ['data' => $post]);
}

foreach($tags as $one)
{
	$post = Post::find()->innerJoinWith('postTags', false)->where(['tagId' => $one->id, 'isActive' => 1])->limit(8)->orderBy('id desc')->all();
	echo '<header class="clr"><h2><a href="' . $one->getUrl() .'">' . Html::encode($one->name) . '</a></h2></header>';
	echo $this->render('profileContentSubscriptionsArticle', ['data' => $post]);
}
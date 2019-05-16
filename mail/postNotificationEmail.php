<?php

use yii\helpers\Html;
use app\components\EmailNotification;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $post app\models\Post|null */
/* @var $actionType string */

if(isset($post))
    $postLink = Yii::$app->urlManager->createAbsoluteUrl(['/site/article', 'item' => $post]);
?>

<p>
    <?php if($actionType == EmailNotification::ACTION_FAVORITE): ?>
	Good news! Someone has made your post a favorite.
	<br/>
	<?= Html::a($post->title, $postLink); ?>
    <?php endif ?>
	
    <?php if($actionType == EmailNotification::ACTION_LIKE): ?>
	Hi! Someone has liked your post. Great job. Keep posting to get more people reading and watching!
	<br/>
	<?= Html::a($post->title, $postLink); ?>
    <?php endif ?>
	
    <?php if($actionType == EmailNotification::ACTION_SUBSCRIBER): ?>
	Wow! Your posts are really interesting. Someone has subscribed to your feed!
    <?php endif ?>
    <br/>
    <br/>
    Fusfoo
    <br/>
    <br/>
    <?= Html::a('www.fusfoo.com', 'www.fusfoo.com'); ?>

<?php /*
<p>
    Hello <?= $user->firstName . ' ' . $user->lastName; ?>,
    <br/>
    <br/>
    <?php if(isset($post)): ?>
	Your post: <?= Html::a($post->title, $postLink) ?> has received a new <?= $actionType; ?>
    <?php else: ?>
	You have received a new <?= $actionType; ?>
    <?php endif; ?>
    <br/>
    <br/>
    Fusfoo
    <br/>
    <br/>
    <?= Html::a('www.fusfoo.com', 'www.fusfoo.com'); ?>
</p>
*/ ?>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $post app\models\Post|null */
/* @var $actionType string */

$postLink = Yii::$app->urlManager->createAbsoluteUrl(['/site/article', 'item' => $post]);
?>

<p>
    Congratulations! Your post is really great. We have promoted it to our Fusfoo National Channel. Make sure you look for your badge and share!
    <br/>
    <br/>
    <?= Html::a($post->title, $postLink); ?>
</p>

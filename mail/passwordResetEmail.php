<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'u' => $user->getId(), 'key' => $user->passwordResetToken]);
?>
	Follow the link below to reset your password:
<?= Html::a(Html::encode($resetLink), $resetLink) ?>
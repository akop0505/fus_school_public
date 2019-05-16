<?php

use yii\helpers\Html;
/**
 * @var \app\models\User $user
 */
	$link = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm-email', 'u' => $user->getId(), 'key' => $user->getAuthKey()]);
?>
	Please click this link to activate your Fusfoo account:
<br><?= Html::a(Html::encode($link), $link); ?>
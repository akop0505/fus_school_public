<?php

use yii\helpers\Html;
/**
 * @var \app\models\User $user
 */
$link = Yii::$app->urlManager->createAbsoluteUrl(['site/login']);
?>
Your account on Fusfoo has been activated.
<br><?= Html::a(Html::encode($link), $link); ?>

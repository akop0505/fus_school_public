<?php

use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var app\models\User $model */
/* @var app\models\User $targetModel */

		echo Alert::widget();
		echo $this->render('/user/updateProfile', [
			'model' => $targetModel,
            'subscribed' => $subscribed
		]);

$this->registerJs(<<<JSCLIP
	$(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'userProfile-init');
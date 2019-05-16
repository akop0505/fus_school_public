<?php

use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var app\models\User $model */
/* @var app\models\Institution $targetModel */

		echo Alert::widget();
		echo $this->render('/institution/update', [
			'model' => $targetModel
		]);

$this->registerJs(<<<JSCLIP
	$(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'schoolProfile-init');
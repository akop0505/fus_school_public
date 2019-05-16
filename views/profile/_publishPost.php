<?php

use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var app\models\User $model */
/* @var app\models\Post $targetModel */

		echo Alert::widget();
		if(!isset($hideForm) || !$hideForm) echo $this->render('/post/create', ['model' => $targetModel]);


$this->registerJs(<<<JSCLIP
    $(document).scrollTop(250);
JSCLIP
	, $this::POS_READY, 'publish-post-init');
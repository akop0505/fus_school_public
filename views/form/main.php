<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\widgets\Alert;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\LoginForm */

$this->title = 'Form';

?>
<!-- start:header -->
<header id="header">
    <!-- start:cover -->
    <div class="cover" style="background-image: url('<?=  Url::to($imagePath) ?>');">

        <!-- start:top -->
        <?= $this->render('/site/top'); ?>
        <!-- end:top -->
    </div>
    <!-- end:cover -->
</header>
<!-- end:header -->

<!-- start:main -->
<main id="main">
    <!-- start:post -->
    <article class="post">
        <!-- start:cnt -->
        <div class="cnt clr">
            <?= $formContent ?>
            <div class="user-form">
            </div>
        </div>
        <!-- end:cnt -->

    </article>
    <!-- end:post -->

</main>
<!-- end:main -->
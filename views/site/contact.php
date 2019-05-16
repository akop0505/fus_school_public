<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\forms\ContactForm */

$this->title = 'Contact';
?>
<!-- start:header -->
<header id="header">
    <!-- start:cover -->
    <div class="cover">
        <!-- start:top -->
        <?= $this->render('top'); ?>
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

            <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

                <div class="alert alert-success">
                    Thank you for contacting us. We will respond to you as soon as possible.
                </div>

            <?php else: ?>
                <section class="section user-form">
                    <h2><?= Html::encode($this->title) ?></h2>
                    <p>
                        <?= Yii::t('app', ' If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.') ?>
                    </p>
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'contact-form',
                        'options' => ['class' => 'autoFocus']]);
                    ?>
                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </section>

            <?php endif; ?>
        </div>
        <!-- end:cnt -->

    </article>
    <!-- end:post -->

</main>
<!-- end:main -->
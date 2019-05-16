<?php

use app\models\User;
use app\assets\RedactorAsset;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Form */
/* @var $form yii\widgets\ActiveForm */

$oldScenario = $model->getScenario();
$model->prepareForForm();
RedactorAsset::register($this);
if($oldScenario == 'insert') $model->setScenario($oldScenario);


?>
<div class="wrapper">
    <div class="form-block-1">
        <h1><?= Html::decode($contest->title); ?></h1>
        <div class="content">
            <?= Html::decode($contest->content); ?>
        </div>
    </div>
    <?php if( Yii::$app->user->isGuest ) {
        ?>
        <div class="form-block-2">
            <div class="post-form">
                <?php $form = ActiveForm::begin(['options'=> ['enctype'=> 'multipart/form-data']]); ?>

                <?= $form->field($model, 'first_name')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'last_name')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

                <?= $form->field($model, 'school')->textInput(['maxlength' => 255]) ?>

                <div  class="agree">
                    <?= Html::checkbox('agree', false , [
                        'required'=>true
                    ]) ?>
                    <?= Html::label('By clicking this checkbox you inducate that you agree to our <a href="'.Url::to(["site/content","contentType"=>"privacy-policy"]).'" target="_blank">privacy policy</a>',
                        'agree', ['class' => 'label']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php
    }
    ?>
</div>


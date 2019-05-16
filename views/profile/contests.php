<?php

use app\models\User;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user app\models\User */

$superAdmin = Yii::$app->user->can('SuperAdmin');

$isSchoolAdmin = Yii::$app->user->can('SchoolAdmin');
$canPublish = Yii::$app->user->can('SchoolAuthor');
$canApprovePost = Yii::$app->user->can('ApprovePost');
$canApproveVideo = Yii::$app->user->can('ApproveVideo');
$this->title = Yii::t('app', 'Contests');
?>
    <div class="post-index">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?php if($canPublish): ?>
            <p>
                <?= Html::a(Yii::t('app', 'Create contest'), ['/contest/create'], ['class' => 'btn btn-success create-post']) ?>
            </p>
        <?php endif; ?>

        <?php Pjax::begin();?>
        <?php try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'hover' => true,
                'krajeeDialogSettings' => ['overrideYiiConfirm' => false],
                'columns' => [
                    'title',
                    [
                        'attribute' => 'content',
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'isActive',
                        'format' => 'boolean',
                        'filter' => $searchModel->dropdownYesNo()
                    ],
                    [
                        'attribute' => 'datePublished',
                        'filter' => DateControl::widget([
                            'model' => $searchModel,
                            'attribute' => 'datePublished'
                        ]),
                        'content' => function ($item) {
                            return Yii::$app->formatter->asDate($item->datePublished, 'short');
                        }
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{forms} {view} {update} {deleteForm}',
                        'buttons' => [
                            'forms' => function ($url, $model, $key) use ($canApprovePost, $canApproveVideo) {
                                if (!$model->isActive && (($model->video && !$canApproveVideo) || (!$model->video && !$canApprovePost))) return false;
                                return Html::a('<span>'.$model->waitingToApproveCount.' </span><span class="fa fa-user"></span>', Yii::$app->urlManager->createUrl(['profile/form', 'id'=>$model->id]), ['title' => Yii::t('app', 'Forms to approve'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'view' => function ($url, $model, $key) use ($canApprovePost, $canApproveVideo) {
                                if (!$model->isActive && (($model->video && !$canApproveVideo) || (!$model->video && !$canApprovePost))) return false;
                                return Html::a('<span class="fa fa-eye"></span>', Yii::$app->urlManager->createUrl(['/form/store/', 'id' => $model->id]), ['title' => Yii::t('app', 'View'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'update' => function ($url, $model, $key) use ($canPublish, $isSchoolAdmin) {
                                if (!$isSchoolAdmin && (!$canPublish || $model->isActive)) return false;
                                return Html::a('<span class="fa fa-pencil"></span>', Yii::$app->urlManager->createUrl(['/contest/update', 'id' => $model->id]), ['title' => Yii::t('app', 'Edit'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'deleteForm' => function ($url, $model, $key) use ($superAdmin) {
                                if (!$superAdmin) return false;
                                return Html::a('<span class="fa fa-trash-o"></span>', Yii::$app->urlManager->createUrl(['/contest/delete', 'id' => $model->id]), [
                                    'title' => Yii::t('app', 'Delete'),
                                    'class' => 'option',
                                    'data' => [
                                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                                    'data-pjax' => '0'
                                ]);
                            },
                            'promote' => function ($url, $model, $key) use ($canApproveVideo, $canApprovePost) {
                                if ($model->isActive || (($model->video && !$canApproveVideo) || (!$model->video && !$canApprovePost))) return false;
                                return Html::a('<span class="fa fa-level-up"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-post', 'id' => $model->id, 'promote' => 1]), ['title' => Yii::t('app', 'Publish'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'demote' => function ($url, $model, $key) use ($canApproveVideo, $canApprovePost) {
                                if (!$model->isActive || (($model->video && !$canApproveVideo) || (!$model->video && !$canApprovePost))) return false;
                                return Html::a('<span class="fa fa-level-down"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-post', 'id' => $model->id, 'promote' => 0]), ['title' => Yii::t('app', 'Unpublish'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'approve' => function ($url, $model, $key) use ($superAdmin) {
                                if (!$superAdmin) return false;
                                return Html::a('<span class="fa fa-thumbs-o-up"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-form-approve', 'id' => $model->id, 'approve' => 1]), ['title' => Yii::t('app', 'Approve'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'refuse' => function ($url, $model, $key) use ($canApprovePost, $canApproveVideo) {
                                if (($model->video && !$canApproveVideo) || (!$model->video && !$canApprovePost)) return false;
                                return Html::a('<span class="fa fa-thumbs-down"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-form-approve', 'id' => $model->id, 'approve' => 0]), ['title' => Yii::t('app', 'Refuse'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'video' => function ($url, $model, $key) use ($canPublish, $isSchoolAdmin) {
                                if (!$canPublish || ($model->isActive && !$isSchoolAdmin)) return false;
                                return Html::a('<span class="fa fa-upload"></span>', Yii::$app->urlManager->createUrl(['/post/upload-video', 'id' => $model->id]), ['title' => Yii::t('app', 'Upload Video'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'delvideo' => function ($url, $model, $key) use ($canPublish, $isSchoolAdmin) {
                                if (!$model->video || !$canPublish || ($model->isActive && !$isSchoolAdmin)) return false;
                                return Html::a('<span class="fa fa-eraser"></span>', Yii::$app->urlManager->createUrl(['/post/remove-video', 'id' => $model->id]), ['title' => Yii::t('app', 'Remove Video'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'gallery' => function ($url, $model, $key) use ($canPublish, $isSchoolAdmin) {
                                if (!$canPublish || ($model->isActive && !$isSchoolAdmin)) return false;
                                return Html::a('<span class="fa fa-camera"></span>', Yii::$app->urlManager->createUrl(['/profile/gallery', 'postId' => $model->id]), ['title' => Yii::t('app', 'Gallery'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                        ]
                    ],
                ],
            ]);
        } catch (Exception $e) {
            echo $e->getMessage();
        } ?>
        <?php Pjax::end(); ?>

    </div>

<?php
$this->registerJs(<<<JSCLIP
   $(document).scrollTop(250);
JSCLIP
    , $this::POS_READY, 'post-init');
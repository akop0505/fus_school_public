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
$this->title = Yii::t('app', 'Contest`s forms');
?>
    <div class="post-index">

        <h1><?= Html::encode($this->title) ?></h1>

        <?php Pjax::begin();
        try {
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'export' => false,
                'hover' => true,
                'krajeeDialogSettings' => ['overrideYiiConfirm' => false],
                'columns' => [
                    'first_name',
                    'last_name',
                    'email',
                    [
                        'attribute' => 'isApproved',
                        'format' => 'boolean',
                        'filter' => $searchModel->dropdownYesNo()
                    ],
                    [
                        'attribute' => 'contest_id',
                        'label' => 'Contest',
                        'content' => function ($item) {
                            return Html::encode($item->contest);
                        }
                    ],
                    [
                        'attribute' => 'approvedById',
                        'label' => 'Approved By',
                        'filter' => User::dropDownFind(['institutionId' => $user->institutionId]),
                        'content' => function ($item) {
                            return Html::encode($item->approvedBy);
                        }
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{deleteForm} {approve} {refuse}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) use ($canApprovePost, $canApproveVideo) {
                                if ((!$canApproveVideo) || (!$canApprovePost)) return false;
                                return Html::a('<span class="fa fa-eye"></span>', Yii::$app->urlManager->createUrl(['/site/article-form', 'id' => $model->id]), ['title' => Yii::t('app', 'View'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'update' => function ($url, $model, $key) use ($canPublish, $isSchoolAdmin) {
                                if (!$isSchoolAdmin && (!$canPublish || $model->isApproved)) return false;
                                return Html::a('<span class="fa fa-pencil"></span>', Yii::$app->urlManager->createUrl(['/form/edit-form', 'id' => $model->id]), ['title' => Yii::t('app', 'Edit'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'deleteForm' => function ($url, $model, $key) use ($superAdmin) {
                                if (!$superAdmin) return false;
                                return Html::a('<span class="fa fa-trash-o"></span>', Yii::$app->urlManager->createUrl(['/form/delete-form', 'id' => $model->id]), [
                                    'title' => Yii::t('app', 'Delete'),
                                    'class' => 'option',
                                    'data' => [
                                        'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                                    'data-pjax' => '0'
                                ]);
                            },
                            'approve' => function ($url, $model, $key) use ($superAdmin) {
                                if ($model->isApproved || (!$superAdmin)) return false;
                                return Html::a('<span class="fa fa-thumbs-o-up"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-form-approve', 'id' => $model->id, 'approve' => 1]), ['title' => Yii::t('app', 'Approve'), 'class' => 'option', 'data-pjax' => '0']);
                            },
                            'refuse' => function ($url, $model, $key) use ($canApprovePost, $canApproveVideo) {
                                if (!$model->isApproved || (!$canApproveVideo) || (!$canApprovePost)) return false;
                                return Html::a('<span class="fa fa-thumbs-down"></span>', Yii::$app->urlManager->createUrl(['/profile/toggle-form-approve', 'id' => $model->id, 'approve' => 0]), ['title' => Yii::t('app', 'Refuse'), 'class' => 'option', 'data-pjax' => '0']);
                            }
                        ]
                    ],
                ],
            ]);
        } catch (Exception $e) {
            throw new \yii\web\ForbiddenHttpException();
        }
        Pjax::end(); ?>

    </div>

<?php
$this->registerJs(<<<JSCLIP
   $(document).scrollTop(250);
JSCLIP
    , $this::POS_READY, 'post-init');
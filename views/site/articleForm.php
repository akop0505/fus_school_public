<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var app\models\Post $model */

$this->title .= ' - '. $model->title;
if($model->video) $this->registerJsFile(Yii::$app->params['jwp.player.domain'] .'/players/'. $model->video .'-wtzqEpA3.js');
?>
    <!-- start:header -->
    <header id="header">

        <!-- start:cover -->
        <div class="cover" style="background-image: url('<?= $model->getPicBaseUrl('hasHeaderPhoto') . $model->getPicName('hasHeaderPhoto', true); ?>');">

            <!-- start:top -->
            <?= $this->render('top'); ?>
            <!-- end:top -->

            <?php if($model->video != ''): ?>
                <!-- start:video -->
                <div class="video">

                    <!-- start:cnt -->
                    <div class="cnt clr">

                        <!-- start:play -->
                        <a href="#" class="play toggle">
                        </a>
                        <!-- end:play -->

                        <!-- start:player -->
                        <div class="player">
                            <div class="flex" id="botr_<?= $model->video ?>_wtzqEpA3_div"></div>
                            <a href="#" class="close toggle" rel="botr_<?= $model->video ?>_wtzqEpA3_div">
                                <i class="fa fa-times-circle"></i>
                            </a>
                        </div>
                        <!-- end:player -->

                    </div>
                    <!-- end:cnt -->

                </div>
                <!-- end:video -->
            <?php endif;?>

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

                <!-- start:header -->
                <header class="header">

                    <h1><?= Html::encode($model->title); ?></h1>

                    <!-- start:details -->
                    <div class="details clr">
                        <ul class="left clr">
                            <li>
                                from <?= Html::encode($model->first_name)." ".Html::encode($model->last_name); ?>
                            </li>
                            <li>|</li>
                            <li>
                                <?= Html::encode($model->first_name); ?>
                            </li>
                            <li>|</li>
                            <!--<li>
							<?php if($daysCreatedAgo) { ?>
							<span><?= $daysCreatedAgo; ?></span> <?= $daysCreatedAgo > 1 ? 'days ago' : 'day ago'; ?>
							<?php } else echo 'today'; ?>
						</li>-->
                            <li><?= Yii::$app->formatter->asDate($model->datePublished); ?></li>
                        </ul>
                    </div>
                    <!-- end:details -->

                </header>
                <!-- end:header -->
            </div>
            <!-- end:cnt -->
            <div class="cnt clr">
                <?= $model->postText; ?>
            </div>
        </article>
        <!-- end:post -->


    </main>
    <!-- end:main -->

<?php
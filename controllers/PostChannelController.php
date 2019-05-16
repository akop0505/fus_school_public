<?php

namespace app\controllers;

use Yii;
use app\models\Channel;
use app\models\PostChannel;
use app\models\search\PostChannel as PostChannelSearch;
use app\controllers\common\BaseAdminController;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PostChannelController implements the CRUD actions for PostChannel model.
 */
class PostChannelController extends BaseAdminController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return ArrayHelper::merge([
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		], parent::behaviors());
	}

	/**
	 * Lists all PostChannel models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PostChannelSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->andWhere(['isSystem' => 1]);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new PostChannel model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new PostChannel();
		$message = '';
		if($model->load(Yii::$app->request->post()))
		{
			$check = PostChannel::find()->where([
				'channelId' => Yii::$app->request->post()['PostChannel']['channelId'],
				'postId' => Yii::$app->request->post()['PostChannel']['postId']
			])->one();

			if($check) $message = 'Article is already part of the selected channel.';

			if($message)
			{
				Yii::$app->getSession()->setFlash('error', $message);
				return $this->redirect(['create']);
			}
			elseif($model->save())
			{
				Channel::updateAllCounters(['numPosts' => 1], ['id' => $model->channelId]);
				return $this->redirect(['index']);
			}
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Deletes an existing PostChannel model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $channelId
	 * @param integer $postId
	 * @return mixed
	 */
	public function actionDeletePostChannel($channelId, $postId)
	{
		$find = PostChannel::findOne(['postId' => $postId, 'channelId' => $channelId]);
		if($find->delete())	Channel::updateAllCounters(['numPosts' => -1], ['id' => $channelId]);
		return $this->redirect(['index']);
	}

	/**
	 * @inheritdoc
	 */
	protected function findModel($channelId)
	{
	}
}

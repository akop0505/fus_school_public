<?php

namespace app\controllers;

use Yii;
use app\models\Channel;
use app\models\Post;
use app\models\PostChannel;
use app\models\PostRepost;
use app\models\search\PostRepostSearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;

/**
 * PostRepostController implements the CRUD actions for PostRepost model.
 */
class PostRepostController extends BaseAdminController
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
	 * Lists all PostRepost models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PostRepostSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}


	/**
	 * Creates a new PostRepost model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new PostRepost();

		if($model->load(Yii::$app->request->post()))
		{
			if(PostRepost::findOne(['postId' => $model->postId, 'institutionId' => $model->institutionId]) || $model->save()) return $this->redirect(['index']);
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Approved / refuse repost
	 * @param int $postId
	 * @param int $institutionId
	 * @param int $approve
	 * @return \yii\web\Response
	 * @throws ForbiddenHttpException
	 */
	public function actionToggleRepostApprove($postId, $institutionId, $approve)
	{
		$model = PostRepost::findOne(['postId' => $postId, 'institutionId' => $institutionId]);
		$post = Post::findOne(['id' => $postId]);
		if($model)
		{
			$isAdmin = Yii::$app->user->can($post->video ? 'ApproveVideo' : 'ApprovePost');
			if(!$isAdmin) throw new ForbiddenHttpException();

			if($approve && !$model->isApproved)
			{
				$model->updateAttributes(['isApproved' => 1]);
			}
			elseif(!$approve && $model->isApproved)
			{
				$findChannel = Channel::findOne(['institutionId' => $institutionId]);
				$findIfExists = PostChannel::findOne(['channelId' => $findChannel->id, 'postId' => $postId]);
				if($findIfExists)
				{
					$findIfExists->delete();

				}
				$model->updateAttributes(['isApproved' => 0]);
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Deletes an existing PostRepost model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $institutionId
	 * @param integer $postId
	 * @return mixed
	 */
	public function actionDeletePostRepost($postId, $institutionId)
	{
		$find = PostRepost::findOne(['postId' => $postId, 'institutionId' => $institutionId]);
		$find->delete();
		return $this->redirect(['index']);
	}

	/**
	 * Finds the PostRepost model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $postId
     * @param integer $institutionId
	 * @return PostRepost the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($postId)
	{
		if($this->_workingModel === null) $this->loadModel($postId);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

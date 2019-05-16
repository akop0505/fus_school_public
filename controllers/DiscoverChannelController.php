<?php

namespace app\controllers;

use Yii;
use app\models\DiscoverChannel;
use app\models\search\DiscoverChannel as DiscoverChannelSearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * DiscoverChannelController implements the CRUD actions for DiscoverChannel model.
 */
class DiscoverChannelController extends BaseAdminController
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
	 * Lists all DiscoverChannel models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new DiscoverChannelSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->orderBy('sort asc');

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new DiscoverChannel model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new DiscoverChannel();
		$message = '';
		if($model->load(Yii::$app->request->post()))
		{
			$channel = DiscoverChannel::findOne(Yii::$app->request->post()['DiscoverChannel']['channelId']);
			$sort = DiscoverChannel::find()->where(['sort' => Yii::$app->request->post()['DiscoverChannel']['sort']])->one();
			if($channel) $message = 'Channel already exists.';
			if($sort) $message = 'Sort already exists.';

			if($message)
			{
				Yii::$app->getSession()->setFlash('error', $message);
				return $this->redirect(['create']);
			}
			else
			{
				if($model->save()) return $this->redirect(['index']);
			}
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Move channel sort up/down by one
	 * @param $channelId
	 * @param $sort
	 * @param bool $up
	 * @return \yii\web\Response
	 */
	public function actionSortUp($channelId, $sort, $up = false)
	{
		if($up) $newSort = $sort - 1;
		else $newSort = $sort + 1;

		$changeChannelInitialSort = DiscoverChannel::findOne($channelId);
		$changeChannelSort = DiscoverChannel::find()->where(['sort' => $newSort])->one();

		if($changeChannelInitialSort) $changeChannelInitialSort->updateAttributes(['sort' => $newSort]);
		if($changeChannelSort) $changeChannelSort->updateAttributes(['sort' => $sort]);

		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Deletes an existing DiscoverChannel model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the DiscoverChannel model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return DiscoverChannel the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if($this->_workingModel === null) $this->loadModel($id);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

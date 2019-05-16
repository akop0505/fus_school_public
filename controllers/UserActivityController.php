<?php

namespace app\controllers;

use Yii;
use app\models\UserActivity;
use app\models\search\UserActivitySearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * UserActivityController implements the CRUD actions for UserActivity model.
 */
class UserActivityController extends BaseAdminController
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
	 * Lists all UserActivity models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new UserActivitySearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single UserActivity model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new UserActivity model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new UserActivity();

		if($model->load(Yii::$app->request->post()))
		{
			if($model->save()) return $this->redirect(['view', 'id' => $model->id]);
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Updates an existing UserActivity model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if($model->load(Yii::$app->request->post()))
		{
			if($model->save()) return $this->redirect(['view', 'id' => $model->id]);
		}
		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Deletes an existing UserActivity model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the UserActivity model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return UserActivity the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if($this->_workingModel === null) $this->loadModel($id);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

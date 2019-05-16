<?php

namespace app\controllers;

use Yii;
use app\models\common\BaseUploadForm;
use app\models\Channel;
use app\models\search\ChannelSearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ChannelController implements the CRUD actions for Channel model.
 */
class ChannelController extends BaseAdminController
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
	 * Lists all Channel models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new ChannelSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Channel model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}

	/**
	 * Creates a new Channel model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Channel();

		if($model->load(Yii::$app->request->post()))
		{
			$model->hasPhoto = 0;
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret) return $ret;
			}
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Updates an existing Channel model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if($model->load(Yii::$app->request->post()))
		{
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret) return $ret;
			}
		}
		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Finds the Channel model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Channel the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if($this->_workingModel === null) $this->loadModel($id);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

	/**
	 * @param $id
	 * @return bool|\yii\web\Response
	 */
	private function uploadPicture($id)
	{
		/**
		 * @var Channel $modelReal - reload to get correct class
		 */
		$modelReal = $this->loadModel($id);
		$uploadError = $this->handleImageUpload($modelReal, 'hasPhoto', new BaseUploadForm());
		$uploadError2 = $this->handleImageUpload($modelReal, 'hasPortraitPhoto', new BaseUploadForm());
		if(!$uploadError && !$uploadError2 && $modelReal->save(false)) return $this->redirect(['view', 'id' => $id]);
		elseif($uploadError || $uploadError2) return $this->redirect(['update', 'id' => $id]);
		else return false;
	}
}

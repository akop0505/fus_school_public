<?php

namespace app\controllers;

use Yii;
use app\models\forms\UploadFileForm;
use app\models\FileUpload;
use app\models\search\FileUploadSearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * FileUploadController implements the CRUD actions for FileUpload model.
 */
class FileUploadController extends BaseAdminController
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
	 * Lists all FileUpload models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new FileUploadSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single FileUpload model.
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
	 * Creates a new FileUpload model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new FileUpload();

		if($model->load(Yii::$app->request->post()))
		{
			$upload = new UploadFileForm();
			$upload->fileName = UploadedFile::getInstance($model, 'fileName');
			$fileName = $upload->fileName->baseName . '.' . $upload->fileName->extension;
			$check = FileUpload::find()->where(['fileName' => $fileName])->all();
			if($check) $this->setFlash('error', Yii::t('app', 'File already exists!'));
			else
			{
				if($upload->upload())
				{
					$model->fileName = $fileName;
					if($model->save()) return $this->redirect(['view', 'id' => $model->id]);
				}
			}
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Deletes an existing FileUpload model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		if(file_exists(Yii::getAlias('@webroot/static/'. $model->fileName)))
		{
			unlink(Yii::getAlias('@webroot/static/'. $model->fileName));
		}
		$model->delete();

		return $this->redirect(['index']);
	}

	/**
	 * Finds the FileUpload model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return FileUpload the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if($this->_workingModel === null) $this->loadModel($id);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

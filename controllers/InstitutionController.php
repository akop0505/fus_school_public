<?php

namespace app\controllers;

use Yii;
use app\models\common\BaseUploadForm;
use app\models\forms\InstitutionUploadForm;
use app\models\Institution;
use app\models\search\InstitutionSearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * InstitutionController implements the CRUD actions for Institution model.
 */
class InstitutionController extends BaseAdminController
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
	 * Lists all Institution models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new InstitutionSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Institution model.
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
	 * Creates a new Institution model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Institution();

		if($model->load(Yii::$app->request->post()))
		{
			$model->hasLatestPhoto = 0;
			$model->about = $this->addTargetBlankToUrl($model->about);
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret) return $this->redirect($ret);
			}
		}
		else $model->themeColor = '#e12c3c';
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Updates an existing Institution model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if($model->load(Yii::$app->request->post()))
		{
			if(!$model->schoolBanner) $model->hasLatestPhoto = 0;
			$model->about = $this->addTargetBlankToUrl($model->about);
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret) return $this->redirect($ret);
			}
		}
		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Add target blank to links
	 * @param string $text
	 * @return string
	 */
	private function addTargetBlankToUrl($text)
	{
		$text = preg_replace("/(<a.*?)[ ]*target=\".+\"([^>]*>)/", '\\1\\2', $text);
		return str_replace('<a ', '<a target="_blank" ', $text);
	}

	/**
	 * Updates an existing Institution model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionSchoolAdminUpdate()
	{
		$this->layout = '@app/views/layouts/main';
		$model = $this->findModel(Yii::$app->user->identity->institutionId);
		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'schoolProfile';

		if($model->load(Yii::$app->request->post()))
		{
			if(!$model->schoolBanner) $model->hasLatestPhoto = 0;
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret)
				{
					$this->setFlash('success', Yii::t('app', 'Data saved!'));
					$model = $this->findModel(Yii::$app->user->identity->institutionId);
				}
			}
		}
		if($model->hasLatestPhoto) $model->schoolBanner = true;
		$data['targetModel'] = $model;
		$data['profileContent'] = $this->renderPartial('/profile/_schoolProfile', $data);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Finds the Institution model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Institution the loaded model
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
	 * @return bool|array
	 */
	private function uploadPicture($id)
	{
		/**
		 * @var Institution $modelReal - reload to get correct class
		 */
		$modelReal = $this->loadModel($id);
		$modelReal->loadCityData();
		$uploadError = $this->handleImageUpload($modelReal, 'logo', new BaseUploadForm());
		$uploadError2 = $this->handleImageUpload($modelReal, 'header', new BaseUploadForm());
		$uploadError3 = $this->handleImageUpload($modelReal, 'hasLatestPhoto', new InstitutionUploadForm());
		if(!$uploadError && !$uploadError2 && !$uploadError3 && $modelReal->save(false)) return ['view', 'id' => $id];
		elseif($uploadError || $uploadError2 || $uploadError3) return ['update', 'id' => $id];
		else return false;
	}
}

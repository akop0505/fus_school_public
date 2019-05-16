<?php

namespace app\controllers;

use Yii;
use app\models\HomepageFeaturedPost;
use app\models\search\HomepageFeaturedPostSearch;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * HomepageFeaturedController implements the CRUD actions for HomepageFeaturedPost model.
 */
class HomepageFeaturedController extends BaseAdminController
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
	 * Lists all HomepageFeaturedPost models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new HomepageFeaturedPostSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$dataProvider->query->orderBy('channelId asc');

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * @return string
	 */
	public function actionHomepageChannel()
	{
		$model = new HomepageFeaturedPost();

		return $this->render('homepageChannel', ['model' => $model]);
    }

	/**
	 * @return string
	 * @throws BadRequestHttpException
	 */
	public function actionHomepageFeaturedPost()
	{
		$channelId = $_GET['HomepageFeaturedPost']['channelId'];
		$sortMax = 10;

		$post = Yii::$app->request->post('homepageFeaturedPost', []);
		if($post)
		{
			HomepageFeaturedPost::deleteAll(['channelId' => $channelId]);
			$postIdUsed = [];
			foreach($post as $key => $one)
			{
				if(!$one || in_array($one, $postIdUsed)) continue;
				else
				{
					$model = new HomepageFeaturedPost();
					$model->postId = $one;
					$model->channelId = $channelId;
					$model->sort = $key;
					if(!$model->save()) throw new BadRequestHttpException(Yii::t('app', 'Homepage Featured Post post not saved.'));
					else $postIdUsed[] = $one;
				}
			}
			$this->setFlash('success', Yii::t('app', 'Data saved.'));
		}

		return $this->render('homepageFeaturedPost', [
			'sortMax' => $sortMax,
			'channelId' => $channelId,
			]
		);
	}

	/**
	 * Finds the HomepageFeaturedPost model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $channelId
	 * @return HomepageFeaturedPost the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($channelId)
	{
		if($this->_workingModel === null) $this->loadModel($channelId);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

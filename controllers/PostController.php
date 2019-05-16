<?php

namespace app\controllers;

use app\models\Channel;
use app\models\HomepageFeaturedPost;
use app\models\PostChannel;
use app\models\PostFavorite;
use app\models\PostFeatured;
use app\models\PostLater;
use app\models\PostLike;
use app\models\PostMedia;
use app\models\PostRepost;
use app\models\PostTag;
use app\models\User;
use app\models\UserActivity;
use Yii;
use app\botr\BotrAPI;
use app\models\forms\PostThumbUploadForm;
use app\models\common\BaseUploadForm;
use app\models\Post;
use app\models\search\PostSearch;
use app\controllers\common\BaseAdminController;
use yii\db\Expression;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends BaseAdminController
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
	 * Lists all Post models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel = new PostSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Displays a single Post model.
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
	 * Creates a new Post model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Post();
		$model->setScenario('insert');

		if($model->load(Yii::$app->request->post()))
		{
			$model->hasHeaderPhoto = 0;
			$model->hasThumbPhoto = 0;
			$model->datePublished = new Expression('UTC_TIMESTAMP()');
			$model->isApproved = $model->isActive;
			if($model->isApproved)
			{
				$model->approvedById = Yii::$app->user->id;
				Yii::warning('3 Approved '. $model->id .' by '. Yii::$app->user->id);
			}
			if($model->dateToBePublished)
			{
				$model->dateToBePublishedSetById = Yii::$app->user->id;
			}
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret) return $this->redirect($ret);
			}
		}
		return $this->render('create', ['model' => $model]);
    }

	/**
	 * Updates an existing Post model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if($model->load(Yii::$app->request->post()))
		{
			$model->isApproved = $model->isActive;
			if($model->isApproved)
			{
				$model->approvedById = Yii::$app->user->id;
				Yii::warning('4 Approved '. $model->id .' by '. Yii::$app->user->id);
			}
			if($model->dateToBePublished)
			{
				$model->dateToBePublishedSetById = Yii::$app->user->id;
			}
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret) return $this->redirect($ret);
			}
		}
		return $this->render('update', ['model' => $model]);
	}

	/**
	 * Publish new post
	 * @return mixed
	 */
	public function actionPublishPost()
	{
		$this->layout = '@app/views/layouts/main';
		$isAdmin = Yii::$app->user->can('SchoolAdmin');
		$model = new Post();
		$model->setScenario('insert');
		$hideForm = false;

		if($model->load(Yii::$app->request->post()))
		{
			$postData = Yii::$app->request->post('Post');
			$model->hasHeaderPhoto = 0;
			$model->hasThumbPhoto = 0;
			if(!$isAdmin) $model->isActive = $model->isApproved = 0;
			else $model->isApproved = $model->isActive;
			if($model->isApproved)
			{
				$model->approvedById = Yii::$app->user->id;
				Yii::warning('5 Approved '. $model->id .' by '. Yii::$app->user->id);
			}
			$model->datePublished = new Expression('UTC_TIMESTAMP()');
			if($model->dateToBePublished)
			{
				$model->dateToBePublishedSetById = Yii::$app->user->id;
			}
			if(isset($postData['createdById']) && Yii::$app->user->can('SchoolAdmin'))
			{
				$check = User::find()->where(['id' => $postData['createdById'], 'institutionId' => Yii::$app->user->identity->institutionId])->one();
				if($check) $model->setOverrideCreatedBy($postData['createdById']);
			}
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret)
				{
					if(isset($postData['video']) && $postData['video'] == '1') return $this->redirect(['/post/upload-video', 'id' => $model->id]);
					$this->setFlash('success', Yii::t('app', 'Data saved!'));
					$hideForm = true;
				}
			}
		}
		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'publishPost';
		$data['targetModel'] = $model;
		$data['hideForm'] = $hideForm;
		$data['profileContent'] = $this->renderPartial('/profile/_publishPost', $data);
		return $this->render('/site/profile', $data);
	}


	/**
	 * Upload post video
	 * @param int $id - post ID
	 * @param bool|int $done
	 * @return mixed
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionUploadVideo($id, $done = false)
	{
		$this->layout = '@app/views/layouts/main';
		$model = $this->findModel($id);
		$model->checkPostPermission();
		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'publishPost';

		if($done && $_GET['video'])
		{
			$oldVideo = $model->video;
			$updateData = [
				'video' => $_GET['video'],
				'updatedById' => Yii::$app->user->id,
				'updatedAt' => new Expression('UTC_TIMESTAMP()')
			];
			if(!Yii::$app->user->can('SchoolAdmin')) $updateData['isActive'] = $updateData['isApproved'] = 0;
			$model->updateAttributes($updateData);
			if($oldVideo)
			{
				$botr = new BotrAPI(Yii::$app->params['jwp.api.key'], Yii::$app->params['jwp.api.secret']);
				$botr->call('/videos/delete', ['video_key' => $oldVideo]);
			}
			$this->setFlash('success', Yii::t('app', 'Video uploaded!'));
			return $this->redirect(Yii::$app->user->can('ContentAdmin') ? ['/post/index'] : ['/profile/posts']);
		}
		$data['targetModel'] = $model;
		$data['profileContent'] = $this->renderPartial('/post/_uploadVideoForm', $data);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Remove video from post - frontend
	 * @param int $id
	 * @return mixed
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionRemoveVideo($id)
	{
		$model = $this->findModel($id);
		$model->checkPostPermission();
		$oldVideo = $model->video;
		if($oldVideo)
		{
			$updateData = [
				'video' => new Expression('NULL'),
				'updatedById' => Yii::$app->user->id,
				'updatedAt' => new Expression('UTC_TIMESTAMP()')
			];
			$model->updateAttributes($updateData);
			$botr = new BotrAPI(Yii::$app->params['jwp.api.key'], Yii::$app->params['jwp.api.secret']);
			$botr->call('/videos/delete', ['video_key' => $oldVideo]);
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Edit post - frontend
	 * @param int $id
	 * @return mixed
	 * @throws ForbiddenHttpException
	 * @throws NotFoundHttpException
	 */
	public function actionEditPost($id)
	{
		$this->layout = '@app/views/layouts/main';
		$model = $this->findModel($id);
		$model->checkPostPermission();
		if($model->load(Yii::$app->request->post()))
		{
			if(!Yii::$app->user->can('SchoolAdmin') || !$model->hasHeaderPhoto || !$model->hasThumbPhoto) $model->isActive = $model->isApproved = 0;
			else
			{
				$model->isApproved = $model->isActive;
				if($model->isApproved)
				{
					$model->approvedById = Yii::$app->user->id;
					Yii::warning('6 Approved '. $model->id .' by '. Yii::$app->user->id);
				}
				$model->datePublished = new Expression('UTC_TIMESTAMP()');
				if($model->dateToBePublished)
				{
					$model->dateToBePublishedSetById = Yii::$app->user->id;
				}
			}
			if($model->save())
			{
				$ret = $this->uploadPicture($model->id);
				if($ret)
				{
					$this->setFlash('success', Yii::t('app', 'Data saved!'));
					$model = $this->findModel($id);
					if(!$model->hasHeaderPhoto || !$model->hasThumbPhoto) $this->setFlash('error', Yii::t('app', 'Post can not be published because there is no picture.'));
				}
			}
		}
		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'none';
		$data['targetModel'] = $model;
		$data['profileContent'] = $this->renderPartial('/profile/_editPost', $data);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Finds the Post model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Post the loaded model
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
		 * @var Post $modelReal - reload to get correct class
		 */
		$modelReal = $this->loadModel($id);
		$uploadError = $this->handleImageUpload($modelReal, 'hasHeaderPhoto', new BaseUploadForm());
		$uploadError2 = $this->handleImageUpload($modelReal, 'hasThumbPhoto', new PostThumbUploadForm());
		if(!$uploadError && !$uploadError2 && $modelReal->save(false)) return ['view', 'id' => $id];
		elseif($uploadError || $uploadError2) return ['update', 'id' => $id];
		else return false;
	}

	/**
	 * @param $id
	 * @param $national
	 * @return \yii\web\Response
	 * @throws ForbiddenHttpException
	 */
	public function actionTogglePostNational($id, $national)
	{
		$model = Post::findOne($id);
		if($model)
		{
			if(!Yii::$app->user->can('ContentAdmin')) throw new ForbiddenHttpException();

			if($national && !$model->isNational)
			{
				$model->updateAttributes(['isNational' => 1, 'updatedById' => Yii::$app->user->id, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);
				Post::sendNationalNotification($model->id);
			}
			elseif(!$national && $model->isNational)
			{
				$model->updateAttributes(['isNational' => 0, 'updatedById' => Yii::$app->user->id, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
	public function actionDeletePost($id)
	{
		$model = $this->findModel($id);
		$model->checkPostPermission();
		$transaction = Yii::$app->db->beginTransaction();
		try
		{
			$model->isActive = 0;
			$model->save();
			$postChannel = PostChannel::findAll(['postId' => $id]);
			if($postChannel)
			{
				foreach($postChannel as $onePost)
				{
					Channel::updateAllCounters(['numPosts' => -1], ['id' => $onePost->channelId]);
					$onePost->delete();
				}
			}
			PostFavorite::deleteAll(['postId' => $id]);
			PostFeatured::deleteAll(['postId' => $id]);
			PostLater::deleteAll(['postId' => $id]);
			PostLike::deleteAll(['postId' => $id]);
			PostRepost::deleteAll(['postId' => $id]);
			PostTag::deleteAll(['postId' => $id]);
			if(PostMedia::deleteAll(['postId' => $id])){
                $this->deleteDirectory($model->getGalleryBasePath());
            };
			HomepageFeaturedPost::deleteAll(['postId' => $id]);

			if($model->video) $this->actionRemoveVideo($id);
			$model->delete();

			UserActivity::deleteAll(['activityTypeFk' => $id, 'activityType' => [
				UserActivity::ACTIVITYTYPE_POST,
				UserActivity::ACTIVITYTYPE_POSTFAVORITE,
				UserActivity::ACTIVITYTYPE_POSTLATER,
				UserActivity::ACTIVITYTYPE_POSTLIKE
			]]);

			$transaction->commit();
			$this->setFlash('success', Yii::t('app', 'Post deleted.'));
		}
		catch(\Exception $e)
		{
			$transaction->rollBack();
			$this->setFlash('error', $e->getMessage());
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

}

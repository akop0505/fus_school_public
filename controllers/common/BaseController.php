<?php
namespace app\controllers\common;

use Yii;
use app\models\Channel;
use app\models\ChannelSubscribe;
use app\models\common\BaseUploadForm;
use app\models\User;
use app\models\common\BaseActiveRecord;
use app\filters\PreloadModelFilter;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

abstract class BaseController extends Controller
{
	/**
	 * @var BaseActiveRecord|null
	 */
	public $_workingModel = null;
	/**
	 * @var null|int
	 */
	protected $userId = null;

	/**
	 * Load model for current controller by ID
	 * @param int $id
	 * @return BaseActiveRecord|null
	 */
	public function loadModel($id)
	{
		if(!$id) return $this->_workingModel;
		$modelClassName = str_replace(['app\controllers', 'Controller'], ['app\models', ''], $this->className());
		$this->_workingModel = $modelClassName::findOne($id);
		return $this->_workingModel;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return ArrayHelper::merge(parent::behaviors(), [
			'preloadModel' => [
				'class' => PreloadModelFilter::className(),
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeAction($action)
	{
		$this->userId = Yii::$app->user->identity['id'];
		if(!parent::beforeAction($action)) return false;
		Yii::$app->view->registerMetaTag(Yii::$app->params['meta.default.description'], "meta_description");
		Yii::$app->view->registerMetaTag(Yii::$app->params['meta.default.keywords'], "meta_keywords");
		Yii::$app->view->registerMetaTag(Yii::$app->params['meta.default.robots'], "meta_robots");
		return true;
	}

	/**
	 * Set flash message
	 * @param string $key
	 * @param bool|string $value
	 * @param bool $removeAfterAccess
	 */
	protected function setFlash($key, $value = true, $removeAfterAccess = true)
	{
		Yii::$app->getSession()->setFlash($key, $value, $removeAfterAccess);
	}

	/**
	 * Save uploaded image and thumb
	 * @param BaseActiveRecord $model
	 * @param string $attribute
	 * @param BaseUploadForm $picModel
	 * @return bool
	 */
	protected function handleImageUpload($model, $attribute, $picModel)
	{
		$picModel->attribute = $attribute;
		$uploadError = false;
		$ufInstance = UploadedFile::getInstance($model, $attribute);
		if(($picModel->imageFile = $ufInstance) && !$picModel->upload($model)) $uploadError = true;
		return $uploadError;
	}

	/**
	 * Finds the model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return BaseActiveRecord the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	abstract protected function findModel($id);

	/**
	 * Inactivates or deletes an existing model.
	 * If deletion is successful, the browser will be redirected to the 'index' page, if deactivation to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		if($model->hasAttribute('isActive'))
		{
			$model->setAttribute('isActive', 0);
			$model->save(false);
			return $this->redirect(['view', 'id' => $id]);
		}
		else
		{
			$model->delete();
			return $this->redirect(['index']);
		}
	}

	/**
	 * Setup layout for user's profile
	 * @param int $id - user ID
	 * @return array
	 * @throws NotFoundHttpException
	 */
	protected function setupProfileLayout($id)
	{
		$this->view->params['class'] = 'profile';
		$model = User::findOne(['id' => $id]);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
		$page = Yii::$app->request->get('postType', 'latest');
		$model->updatingNumberOfViews();

		$userChannelId = $subscribed = 0;
		/**
		 * @var $userChannel Channel
		 */
		$userChannel = Channel::find()->where(['userId' => $id])->one();
		if(!Yii::$app->user->isGuest)
		{
			if($userChannel && Yii::$app->user->identity->getId() != $id)
			{
				$userChannelId = $userChannel->id;
			}
	
			$subscribedToUser = ChannelSubscribe::find()->where([
				'channelId' => $userChannel->id,
				'createdById' => Yii::$app->user->identity->getId()])->one();
	
			if($subscribedToUser) $subscribed = true;
		}

		switch($page)
		{
			case 'like':
				$title = 'Liked posts';
				break;
			case 'activity':
				$title = 'Activity';
				break;
			case 'watchLater':
				$title = 'View later';
				break;
			case 'schoolProfile':
			case 'userProfile':
			case 'publishPost':
			case 'postsAdmin':
			case 'analytics':
				$title = '';
				break;
			case 'favorite':
				$title = 'Favorite posts';
				break;
			case 'latest':
			default:
				$title = 'Posts';
				break;
		}

		return array(
			'model' => $model,
			'countWatchLater' => $model->countWatchLater(),
			'countLike' => $model->countPostLike(),
			'countFavorite' => $model->countPostFavorite(),
			'countSubscriptions' => $model->countSubscriptions(),
			'countActivity' => $model->countActivity(),
			'countLatest' => $model->countPosts(),
			'subscribed' => $subscribed,
			'userChannelId' => $userChannelId,
			'page' => $page,
			'title' => $title,
		);
	}

    /**
     * @param $dir
     * @return bool
     */
    protected function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return @unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return @rmdir($dir);
    }

}
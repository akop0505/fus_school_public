<?php

namespace app\controllers;

use Yii;
use app\models\forms\PostMediaUploadForm;
use app\models\Post;
use app\models\PostMedia;
use app\controllers\common\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

/**
 * PostMediaController implements the CRUD actions for PostMedia model.
 */
class PostMediaController extends BaseAdminController
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
	 * Displays a single PostMedia model.
	 * @param int $postId
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionView($postId)
	{
		$model = Post::findOne($postId);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
		$mediaPath = $model->getGalleryBasePath();
		$mediaDir = $model->getGalleryBaseUrl();
		$fileSize = [];
		$postMedia = PostMedia::find()->where(['postId' => $postId])->orderBy('sort asc')->all();

		foreach($postMedia as $i => $media)
		{
			$fileSize[$i] = filesize($mediaPath . $media->filename);
		}

		return $this->render('view', [
			'post' => $model,
			'postMedia' => $postMedia,
			'mediaDir' 	=> $mediaDir,
			'fileSize'	=> $fileSize
		]);
	}

	/**
	 * Saves Gallery image and thumbnail by postId sent in post. Throws exception if upload error.
	 * @return string
	 * @throws BadRequestHttpException
	 */
	public function actionUpload()
	{
		$upload = new PostMediaUploadForm();
		$upload->imageFile = UploadedFile::getInstanceByName('file');
		$postId = Yii::$app->request->post('postId');

		if(($post = Post::findOne($postId)) && $upload->imageFile)
		{
			$uploadError = !$upload->upload($post, Yii::$app->request->post('fileSort', 0));
		}
		else $uploadError = true;
		if($uploadError) throw new BadRequestHttpException(Yii::t('app', 'Error.'));
		return '';
	}

	/**
	 * Deletes gallery images from filesystem and rows from database. Throws exception if post doesn't
	 * exist or removing went failed.
	 * @return string
	 * @throws BadRequestHttpException
	 */
	public function actionDeleteOne()
	{
		$filename = Yii::$app->request->post('filename');
		$postId = Yii::$app->request->post('postId');
		$upload = new PostMediaUploadForm();

		if(($post = Post::findOne($postId)) && $upload->removeImageByName($post, $filename))
		{
			$ret = PostMedia::deleteAll(['filename' => $filename, 'postId' => $postId]);
			if(!$ret) throw new BadRequestHttpException(Yii::t('app', 'Error.'));
		}
		else throw new BadRequestHttpException(Yii::t('app', 'Error.'));
		return '';
	}

	/**
	 * Finds the PostMedia model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return PostMedia the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if($this->_workingModel === null) $this->loadModel($id);
		if($this->_workingModel !== null) return $this->_workingModel;
		else throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}

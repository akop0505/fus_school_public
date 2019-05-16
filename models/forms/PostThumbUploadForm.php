<?php

namespace app\models\forms;

use Yii;
use app\models\common\BaseUploadForm;
use app\models\Post;
use yii\base\Exception;
use yii\imagine\Image;
use Imagine\Image\ManipulatorInterface;

/**
 * Class PostThumbUploadForm
 * @package app\models\forms
 */
class PostThumbUploadForm extends BaseUploadForm
{
	/**
	 * Return thumbnail dimensions [w, h]
	 * @return array
	 */
	private function getThumbDimensions()
	{
		return [750, 600];
	}

	/**
	 * Uploads Post thumbnail in directory. Also creates
	 * directory if it does not exist. Returns false if it fails.
	 * @param Post $model
	 * @param array $extra
	 * @return bool|string
	 */
	public function upload($model, $extra = [])
	{
		if($this->validate())
		{
			if($model->hasAttribute($this->attribute))
			{
				$model->setAttribute($this->attribute, 1);
				if(!$model->validate()) return false;
			}
			try
			{
				$savePath = $model->getPicBasePath($this->attribute);
				$this->createDirRecursive($savePath);
				$savePath .= $model->getPicName($this->attribute);
				$thumbDims = $this->getThumbDimensions();

				// already jpg
				if(strtolower($this->imageFile->extension) == 'jpg' && exif_imagetype($this->imageFile->tempName) == IMAGETYPE_JPEG)
				{
					Image::thumbnail($this->imageFile->tempName, $thumbDims[0], $thumbDims[1], ManipulatorInterface::THUMBNAIL_OUTBOUND)->save($savePath);
				}
				// need to convert
				else
				{
					Image::getImagine()->open($this->imageFile->tempName)->save($this->imageFile->tempName, ['quality' => isset($extra['quality']) ? $extra['quality'] : 95]);
					Image::thumbnail($this->imageFile->tempName, $thumbDims[0], $thumbDims[1], ManipulatorInterface::THUMBNAIL_OUTBOUND)->save($savePath);
				}
				shell_exec('jpegoptim --strip-all -T10 -m95 '. escapeshellarg($savePath));
			}
			catch(Exception $e)
			{
				Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Image upload failed.'));
				return false;
			}
			return true;
		}
		else Yii::$app->getSession()->setFlash('error', $this->getErrors('imageFile')[0]);
		return false;
	}
}
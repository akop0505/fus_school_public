<?php
namespace app\models\forms;

use Imagine\Image\Box;
use Yii;
use app\models\PostMedia;
use app\models\Post;
use app\models\common\BaseUploadForm;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;
use yii\base\Exception;
use yii\imagine\Image;
use Imagine\Image\ManipulatorInterface;

class PostMediaUploadForm extends BaseUploadForm
{
	/**
	 * @var UploadedFile
	 */
	public $imageFile;

	public function rules()
	{
		return [
			['imageFile', 'image', 'skipOnEmpty' => false, 'extensions' => 'png, jpg',
				'minWidth' => 90, 'maxWidth' => 10000,
				'minHeight' => 90, 'maxHeight' => 10000,
			],
		];
	}

	/**
	 * Uploads Gallery image and thumbnail in directory. Also creates
	 * directory if it does not exist. Throws exception if filename already exists.
	 * @param Post $post
	 * @param int $sort
	 * @return bool
	 * @throws BadRequestHttpException
	 */
	public function upload($post, $sort = 0)
	{
		if($this->validate())
		{
			$basePath = $post->getGalleryBasePath();
			$imageName = preg_replace('/[^-_0-9a-zA-Z]+/', '', str_replace(' ', '_', $this->imageFile->baseName)) . '.' . $this->imageFile->extension;
			if(file_exists($basePath . $imageName)) throw new BadRequestHttpException(Yii::t('app', 'Filename already exist.'));
			$thumbName = $post->getThumbFilename($imageName);
			$thumbDims = $post->getThumbDimensions();
			$maxDims = $post->getGalleryMaxDimensions();
			$postMedia = new PostMedia();
			$postMedia->filename = $imageName;
			$postMedia->postId = $post->id;
			$postMedia->sort = $sort;
			if(!$postMedia->validate()) return false;
			try
			{
				$this->createDirRecursive($basePath);
				if(!$this->imageFile->saveAs($basePath . $imageName)) throw new Exception;
				list($width1, $height1, $type1, $attr1) = getimagesize($basePath . $imageName);
				if(($width1 > $maxDims[0]) || ($height1 > $maxDims[1]))
				{
					Image::thumbnail($basePath . $imageName, $maxDims[0], $maxDims[1], ManipulatorInterface::THUMBNAIL_INSET)->save($basePath . $imageName);
				}
				Image::thumbnail($basePath . $imageName, $thumbDims[0], $thumbDims[1], ManipulatorInterface::THUMBNAIL_INSET)->save($basePath . $thumbName);
			}
			catch(Exception $e)
			{
				throw new BadRequestHttpException(Yii::t('app', 'Image upload failed.'));
			}
			// file is uploaded successfully
			$postMedia->save();
			return true;
		}
		throw new BadRequestHttpException($this->getErrors('imageFile')[0]);
	}

	/**
	 * Removes Gallery image and thumbnail from filesystem
	 * @param Post $post
	 * @param $imageName
	 * @return bool
	 */
	public function removeImageByName($post, $imageName)
	{
		$basePath = $post->getGalleryBasePath();
		if(!file_exists($basePath . $imageName)) $imageName = str_replace(' ', '_', $imageName);
		if(file_exists($basePath . $imageName))
		{
			@unlink($basePath . $imageName);
			$thumbName = $post->getThumbFilename($imageName);
			@unlink($basePath . $thumbName);
		}
		return true;
	}
}
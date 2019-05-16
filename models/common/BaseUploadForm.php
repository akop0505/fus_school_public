<?php
namespace app\models\common;

use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class BaseUploadForm
 * @package app\models\common
 */
class BaseUploadForm extends Model
{
	/**
	 * @var UploadedFile
	 */
	public $imageFile;
	/**
	 * @var string
	 */
	public $attribute;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['imageFile', 'image', 'skipOnEmpty' => false,
				'minWidth' => 100, 'maxWidth' => 3200,
				'minHeight' => 100, 'maxHeight' => 1800,
				'maxSize' => 1024 * 1024 * 4
			],
		];
	}

	/**
	 * Recursively create folder structure if it does not exist
	 * @param string $path
	 * @throws ErrorException
	 */
	protected function createDirRecursive($path)
	{
		if(!file_exists($path))
		{
			@mkdir($path, 0755, true);
		}
		if(!file_exists($path)) throw new ErrorException('Cannot create folder.');
	}

	/**
	 * Uploads image in directory. Also creates
	 * directory if it does not exist. Returns false if it fails.
	 * @param BaseActiveRecord $model
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
				// already jpg
				if(strtolower($this->imageFile->extension) == 'jpg' && exif_imagetype($this->imageFile->tempName) == IMAGETYPE_JPEG)
				{
					if(!$this->imageFile->saveAs($savePath)) throw new Exception;
				}
				// need to convert
				else
				{
					Image::getImagine()->open($this->imageFile->tempName)->save($savePath, ['quality' => isset($extra['quality']) ? $extra['quality'] : 90]);
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

	/**
	 * Removes image from filesystem
	 * @param BaseActiveRecord $model
	 * @return bool
	 */
	public function removeImage($model)
	{
		$savePath = $model->getPicBasePath($this->attribute) . $model->getPicName($this->attribute);
		if(file_exists($savePath))
		{
			@unlink($savePath);
		}
		return true;
	}
}
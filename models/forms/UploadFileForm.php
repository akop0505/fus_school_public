<?php
namespace app\models\forms;

use yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadFileForm extends Model
{
	/**
	 * @var UploadedFile
	 */
	public $fileName;

	public function rules()
	{
		return [
			[['fileName'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf, jpg, png, svg, xls, xlsx, doc, docx, ppt, pptx', 'checkExtensionByMimeType' => false],
		];
	}

	public function upload()
	{
		if($this->validate())
		{
			$this->fileName->saveAs(Yii::getAlias('@webroot/static/'. $this->fileName->baseName . '.' . $this->fileName->extension));
			return true;
		}
		else Yii::$app->getSession()->setFlash('error', $this->getErrors('fileName')[0]);
		return false;
	}
}
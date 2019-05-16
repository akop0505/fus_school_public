<?php

namespace app\models\forms;

use app\models\common\BaseUploadForm;

/**
 * InstitutionUploadForm is the model behind the institution upload form.
 */
class InstitutionUploadForm extends BaseUploadForm
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['imageFile', 'image', 'skipOnEmpty' => false,
				'minWidth' => 750, 'maxWidth' => 750,
				'minHeight' => 600, 'maxHeight' => 600,
				'maxSize' => 1024 * 1024 * 2
			],
		];
	}
}
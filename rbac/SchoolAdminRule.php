<?php
namespace app\rbac;

use yii\rbac\Rule;

class SchoolAdminRule extends Rule
{
	public $name = 'isSchoolAdmin';

	/**
	 * @inheritdoc
	 */
	public function execute($user, $item, $params)
	{
		return isset($params['institutionId']) ? $params['institutionId'] == $params['userInstitutionId'] : true;
	}
}
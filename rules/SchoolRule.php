<?php

namespace app\rules;

use app\models\Institution;
use yii\web\UrlRuleInterface;

class SchoolRule extends BaseRule implements UrlRuleInterface
{
	/**
	 * @inheritdoc
	 */
	public function createUrl($manager, $route, $params)
	{
		if($route != 'site/school') return false;
		$model = isset($params['item']) ? $params['item'] : Institution::findOne([$params['id']]);
		$suffix = $manager->suffix;
		if(isset($params['about']) && $params['about']) $suffix .= '?about='. $params['about'];
		return 'school/' . $model->id . '/' . $this->nameFromLink((string)$model) . $suffix;
	}

	/**
	 * @inheritdoc
	 */
	public function parseRequest($manager, $request)
	{
		$school = explode('/', $request->getPathInfo());
		if('school' != $school[0]) return false;
		$p = $request->getQueryParams();
		$p['id'] = $school[1];
		return ['site/school', $p];
	}
}
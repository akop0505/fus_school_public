<?php

namespace app\rules;

use app\models\User;
use yii\web\UrlRuleInterface;

class UserRule extends BaseRule implements UrlRuleInterface
{
	/**
	 * @inheritdoc
	 */
	public function createUrl($manager, $route, $params)
	{
		if($route != 'site/profile') return false;
		$model = isset($params['item']) ? $params['item'] : User::findOne([$params['id']]);
		$suffix = $manager->suffix;
		$extraParams = [];
		if(isset($params['postType']) && $params['postType']) $extraParams[] = 'postType='. $params['postType'];
		if(isset($params['page']) && $params['page']) $extraParams[] = 'page='. $params['page'];
		if(isset($extraParams[0])) $suffix .= '?'. implode('&', $extraParams);
		return 'profile/' . $model->id . '/' . $this->nameFromLink((string)$model) . $suffix;
	}

	/**
	 * @inheritdoc
	 */
	public function parseRequest($manager, $request)
	{
		$profile = explode('/', $request->getPathInfo());
		if('profile' != $profile[0] || !ctype_digit($profile[1])) return false;
		$p = $request->getQueryParams();
		$p['id'] = $profile[1];
		return ['site/profile', $p];
	}
}
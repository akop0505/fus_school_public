<?php

namespace app\rules;

use app\models\Tag;
use yii\web\UrlRuleInterface;

class TagRule extends BaseRule implements UrlRuleInterface
{
	/**
	 * @inheritdoc
	 */
	public function createUrl($manager, $route, $params)
	{
		if($route != 'site/tag') return false;
		$model = isset($params['item']) ? $params['item'] : Tag::findOne([$params['id']]);
		$suffix = $manager->suffix;
		if(isset($params['page']) && $params['page']) $suffix .= '?page='. $params['page'];
		return 'tag/' . $model->id . '/' . $this->nameFromLink((string)$model) . $suffix;
	}

	/**
	 * @inheritdoc
	 */
	public function parseRequest($manager, $request)
	{
		$tag = explode('/', $request->getPathInfo());
		if('tag' != $tag[0] || !ctype_digit($tag[1])) return false;
		$p = $request->getQueryParams();
		$p['id'] = $tag[1];
		return ['site/tag', $p];
	}
}
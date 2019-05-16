<?php

namespace app\rules;

use app\models\Post;
use yii\web\UrlRuleInterface;

class PostRule extends BaseRule implements UrlRuleInterface
{
	/**
	 * @inheritdoc
	 */
	public function createUrl($manager, $route, $params)
	{
		if($route != 'site/article') return false;
		$model = isset($params['item']) ? $params['item'] : Post::findOne([$params['id']]);
		return 'article/' . $model->id . '/' . $this->nameFromLink((string)$model) . $manager->suffix;
	}

	/**
	 * @inheritdoc
	 */
	public function parseRequest($manager, $request)
	{
		$article = explode('/', $request->getPathInfo());
		if('article' != $article[0]) return false;
		return ['site/article', ['id' => $article[1]]];
	}
}
<?php

namespace app\rules;

use app\models\Channel;
use yii\web\UrlRuleInterface;

class ChannelRule extends BaseRule implements UrlRuleInterface
{
	/**
	 * @inheritdoc
	 */
	public function createUrl($manager, $route, $params)
	{
		if($route != 'site/channel') return false;
		$model = isset($params['item']) ? $params['item'] : Channel::findOne([$params['id']]);
		$suffix = $manager->suffix;
		if(isset($params['page']) && $params['page']) $suffix .= '?page='. $params['page'];
		return 'channel/' . $model->id . '/' . $this->nameFromLink((string)$model) . $suffix;
	}

	/**
	 * @inheritdoc
	 */
	public function parseRequest($manager, $request)
	{
		$channel = explode('/', $request->getPathInfo());
		if('channel' != $channel[0] || !ctype_digit($channel[1])) return false;
		$p = $request->getQueryParams();
		$p['id'] = $channel[1];
		return ['site/channel', $p];
	}
}
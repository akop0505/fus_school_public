<?php

namespace app\commands;
use app\models\Post;
use yii\console\Controller;
use yii\db\Expression;
use Yii;
use app\models\User;
class PublishPostController extends Controller
{
	public function actionIndex()
	{
		$post = Post::find()
			->where(['<=', 'dateToBePublished', new Expression('UTC_TIMESTAMP()')])->all();

		if($post)
		{
			foreach($post as $model)
			{
				/**
				 * @var Post $model
				 */
				$params = ['dateToBePublished' => new Expression('NULL')];
				$user = User::findIdentity($model->dateToBePublishedSetById);
				Yii::$app->user->login($user);

				$model->updateAttributes($params);
				if($model->hasHeaderPhoto && $model->hasThumbPhoto && $model->isApproved)
				{
					$params['isActive'] = 1;
					$params['datePublished'] = new Expression('UTC_TIMESTAMP()');
					$model->updateAttributes($params);
					$model->isActive = 1;
					$model->updateDefaultChannels();
				}
			}
		}
	}
}
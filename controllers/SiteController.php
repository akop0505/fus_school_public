<?php

namespace app\controllers;

use app\models\Form;
use app\models\TagFeatured;
use Yii;
use app\controllers\common\BaseController;
use app\models\forms\PasswordResetRequestForm;
use app\models\forms\ContactFusFooForm;
use app\models\ChannelSubscribe;
use app\models\Content;
use app\models\DiscoverChannel;
use app\models\FeaturedChannel;
use app\models\forms\RegisterForm;
use app\models\forms\AccountActivationForm;
use app\models\forms\SchoolContactForm;
use app\models\forms\SchoolSearchForm;
use app\models\Institution;
use app\models\InstitutionLike;
use app\models\PostFavorite;
use app\models\PostLater;
use app\models\PostLike;
use app\models\PostRepost;
use app\models\Tag;
use app\models\TagSubscribe;
use app\models\User;
use app\models\UserActivity;
use app\models\Post;
use app\models\forms\LoginForm;
use app\models\forms\ContactForm;
use app\common\Pagination;
use app\models\Channel;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class SiteController extends BaseController
{
	protected function findModel($id)
	{
		return null;
	}

	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? null : null,
				'offset' => -1
            ],
        ];
    }

	/**
	 * Confirm users email
	 * @param int $u
	 * @param string $key
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionConfirmEmail($u, $key)
	{
		$user = User::findIdentity($u);
		if(!$user || !$user->validateAuthKey($key)) throw new BadRequestHttpException(Yii::t('app', 'The user and/or key is invalid.'));
		if($user && $user->status == User::STATUS_ACTIVE)
		{
			$this->setFlash('success', Yii::t('app', 'Account is already activated, please login with your data.'));
			return $this->redirect('/site/login.html');
		}
		$user->emailVerified = 1;
		$user->generateAuthKey();
		$user->status = User::STATUS_ACTIVE;
		if($user->save())
		{
			$this->setFlash('success', Yii::t('app', 'Email confirmed!'));
			if(Yii::$app->getUser()->login($user, Yii::$app->params['user.loginShortRememberMeDuration'])) return $this->goHome();
		}
		throw new BadRequestHttpException(Yii::t('app', 'Update failed.'));
	}

	public function actionError()
	{
		$this->view->params['class'] = 'general';
		/**
		 * @var Exception|HttpException $exception
		 */
		$exception = Yii::$app->errorHandler->exception;
		if($exception !== null)
		{
			if($exception instanceof HttpException)
			{
				$code = $exception->statusCode;
				$message = $exception->getMessage();
			}
			else
			{
				$code = $exception->getCode();
				$message = '';
			}
			$name = $exception->getName() .' ('. $code .')';
			if(Yii::$app->getRequest()->getIsAjax()) return "$name: $message";
			return $this->render('error', [
				'name' => $name,
				'message' => $message,
				'exception' => $exception
			]);
		}
		return null;
	}

    public function actionIndex()
    {
		$this->view->params['class'] = 'home';

		$topSlider = Post::find()->innerJoinWith('homepageFeaturedPosts', false)->with('createdBy.institution')->where(['isActive' => 1])
			->andWhere(['channelId' => Channel::CHANNEL_HOME_SLIDER])->orderBy('sort asc')->limit(7)->all();

		$bottomSlider = Post::find()->innerJoinWith('homepageFeaturedPosts', false)->with('createdBy.institution')->where(['isActive' => 1])
			->andWhere(['channelId' => Channel::CHANNEL_HOME_BELOW_SLIDER])->orderBy('sort asc')->limit(3)->all();

		$homeLatest = Post::find()->innerJoinWith('homepageFeaturedPosts', false)->with('createdBy.institution')->where(['isActive' => 1])
			->andWhere(['channelId' => Channel::CHANNEL_HOME_LATEST])->orderBy('sort asc')->limit(10)->all();

		$homeMustSee = Post::find()->innerJoinWith('homepageFeaturedPosts', false)->with('createdBy.institution')->where(['isActive' => 1])
			->andWhere(['channelId' => Channel::CHANNEL_HOME_MUST_SEE])->orderBy('sort asc')->limit(8)->all();

		$featuredChannels = FeaturedChannel::find()->orderBy('sort asc')->limit(10)->all();

		$homeFeatured = [];
		$n = 0;
		foreach($featuredChannels as $oneChannel)
		{
			if($n == 1)
			{
				$homeFeatured[$n] = [];
				$n++;
			}

			$featuredPost = Post::find()->innerJoinWith('homepageFeaturedPosts', false)->with('createdBy.institution')->where(['isActive' => 1])
				->andWhere(['channelId' => $oneChannel->channelId])->orderBy('sort asc')->limit($oneChannel->numPost)->all();

			$homeFeatured[$n] = [
				'channelName' => $oneChannel->channel->name,
				'channelId' => $oneChannel->channelId,
				'posts' => $featuredPost,
				'listClass' => 'list list-' . $oneChannel->numPost . ' clr',
				'numPost' => $oneChannel->numPost,
			];

			$n++;
		}
		$discoverChannels = DiscoverChannel::find()->joinWith('channel')->where(['isActive' => 1, 'hasPhoto' => 1])->orderBy('sort asc')->limit(10)->all();

        return $this->render('index', array(
			'topSlider' => $topSlider,
			'bottomSlider' => $bottomSlider,
			'homeLatest' => $homeLatest,
			'homeMustSee' => $homeMustSee,
			'discoverChannels' => $discoverChannels,
			'homeFeatured' => $homeFeatured
		));
    }

	public function actionChannel($id, $sort = '')
	{
		$this->view->params['class'] = 'channel';
		$searchTerm = '';
		$pageSize = 9;
		$model = Channel::findOne(['id' => $id]);
		$institutionId = $model->institutionId ? $model->institutionId : 0;

		$selectedPeriod = '';
		$months[0] = ['id' => '', 'name' => 'All'];
		for($i = 0; $i <= 6; $i++)
		{
			$date = date("Y-m-d", strtotime("-$i months"));
			$splitDate = explode('-', $date);
			$monthName = date('F', mktime(0, 0, 0, $splitDate[1], 10));
			$months[] = [
				'id' => $date,
				'name' => $monthName . ' ' . $splitDate[0]
			];
		}

		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
		if(!$model->hasPhoto) $this->view->params['class'] = 'general';
		$order = 'datePublished desc, id desc';
		if($sort) $order = 'views desc';

		$query = Post::find()->innerJoinWith('postChannels', false)->with('createdBy.institution')->where(['isActive' => 1])
			->andWhere(['channelId' => $id])->orderBy($order);
		if(isset($_GET['term']) && $_GET['term'])
		{
			$searchTerm = $_GET['term'];
			$query = $query->andWhere(['like', 'title', $_GET['term']]);
		}
		if(isset($_GET['period']) && $_GET['period'])
		{
			$split = explode('-', $_GET['period']);
			$number = cal_days_in_month(CAL_GREGORIAN, $split[1], $split[0]);
			$startDate = $split[0] . '-' . $split[1] . '-' . '1';
			$endDate =  $split[0] . '-' . $split[1] . '-' . $number;
			$query = $query->andWhere(['between', 'datePublished', $startDate, $endDate]);
			$selectedPeriod = $_GET['period'];
		}
		$countQuery = clone $query;
		$postCount = $countQuery->count();
		$pages = new Pagination(['totalCount' => $postCount, 'pageSize' => $pageSize]);
		$models = $query->offset($pages->offset)->limit($pageSize)->all();

		$sidebar = $this->getSidebar();

		$articleClass = [
			'0' => 'article size-split',
			'1' => 'article size-small',
			'2' => 'article size-small',
			'3' => 'article size-small',
			'4' => 'article size-big',
			'5' => 'article size-big',
			'6' => 'article size-small',
			'7' => 'article size-small',
			'8' => 'article size-small',
		];

		return $this->render('channel', array(
			'model' => $model,
			'pages' => $pages,
			'posts' => $models,
			'postCount' => $postCount,
			'dataSidebar' => $sidebar,
			'articleClass' => $articleClass,
			'searchTerm' => $searchTerm,
			'institutionId' => $institutionId,
			'months' => $months,
			'selectedPeriod' => $selectedPeriod
		));
	}

	public function actionTag($id)
	{
		$this->view->params['class'] = 'general';

		$tagSubscribed = 0;
		if(!Yii::$app->user->isGuest)
		{
			$userId = Yii::$app->user->identity->getId();
			$checkIfSubscribedToTag = TagSubscribe::findOne(['tagId' => $id, 'createdById' => $userId]);
			if($checkIfSubscribedToTag) $tagSubscribed = 1;
		}

		$pageSize = 9;
		$model = Tag::findOne($id);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		$query = Post::find()->joinWith('postTags')->where(['isActive' => 1])
			->andWhere(['tagId' => $model->id])->orderBy('id desc');

		$countQuery = clone $query;
		$postCount = $countQuery->count();
		$pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
		$models = $query->offset($pages->offset)->limit($pageSize)->all();

		$sidebar = $this->getSidebar();

		$articleClass = [
			'0' => 'article size-split',
			'1' => 'article size-small',
			'2' => 'article size-small',
			'3' => 'article size-small',
			'4' => 'article size-big',
			'5' => 'article size-big',
			'6' => 'article size-small',
			'7' => 'article size-small',
			'8' => 'article size-small',
		];

		return $this->render('tag', array(
			'model' => $model,
			'pages' => $pages,
			'posts' => $models,
			'tagSubscribed' => $tagSubscribed,
			'postCount' => $postCount,
			'dataSidebar' => $sidebar,
			'articleClass' => $articleClass
		));
	}

	public function actionArticle($id)
	{
		$this->view->params['class'] = 'video stopped-video';
		$postRecent = $postMostViewed = '';
		$userInstitutionChannelId = $channelSubscribed = $postLiked = 0;
		$postFavorite = $postLater =  $userChannelId = $channelAuthorSubscribed = 0;

		$model = Post::findOne(['Post.id' => $id]);
		if(!$model || (!$model->isActive && (($model->video && !Yii::$app->user->can('ApproveVideo')) || (!$model->video && !Yii::$app->user->can('ApprovePost')))))
		{
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
		}
		$model->updatingNumberOfViews();

		$creator = User::findOne(['id' => $model->createdById]);
		$userChannel = Channel::findOne(['userId' => $creator->id, 'isActive' => 1]);
		if($userChannel)
		{
			$userChannelId = $userChannel->id;
		}

		$sidebar = $this->getSidebar();

		if($creator->institutionId)
		{
			$instChannel = Channel::findOne(['institutionId' => $creator->institutionId, 'isActive' => 1]);
			if($instChannel)
			{
				$userInstitutionChannelId = $instChannel->id;
				$postRecent = Post::find()->innerJoinWith('postChannels', false)->with('createdBy.institution')
					->where(['isActive' => 1, 'channelId' => $instChannel->id])
					->limit(6)->orderBy('id desc')->all();

				$postMostViewed = Post::find()->innerJoinWith('postChannels', false)->with('createdBy.institution')
					->where(['isActive' => 1, 'channelId' => $instChannel->id])
					->limit(6)->orderBy('views desc')->all();
			}
		}
		else
		{
			$postRecent = Post::find()->where(['isActive' => 1])->with('createdBy')->limit(6)->orderBy('id desc')->all();
			$postMostViewed = Post::find()->where(['isActive' => 1])->with('createdBy')->limit(6)->orderBy('views desc')->all();
		}
		
		$rePosted = true;
		if(!Yii::$app->user->isGuest)
		{
			$userId = Yii::$app->user->identity->getId();
			$checkIfSubscribedToChannel = ChannelSubscribe::findOne(['channelId' => $userInstitutionChannelId, 'createdById' => $userId]);
			if($checkIfSubscribedToChannel) $channelSubscribed = 1;

			$checkIfSubscribedToUserChannel = ChannelSubscribe::findOne(['channelId' => $userChannelId, 'createdById' => $userId]);
			if($checkIfSubscribedToUserChannel) $channelAuthorSubscribed = 1;

			$checkIfLikedPost = PostLike::findOne(['postId' => $model->id, 'createdById' => $userId]);
			if($checkIfLikedPost) $postLiked = 1;
	
			$checkIfLikedFavorite = PostFavorite::findOne(['postId' => $model->id, 'createdById' => $userId]);
			if($checkIfLikedFavorite) $postFavorite = 1;
	
			$checkIfInWatchLater = PostLater::findOne(['postId' => $model->id, 'createdById' => $userId]);
			if($checkIfInWatchLater) $postLater = 1;
			
			if($model->createdBy->institutionId != Yii::$app->user->identity->institutionId)
			{
				$checkIfInChannel = PostRepost::findOne(['institutionId' => Yii::$app->user->identity->institutionId, 'postId' => $model->id]);
				if(!$checkIfInChannel) $rePosted = false;
			}
		}

		$createdAt = new \DateTime($model->createdAt);
		$today = new \DateTime();
		$interval = $createdAt->diff($today);

		$shortDesc = $model->getDescription(100);
		$pictureUrl = rtrim(Url::home(true), '/') . $model->getPicBaseUrl('hasThumbPhoto') . $model->getPicName('hasThumbPhoto', true);

		Yii::$app->view->registerMetaTag(['property' => 'fb:app_id', 'content' => 657171661131386]);
		Yii::$app->view->registerMetaTag(['property' => 'og:type', 'content' => 'article']);
		Yii::$app->view->registerMetaTag(['property' => 'og:title', 'content' => $model->title]);
		Yii::$app->view->registerMetaTag(['property' => 'og:url', 'content' => Url::canonical()]);
		Yii::$app->view->registerMetaTag(['property' => 'og:description', 'content' => $shortDesc]);
		Yii::$app->view->registerMetaTag(['property' => 'og:image', 'content' => $pictureUrl]);

		Yii::$app->view->registerMetaTag(['name' => 'twitter:site', 'content' => '@FusFoo']);
		Yii::$app->view->registerMetaTag(['name' => 'twitter:card', 'content' => 'summary_large_image']);
		Yii::$app->view->registerMetaTag(['name' => 'twitter:title', 'content' => $model->title]);
		Yii::$app->view->registerMetaTag(['name' => 'twitter:description', 'content' => $shortDesc]);
		Yii::$app->view->registerMetaTag(['name' => 'twitter:image', 'content' => $pictureUrl]);

		return $this->render('article', array(
			'model' => $model,
			'dataSidebar' => $sidebar,
			'user' => $creator,
			'postRecent' => $postRecent,
			'postMostViewed' => $postMostViewed,
			'userInstitutionChannelId' => $userInstitutionChannelId,
			'userChannelId' => $userChannelId,
			'channelSubscribed' => $channelSubscribed,
			'channelAuthorSubscribed' => $channelAuthorSubscribed,
			'postLiked' => $postLiked,
			'postFavorite' => $postFavorite,
			'postLater' => $postLater,
			'daysCreatedAgo' => $interval->days,
			'tags' => $model->tags,
			'channels' => $model->getChannels()->andWhere(['isSystem' => 0])->all(),
			'rePosted' => $rePosted
		));
	}

	public function actionProfile($id)
	{
		$data = $this->setupProfileLayout($id);
		$pageSize = 8;
		$data['modelChannel'] = $data['modelPost'] = [];
		$data['pages'] = 0;
		$post = $activity = false;

		switch($data['page'])
		{
			case 'like':
				$post = Post::find()->innerJoinWith('postLikes', false)->with('createdBy.institution')->where(['PostLike.createdById' => $id, 'Post.isActive' => 1]);
				break;
			case 'subscriptions':
				$data['subscriptionSchools'] = Institution::find()->innerJoinWith('channels.channelSubscribes', false)->where(['ChannelSubscribe.createdById' => $id, 'Institution.isActive' => 1])->andWhere(['IS NOT', 'institutionId', NULL])->offset(0)->limit(100)->all();
				$data['subscriptionUsers'] = User::find()->innerJoinWith('channels1.channelSubscribes', false)->with('institution')->where(['ChannelSubscribe.createdById' => $id, 'User.status' => User::STATUS_ACTIVE])->andWhere(['IS NOT', 'userId', NULL])->offset(0)->limit(100)->all();
				$data['tags'] = Tag::find()->innerJoinWith('tagSubscribes', false)->where(['TagSubscribe.createdById' => $id, 'Tag.isActive' => 1])->offset(0)->limit(100)->all();
				break;
			case 'activity':
				$activity = UserActivity::find()->where(['createdById' => $id])->orderBy('createdAt asc');
				break;
			case 'watchLater':
				$post = Post::find()->innerJoinWith('postLaters', false)->with('createdBy.institution')->where(['PostLater.createdById' => $id, 'Post.isActive' => 1]);
				break;
			case 'favorite':
				$post = Post::find()->innerJoinWith('postFavorites', false)->with('createdBy.institution')->where(['PostFavorite.createdById' => $id, 'Post.isActive' => 1]);
				break;
			case 'latest':
			default:
				$post = Post::find()->with('createdBy.institution')->where(['createdById' => $id, 'Post.isActive' => 1]);
				break;
		}

		/**
		 * @var ActiveQuery $activity
		 */
		if($activity)
		{
			$pageSize = 30;
			$countQuery = clone $activity;
			$data['pages'] = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
			$data['activity'] = $activity->offset($data['pages']->offset)->limit($pageSize)->orderBy('id desc')->all();
		}

		/**
		 * @var ActiveQuery $post
		 */
		if($post)
		{
			$countQuery = clone $post;
			$data['pages'] = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => $pageSize]);
			$data['post'] = $post->offset($data['pages']->offset)->limit($pageSize)->orderBy('id desc')->all();
		}

		$data['profileContent'] = $this->renderPartial($data['page'] != 'subscriptions' ? 'profileContent' : 'profileContentSubscriptions', $data);

		return $this->render('profile', $data);
	}

	public function actionSchool($id)
	{
		$this->view->params['class'] = 'school';
		$model = Institution::findOne(['id' => $id]);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
		$channel = $model->getChannel();
		$model->updatingNumberOfViews();

		$channelSubscribed = $isLiked = 0;
		$page = '';
		$members = [];
		$archivedMembers = [];

		if($model->hasLatestPhoto && !isset($_GET['about']))
		{
			$postLatestLimit = 8;
			$latestPictureUrl = $model->getPicBaseUrl('hasLatestPhoto') . $model->getPicName('hasLatestPhoto', true);
		}
		else
		{
			$postLatestLimit = 9;
			$latestPictureUrl = '';
		}

		if($model->latestLink)
		{
			$latestUrl = $model->latestLink;
			if(stripos($latestUrl, 'http') !== 0) $latestUrl = 'http://'. $latestUrl;
		}
		else $latestUrl = '#';

		$articleClassLatest = [
			'0' => 'article size-2x2',
			'1' => 'article size-1x1',
			'2' => 'article size-1x1',
			'3' => 'article size-1x1',
			'4' => 'article size-1x1',
			'5' => 'article size-1x1',
			'6' => 'article size-1x1',
			'7' => 'article size-1x1',
			'8' => 'article size-1x1',
		];

		$articleClassMustSee = [
			'0' => 'article size-2x2 float-right',
			'1' => 'article size-1x1',
			'2' => 'article size-1x1',
			'3' => 'article size-1x1',
			'4' => 'article size-1x1',
			'5' => 'article size-1x1',
			'6' => 'article size-1x1',
			'7' => 'article size-1x1',
			'8' => 'article size-1x1',
		];

		if(isset($_GET['about']))
		{
			$page = 'about';
			$members = User::find()->innerJoinWith('studentsFeatureds', false)->where(['User.institutionId' => $id, 'status' => 'active'])
				->orderBy('sort asc')->limit(60)->all();
			if(!$members)
			{
				$members = User::find()->where(['institutionId' => $id, 'status' => 'active'])
					->limit(60)->orderBy('id desc')->all();
			}

			$archivedMembers = User::find()->innerJoinWith('studentsArchiveds', false)->where(['User.institutionId' => $id, 'status' => 'archived'])
				->orderBy('sort asc')->limit(60)->all();
			$articleClassLatest = $articleClassMustSee;
		}

		$postLatest = Post::find()->innerJoinWith('postChannels', false)->with('createdBy.institution')->where(['channelId' => $channel->id, 'isActive' => 1])->limit($postLatestLimit)->orderBy('datePublished desc, id desc')->all();
		$postMustSee = Post::find()->innerJoinWith('postChannels', false)->innerJoinWith('postFeatureds', false)->with('createdBy.institution')->where(['channelId' => $channel->id, 'isActive' => 1, 'PostFeatured.institutionId' => $id])->limit(9)->orderBy('sort asc')->all();

		$featuredTags = TagFeatured::find()->with('tag')->where(['institutionId' => $id])->orderBy('sort desc')->all();
		$dataTag = [];
		if($featuredTags)
		{
			foreach($featuredTags as $one)
			{
				$postTag = Post::find()
					->innerJoinWith('postTags', false)
					->innerJoinWith('postChannels', false)
					->with('createdBy.institution')
					->where(['channelId' => $channel->id, 'isActive' => 1, 'tagId' => $one['tagId']])
					->limit(6)
					->orderBy('datePublished desc, id desc')
					->all();
				if($postTag)
				{
					$dataTag[] = [
						'model' => $postTag,
						'title' => $one['tag']['name']
					];
				}
			}
		}

		if(!Yii::$app->user->isGuest)
		{
			$userId = Yii::$app->user->identity->getId();
			$checkIfLiked = InstitutionLike::findOne(['institutionId' => $model->id, 'createdById' => $userId]);
			if($checkIfLiked) $isLiked = 1;
			$checkIfSubscribedToChannel = ChannelSubscribe::findOne(['channelId' => $channel->id, 'createdById' => $userId]);
			if($checkIfSubscribedToChannel) $channelSubscribed = 1;
		}

		return $this->render('school', array(
			'model' => $model,
			'postLatest' => $postLatest,
			'postMustSee' => $postMustSee,
			'articleClassLatest' => $articleClassLatest,
			'articleClassMustSee' => $articleClassMustSee,
			'page' => $page,
			'members' => $members,
			'archivedMembers' => $archivedMembers,
			'schoolChannel' => $channel,
			'isLiked' => $isLiked,
			'channelSubscribed' => $channelSubscribed,
			'latestPictureUrl' => $latestPictureUrl,
			'latestUrl' => $latestUrl,
			'dataTag' => $dataTag
		));
	}

	public function actionResources()
	{
		$this->view->params['class'] = 'general';

		return $this->render('resources', array(
		));
	}

	public function actionResourcesPartial()
	{
		$this->view->params['class'] = 'general';
		$page = '';
		if(isset($_GET['contentType']) && $_GET['contentType']) $page = $_GET['contentType'];

		$model = Content::findOne(['urlSlug' => $page]);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		return $this->render('resourcesPartial', array(
			'model' => $model,
			'page' => $page
		));
	}

	public function actionSearch()
	{
		$this->view->params['class'] = 'general';

		if(isset($_GET['searchType']) && $_GET['searchType']) $page = $_GET['searchType'];
		else $page = 'default';

		$pageSize = 8;
		$searchTerm = $pagination = $message = '';
		$totalCount = $numResults = 0;
		$dataResults = [];

		$searchForArticleCount = $searchForVideoCount = $searchForMembersCount = 0;
		$searchForChannelCount = $searchForInstitutionCount = 0;

		if(isset($_GET['term']) && $_GET['term'])
		{
			$searchTerm = $_GET['term'];

			$postArticle =  Post::find()
				->with('createdBy.institution')
				->where(
					'isActive = 1 and video IS NULL and MATCH(fullTextContent) AGAINST (:search)',
					[':search' => $searchTerm]
				)
				->orderBy('id desc');

			$postVideo = Post::find()
				->with('createdBy.institution')
				->where(
					'isActive = 1 and video IS NOT NULL and MATCH(fullTextContent) AGAINST (:search)',
					[':search' => $searchTerm]
				)
				->orderBy('id desc');

			$members = User::find()->where(['like', 'firstName', $searchTerm])
				->orWhere(['like', 'lastName', $searchTerm])
				->orWhere(['like', 'concat(firstName, " ", lastName)', $searchTerm])->andWhere(['status' => 'active'])->orderBy('id desc');
			$channel = Channel::find()->where(['like', 'name', $searchTerm])->andWhere(['isActive' => 1, 'isSystem' => 0])
				->orderBy('id desc');
			$institution = Institution::find()->where(['like', 'name', $searchTerm])
				->andWhere(['isActive' => 1])->orderBy('id desc');

			$searchForArticleCount = $postArticle->count();
			$searchForVideoCount = $postVideo->count();
			$searchForChannelCount = $channel->count();
			$searchForMembersCount = $members->count();
			$searchForInstitutionCount = $institution->count();
			$totalCount = $searchForArticleCount + $searchForVideoCount + $searchForMembersCount + $searchForChannelCount + $searchForInstitutionCount;

			switch($page)
			{
				case 'article':
					$numResults = $searchForArticleCount;
					$pagination = new Pagination(['totalCount' => $searchForArticleCount, 'pageSize' => $pageSize]);
					$dataResults = $postArticle->with('createdBy')->offset($pagination->offset)->limit($pageSize)->all();
					break;
				case 'video':
					$numResults = $searchForVideoCount;
					$pagination = new Pagination(['totalCount' => $searchForVideoCount, 'pageSize' => $pageSize]);
					$dataResults = $postVideo->with('createdBy')->offset($pagination->offset)->limit($pageSize)->all();
					break;
				case 'channel':
					$numResults = $searchForChannelCount;
					$pagination = new Pagination(['totalCount' => $searchForChannelCount, 'pageSize' => $pageSize]);
					$dataResults = $channel->offset($pagination->offset)->limit($pageSize)->all();
					break;
				case 'members':
					$numResults = $searchForMembersCount;
					$pagination = new Pagination(['totalCount' => $searchForMembersCount, 'pageSize' => $pageSize]);
					$dataResults = $members->offset($pagination->offset)->limit($pageSize)->all();
					break;
				case 'school':
					$numResults = $searchForInstitutionCount;
					$pagination = new Pagination(['totalCount' => $searchForInstitutionCount, 'pageSize' => $pageSize]);
					$dataResults = $institution->offset($pagination->offset)->limit($pageSize)->all();
					break;
				default:
					$dataResults = array_merge($postArticle->with('createdBy')->all(), $postVideo->with('createdBy')->all(), $channel->all(), $members->all(), $institution->all());
					$provider = new ArrayDataProvider([
						'allModels' => $dataResults,
						'pagination' => [
							'pageSize' => $pageSize,
							'totalCount' => $totalCount
						],
					]);
					$dataResults = $provider->getModels();
					$numResults = $totalCount;
					$pagination = $provider->getPagination();
					break;
			}
		}
		else $message = "Search term has not been entered.";

		return $this->render('search', array(
			'page' => $page,
			'message' => $message,
			'searchTerm' => $searchTerm,
			'totalCount' => $totalCount,
			'numResults' => $numResults,
			'pagination' => $pagination,
			'dataResults' => $dataResults,
			'searchForArticleCount' => $searchForArticleCount,
			'searchForVideoCount' => $searchForVideoCount,
			'searchForMembersCount' => $searchForMembersCount,
			'searchForChannelCount' => $searchForChannelCount,
			'searchForInstitutionCount' => $searchForInstitutionCount,
		));
	}

	public function actionSearchInstitution()
	{
		$this->view->params['class'] = 'general';

		$message = '';
		$pagination = $countQuery = 0;
		$pageSize = 8;
		$data = [];

		if(isset($_GET['SchoolSearchForm']))
		{
			$schoolSearch = Institution::find()->joinWith('city')->where(['Institution.isActive' => 1]);

			if(isset($_GET['SchoolSearchForm']['schoolName']) && $_GET['SchoolSearchForm']['schoolName'])
			{
				$schoolSearch->andWhere(['LIKE', 'Institution.name', $_GET['SchoolSearchForm']['schoolName']]);
			}

			if(isset($_GET['SchoolSearchForm']['stateId']) && $_GET['SchoolSearchForm']['stateId'])
			{
				$schoolSearch->andWhere(['stateId' => $_GET['SchoolSearchForm']['stateId']]);
			}

			if(isset($_GET['SchoolSearchForm']['cityId']) && $_GET['SchoolSearchForm']['cityId'])
			{
				$schoolSearch->andWhere(['LIKE', 'City.name', $_GET['SchoolSearchForm']['cityId']]);
			}

			if(isset($_GET['SchoolSearchForm']['zip']) && $_GET['SchoolSearchForm']['zip'])
			{
				$schoolSearch->andWhere(['zip' => $_GET['SchoolSearchForm']['zip']]);
			}

			$countQuery = $schoolSearch->count();
			$pagination = new Pagination(['totalCount' => $countQuery, 'pageSize' => $pageSize]);
			$data = $schoolSearch->offset($pagination->offset)->limit($pageSize)->all();
		}
		else $message = "Search terms have not been entered.";

		return $this->render('searchInstitution', array(
			'pagination' => $pagination,
			'dataResults' => $data,
			'numResults' => $countQuery,
			'message' => $message
		));
	}

	public function actionRequestPasswordReset()
	{
		$this->view->params['class'] = 'general';

		$model = new PasswordResetRequestForm();
		if($model->load(Yii::$app->request->post()) && $model->validate())
		{
			if($model->sendEmail())
			{
				$this->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
			}
			else
			{
				$this->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
			}
			return $this->refresh();
		}

		return $this->render('requestPasswordReset', ['model' => $model]);
	}

	/**
	 * Validates user and data and saves new password
	 * @param int $u
	 * @param string $key
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionResetPassword($u, $key)
	{
		$user = User::findIdentity($u);
		if(!$user || !$user->isPasswordResetTokenValid($key)) throw new BadRequestHttpException(Yii::t('app', 'The user and/or key is invalid.'));
		$user->passwordHash = 'xxx';
		$user->removePasswordResetToken();
		$user->setScenario('passReset');

		if($user->save())
		{
			if(Yii::$app->getUser()->login($user, Yii::$app->params['user.loginShortRememberMeDuration']))
			{
				$this->setFlash('error', Yii::t('app', 'Please update your password now!'));
				return $this->redirect('/user/profile-update.html');
			}
		}
		throw new BadRequestHttpException(Yii::t('app', 'Update failed.'));
	}

	public function actionContent()
	{
		$this->view->params['class'] = 'general';

		if(isset($_GET['contentType']) && $_GET['contentType']) $page = $_GET['contentType'];
		else $page = 'about';

		$model = Content::findOne(['urlSlug' => $page]);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		return $this->render('content', array(
			'model' => $model,
			'page' => $page
		));
	}

	public function getSidebar()
	{
		return Channel::find()->where(['isActive' => 1, 'isSystem' => 0])->andWhere(['>', 'numPosts', 1])
			->orderBy('rand() desc')->limit(3)->all();
	}

	public function actionSchoolSearch()
	{
		$this->view->params['class'] = 'general';
		$model = new SchoolSearchForm();

		return $this->render('schoolSearch', [
			'model' => $model,
		]);
	}

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

		$this->view->params['class'] = 'general';
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
		Yii::$app->user->logout();
        $cookies = Yii::$app->response->cookies;
        $cookies->remove('to_subscribe_to_mailchimp');
		return $this->goHome();
    }

    public function actionContact()
    {
		$this->view->params['class'] = 'general';

		$model = new ContactForm();
        if($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail']))
		{
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

	public function actionSchoolContact($institutionId)
	{
		$this->view->params['class'] = 'general';
		$institution = Institution::findOne(['id' => $institutionId]);
		if(!$institution) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

		$admin = User::find()->innerJoin('auth_assignment', 'auth_assignment.user_id = User.id and auth_assignment.item_name = "SchoolAdmin"')->where(['institutionId' => $institutionId, 'status' => 'active'])->one();

		$model = new SchoolContactForm();
		if($model->load(Yii::$app->request->post()) && $model->contact($admin->email))
		{
			Yii::$app->session->setFlash('contactFormSubmitted');
			return $this->refresh();
		}

		return $this->render('schoolContact', [
			'model' => $model,
			'institution' => $institution
		]);
	}

	public function actionContactFusfoo()
	{
		$this->view->params['class'] = 'general';

		$model = new ContactFusFooForm();
		if($model->load(Yii::$app->request->post()) && $model->contact('info@fusfoo.com'))
		{
			Yii::$app->session->setFlash('contactFormSubmitted');
			return $this->refresh();
		}

		return $this->render('contactFusFoo', [
			'model' => $model,
		]);
	}

    public function actionAbout()
    {
        return $this->render('about');
    }

	/**
	 * Register as new user
	 * @param null|int $schoolId
	 * @return Response|array|string
	 */
	public function actionRegister($schoolId = null)
	{
		$this->view->params['class'] = 'general';
		$model = new RegisterForm();
		$model->school = $schoolId;
		$regDone = 0;
		if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
		{
            Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model, ['email', 'username', 'password', 'school', 'firstName', 'lastName']);
		}
		if($model->load(Yii::$app->request->post())) $regDone = $model->register();
		return $this->render('register', [
			'model' => $model,
			'regDone' => $regDone
		]);
	}

	public function actionBrowseSchool()
	{
		$this->view->params['class'] = 'general';

		if(isset($_GET['searchType']) && $_GET['searchType']) $page = $_GET['searchType'];
		else $page = 'default';

		$institution = Institution::find()->innerJoinWith('city')
			->where(['Institution.isActive' => 1])->orderBy('Institution.name, City.name asc')->all();

		$schoolArray = [];
		/**
		 * @var Institution $school
		 */
		foreach($institution as $school)
		{
			$schoolNameFirstLetter = substr($school->name, 0, 1);
			if($page == 'city')
			{
				if(isset($schoolArray[$school->city->name])) $schoolArray[$school->city->name]['schools'][$school->id] = $school;
				else
				{
					$schoolArray[$school->city->name]['name'] = $school->city->name;
					$schoolArray[$school->city->name]['class'] = ' city' . substr($school->city->name, 0, 1);
					$schoolArray[$school->city->name]['schools'][$school->id] = $school;
				}
			}
			elseif($page == 'state')
			{
				if(isset($schoolArray[$school->city->stateId])) $schoolArray[$school->city->stateId]['schools'][$school->id] = $school;
				else
				{
					$schoolArray[$school->city->stateId]['name'] = $school->city->state->name;
					$schoolArray[$school->city->stateId]['class'] = ' state' . substr($school->city->state->name, 0, 1);
					$schoolArray[$school->city->stateId]['schools'][$school->id] = $school;
				}
			}
			else
			{
				if(isset($schoolArray[$schoolNameFirstLetter])) $schoolArray[$schoolNameFirstLetter]['schools'][$school->id] = $school;
				else
				{
					$schoolArray[$schoolNameFirstLetter]['name'] = $schoolNameFirstLetter;
					$schoolArray[$schoolNameFirstLetter]['class'] = ' default' . $schoolNameFirstLetter;
					$schoolArray[$schoolNameFirstLetter]['schools'][$school->id] = $school;
				}
			}
		}
		if($page == 'default') ksort($schoolArray);

		return $this->render('browseSchool', [
			'model' => $schoolArray,
			'page' => $page,
		]);
	}

	/**
	 * @return string|Response
	 */
	public function actionAccountActivation()
	{
		$this->view->params['class'] = 'general';

		$model = new AccountActivationForm();
		if($model->load(Yii::$app->request->post()) && $model->validate())
		{
			$post = Yii::$app->request->post();
			$email = $post['AccountActivationForm']['email'];
			$findUser = User::find()->where(['email' => $email])->one();
			if($findUser)
			{
				if($findUser->status == User::STATUS_ACTIVE)
				{
					$this->setFlash('success', Yii::t('app', 'Account is already activated, please login with your data.'));
					return $this->redirect('/site/login.html');
				}
				else
				{
					if($model->sendEmail())
					{
						$this->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
					}
					else
					{
						$this->setFlash('error', Yii::t('app', 'Sorry, we are unable to send activation link for email provided.'));
					}
					return $this->refresh();
				}
			}
			else
			{
				$this->setFlash('error', Yii::t('app', 'Account does not exist, please register with your data.'));
				return $this->redirect('/site/register.html');
			}
		}

		return $this->render('accountActivation', ['model' => $model]);
	}

	/**
	 * Flush cache if logged in as admin
	 */
	public function actionFlushCache()
	{
		if(Yii::$app->user->can('SuperAdmin'))
		{
			Yii::$app->cache->flush();
			echo "OK";
		}
		else echo "-";
		Yii::$app->end();
	}

	/**
	 * Regenerate fulltext content if logged in as admin
	 */
	public function actionRegenerateFulltext()
	{
		if(Yii::$app->user->can('SuperAdmin'))
		{
			/**
			 * @var $one Post model
			 */
			foreach(Post::find()->with('tags')->each() as $one)
			{
				$one->fullTextContent = $one->getFulltextContent($one->tags);
				$one->updateAttributes(['fullTextContent' => $one->fullTextContent]);
			}
			echo "OK";
		}
		else echo "-";
		Yii::$app->end();
	}

    public function actionArticleForm($id)
    {
        $model = Form::findOne(['Form.id' => $id]);
        if(!$model || (!($model->isActive && $model->isApproved) && !Yii::$app->user->can("SuperAdmin")))
        {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }

        $formMostViewed = Form::find()->where(['isActive' => 1])->limit(6)->orderBy('id desc')->all();
        $createdAt = new \DateTime($model->createdAt);
        $today = new \DateTime();
        $interval = $createdAt->diff($today);

        return $this->render('articleForm', array(
            'model' => $model,
            'formMostViewed' => $formMostViewed,
            'daysCreatedAgo' => $interval->days,
        ));
    }
}

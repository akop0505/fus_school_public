<?php

namespace app\controllers;

use app\models\Contest;
use app\models\Form;
use app\models\search\ContestSearch;
use Yii;
use app\models\forms\PostMediaUploadForm;
use yii\web\UploadedFile;
use app\models\Institution;
use app\models\TagFeatured;
use app\models\PostMedia;
use app\models\StudentsFeatured;
use app\models\StudentsArchived;
use app\models\UserActivity;
use app\models\UserViews;
use app\models\Channel;
use app\models\Post;
use app\models\PostFeatured;
use app\models\PostRepost;
use app\models\search\PostRepostSearch;
use app\models\search\PostSearch;
use app\models\search\UserSearch;
use app\models\User;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\db\Expression;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use app\botr\BotrAPI;
use app\controllers\common\BaseUserController;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookSDKException;
use app\models\search\FormSearch;

class ProfileController extends BaseUserController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$b = parent::behaviors();
		unset($b['preloadModel']);
		return $b;
	}

	protected function findModel($id)
	{
		return null;
	}

	/**
	 * Spellcheck helper
	 * @return array|\StdClass
	 */
	public function actionSpellcheck()
	{
		$action = Yii::$app->request->post('action', 'get_incorrect_words');
		$pspell = pspell_new('en_US', '', '', 'utf-8');
		\Yii::$app->response->format = Response::FORMAT_JSON;
		if($action == 'get_suggestions')
		{
			$word = Yii::$app->request->post('word', '');
			return pspell_suggest($pspell, $word);
		}
		else
		{
			$text = Yii::$app->request->post('text', []);
			$words = explode(' ', $text[0]);
			$ret = [];
			foreach($words as $word)
			{
				if(!pspell_check($pspell, $word)) $ret[] = $word;
			}
			$response = new \StdClass();
			$response->outcome = 'success';
			$response->data = [$ret];
		}
		return $response;
	}

	/**
	 * List all posts user can administer
	 * @return string
	 */
	public function actionPosts()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;
		$searchModel = new PostSearch();
		$searchData = Yii::$app->request->queryParams;
		if(Yii::$app->user->can('ApprovePost') || Yii::$app->user->can('ApproveVideo')) $searchData['PostSearch']['institutionId'] = $user->institutionId;
		else $searchData['PostSearch']['createdById'] = $user->id;
		$dataProvider = $searchModel->search($searchData);
		$dataProvider->pagination->pageSize = 20;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = isset($searchData['page']) && !empty($searchData['page']) ? $searchData['page'] : 'postsAdmin';
		$data['profileContent'] = $this->renderPartial('posts', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'user' => $user
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * List all posts user can administer
	 * @return string
	 */
	public function actionFeatured()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;

		$institutionChannel = Channel::findOne(['institutionId' => $user->institutionId]);

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'postsFeaturedAdmin';
		$data['profileContent'] = $this->renderPartial('featured', [
			'sortMax' => 9,
			'user' => $user,
			'channelId' => $institutionChannel->id
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * @param $institutionId
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionSaveFeatured($institutionId)
	{
		$post = Yii::$app->request->post('featuredPost', []);
		if($post)
		{
			PostFeatured::deleteAll(['institutionId' => $institutionId]);
			$postIdUsed = [];
			foreach($post as $key => $one)
			{
				if(!$one || in_array($one, $postIdUsed)) continue;
				else
				{
					$model = new PostFeatured();
					$model->postId = $one;
					$model->institutionId = $institutionId;
					$model->sort = $key;
					if(!$model->save()) throw new BadRequestHttpException(Yii::t('app', 'Featured post not saved.'));
					else $postIdUsed[] = $one;
				}
			}
			$this->setFlash('success', Yii::t('app', 'Data saved.'));
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * List all students user can administer
	 * @return string
	 */
	public function actionFeaturedStudents()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'studentsFeaturedAdmin';
		$data['profileContent'] = $this->renderPartial('featuredStudents', [
			'sortMax' => 60,
			'user' => $user
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * List all students user can administer
	 * @return string
	 */
	public function actionArchivedStudents()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'studentsArchivedAdmin';
		$data['profileContent'] = $this->renderPartial('archivedStudents', [
			'sortMax' => 60,
			'user' => $user
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * @param $institutionId
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionSaveStudentsFeatured($institutionId)
	{
		$post = Yii::$app->request->post('featuredStudents', []);
		if($post)
		{
			StudentsFeatured::deleteAll(['institutionId' => $institutionId]);
			$studentsIdUsed = [];
			foreach($post as $key => $one)
			{
				if(!$one || in_array($one, $studentsIdUsed)) continue;
				else
				{
					$model = new StudentsFeatured();
					$model->userId = $one;
					$model->institutionId = $institutionId;
					$model->sort = $key;
					if(!$model->save()) throw new BadRequestHttpException(Yii::t('app', 'Students featured not saved.'));
					else $studentsIdUsed[] = $one;
				}
			}
			$this->setFlash('success', Yii::t('app', 'Data saved.'));
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * @param $institutionId
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionSaveStudentsArchived($institutionId)
	{
		$post = Yii::$app->request->post('archivedStudents', []);
		if($post)
		{
			StudentsArchived::deleteAll(['institutionId' => $institutionId]);
			$studentsIdUsed = [];
			foreach($post as $key => $one)
			{
				if(!$one || in_array($one, $studentsIdUsed)) continue;
				else
				{
					$model = new StudentsArchived();
					$model->userId = $one;
					$model->institutionId = $institutionId;
					$model->sort = $key;
					if(!$model->save()) throw new BadRequestHttpException(Yii::t('app', 'Students archived not saved.'));
					else $studentsIdUsed[] = $one;

					$user = User::findOne($one);
					$user->setAttribute('status', 'archived');
					$user->setAttribute('updatedAt',new Expression('UTC_TIMESTAMP()'));
					$user->save();

				}
			}
			$this->setFlash('success', Yii::t('app', 'Data saved.'));
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * List all students user can administer
	 * @return string
	 */
	public function actionFeaturedTags()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'tagsFeaturedAdmin';
		$data['profileContent'] = $this->renderPartial('featuredTags', [
			'sortMax' => 5,
			'user' => $user
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * @param $institutionId
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionSaveTagsFeatured($institutionId)
	{
		$post = Yii::$app->request->post('featuredTags', []);
		if($post)
		{
			TagFeatured::deleteAll(['institutionId' => $institutionId]);
			$tagsIdUsed = [];
			foreach($post as $key => $one)
			{
				if(!$one || in_array($one, $tagsIdUsed)) continue;
				else
				{
					$model = new TagFeatured();
					$model->tagId = $one;
					$model->institutionId = $institutionId;
					$model->sort = $key;
					if(!$model->save()) throw new BadRequestHttpException(Yii::t('app', 'Tags featured not saved.'));
					else $tagsIdUsed[] = $one;
				}
			}
			$this->setFlash('success', Yii::t('app', 'Data saved.'));
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * List all reposted posts user can administer
	 * @return string
	 */
	public function actionRepost()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;
		$searchModel = new PostRepostSearch();
		$searchData = Yii::$app->request->queryParams;
		if(Yii::$app->user->can('ApprovePost') || Yii::$app->user->can('ApproveVideo')) $searchData['PostRepostSearch']['institutionId'] = $user->institutionId;
		else $searchData['PostRepostSearch']['createdById'] = $user->id;
		$dataProvider = $searchModel->search($searchData);
		$dataProvider->pagination->pageSize = 20;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'repostsAdmin';
		$data['profileContent'] = $this->renderPartial('repost', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'user' => $user
		]);
		return $this->render('/site/profile', $data);
	}

	public function actionDeletePostRepost($postId, $institutionId)
	{
		$find = PostRepost::findOne(['postId' => $postId, 'institutionId' => $institutionId]);
		$find->delete();
		return $this->redirect(['/profile/repost']);
	}

	/**
	 * List school users
	 * @return string
	 * @throws ForbiddenHttpException
	 */
	public function actionUsers()
	{
		/**
		 * @var User $user
		 */
		$user = Yii::$app->user->identity;
		$searchModel = new UserSearch();
		$searchData = Yii::$app->request->queryParams;
		if(Yii::$app->user->can('ApproveUser')) $searchData['UserSearch']['institution'] = $user->institutionId;
		else throw new ForbiddenHttpException();
		$dataProvider = $searchModel->search($searchData);
		$dataProvider->pagination->pageSize = 20;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'usersAdmin';
		$data['profileContent'] = $this->renderPartial('users', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'user' => $user
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Activate / deactivate post
	 * @param int $id
	 * @param int $promote
	 * @return Response
	 * @throws ForbiddenHttpException
	 */
	public function actionTogglePost($id, $promote)
	{
		$model = Post::findOne($id);
		if($model)
		{
			if($model->createdBy->institutionId != Yii::$app->user->identity->institutionId && !Yii::$app->user->can('ContentAdmin')) throw new ForbiddenHttpException();
			$isAdmin = (Yii::$app->user->can('SchoolAdmin') || Yii::$app->user->can($model->video ? 'ApproveVideo' : 'ApprovePost'));
			$isAuthor = Yii::$app->user->can('SchoolAuthor');
			if(!$isAdmin && (!$isAuthor || $model->createdById != Yii::$app->user->id)) throw new ForbiddenHttpException();
			if(!$model->isApproved && $promote) throw new ForbiddenHttpException();

			if($promote && !$model->isActive)
			{
				if(!$model->hasHeaderPhoto || !$model->hasThumbPhoto) $this->setFlash('error', Yii::t('app', 'Post can not be published because there is no picture.'));
				else
				{
					$params = [
						'isActive' => 1,
						'updatedById' => Yii::$app->user->id,
						'updatedAt' => new Expression('UTC_TIMESTAMP()')
					];
					if($model->isApproved)
					{
						$model->approvedById = Yii::$app->user->id;
						$params['datePublished'] = new Expression('UTC_TIMESTAMP()');
						Yii::warning('1 Approved '. $id .' by '. Yii::$app->user->id);
					}
					$model->updateAttributes($params);
					$model->isActive = 1;
					$model->updateDefaultChannels();
					$this->actionPublishToFacebook($id);
				}
			}
			elseif(!$promote && $model->isActive)
			{
				$model->updateAttributes(['isActive' => 0, 'updatedById' => Yii::$app->user->id, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);
				$model->isActive = 0;
				$model->updateDefaultChannels();
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Approved / refuse post
	 * @param int $id
	 * @param int $approve
	 * @return Response
	 * @throws ForbiddenHttpException
	 */
	public function actionTogglePostApprove($id, $approve)
	{
		$model = Post::findOne($id);
		if($model)
		{
			if($model->createdBy->institutionId != Yii::$app->user->identity->institutionId && !Yii::$app->user->can('ContentAdmin')) throw new ForbiddenHttpException();
			$isAdmin = Yii::$app->user->can($model->video ? 'ApproveVideo' : 'ApprovePost');
			if(!$isAdmin) throw new ForbiddenHttpException();

			if($approve && !$model->isApproved)
			{
				if(!$model->hasHeaderPhoto || !$model->hasThumbPhoto) $this->setFlash('error', Yii::t('app', 'Post can not be approved because there is no picture.'));
				else
				{
					$params = [
						'isApproved' => 1,
						'updatedById' => Yii::$app->user->id,
						'approvedById' => Yii::$app->user->id,
						'updatedAt' => new Expression('UTC_TIMESTAMP()')
					];
					if($model->isActive) $params['datePublished'] = new Expression('UTC_TIMESTAMP()');
					$model->updateAttributes($params);
					Yii::warning('2 Approved '. $id .' by '. Yii::$app->user->id);
					$this->actionPublishToFacebook($id);
				}
			}
			elseif(!$approve && $model->isApproved)
			{
				$model->updateAttributes(['isApproved' => 0, 'updatedById' => Yii::$app->user->id, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Publish to FB if post is approved and published
	 * @param int $id
	 * @return bool
	 * @throws BadRequestHttpException
	 */
	public function actionPublishToFacebook($id)
	{
		$user = Yii::$app->user->identity;
		$post = Post::findOne(['id' => $id, 'isApproved' => 1, 'isActive' => 1]);
		if(!$post) return false;
		$institution = $user->institution;
		if(!$institution->fbPageToken) return false;

		$fb = $this->getFacebook($institution);
		$fb->setDefaultAccessToken($institution->fbPageToken);

		try
		{
			$fb->post(
				'/'. $institution->fbPageId .'/feed',
				array(
					"message" => '',
					"link" => $post->getUrl(true),
					"picture" => '',
					"name" => '',
					"caption" => '',
					"description" => ''
				),
				$institution->fbPageToken
			);
		}
		catch(\Exception $e)
		{
			Yii::warning('FB post exception: '. get_class($e) .' '. $e->getMessage());
			return false;
		}
		return true;
	}

	/**
	 * Get Facebook obj
	 * @param Institution|bool $model
	 * @return Facebook
	 */
	protected function getFacebook($model = false)
	{
		return new Facebook([
			'app_id' => $model ? $model->fbAppId : Yii::$app->params['fbAppId'],
			'app_secret' => $model ? $model->fbAppSecret : Yii::$app->params['fbSecretKey'],
			'default_graph_version' => Yii::$app->params['fbApiVersion']
		]);
	}

	/**
	 * Link to facebook
	 * @return string|Response
	 * @throws ForbiddenHttpException
	 */
	public function actionFacebook()
	{
		if(!Yii::$app->user->can('SchoolAdmin')) throw new ForbiddenHttpException();
		$user = Yii::$app->user->identity;
		$model = $user->institution;

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'facebook';

		if(Yii::$app->request->post('deauthorize', 0))
		{
			$model->updateAttributes(['fbPageToken' => null, 'updatedById' => Yii::$app->user->id, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);
			$model->fbPageToken = false;
		}
		if($model->fbPageToken)
		{
			$message = 'Page ID: ' . $model->fbPageId . ' authorized!';
			$data['profileContent'] = $this->renderPartial('facebook', ['message' => $message]);
			return $this->render('/site/profile', $data);
		}

		$model->setScenario('fbConnect');
		if($model->load(Yii::$app->request->post()))
		{
			$session = Yii::$app->session;
			$session['tmpFbAppId'] = $model->fbAppId;
			$session['tmpFbAppSecret'] = $model->fbAppSecret;
			$fb = $this->getFacebook($model);

			$helper = $fb->getRedirectLoginHelper();
			$permissions = ['email', 'manage_pages', 'publish_pages'];
			$loginUrl = $helper->getLoginUrl(
				Url::to(
					['/profile/callback', 'pageId' => $model->fbPageId, 'institutionId' => $model->id],
					true
				),
				$permissions
			);

			return $this->redirect($loginUrl);
		}

		$data['profileContent'] = $this->renderPartial('facebook', ['model' => $model]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Callback after FB approve
	 * @param string $pageId
	 * @param int $institutionId
	 * @return Response
	 * @throws BadRequestHttpException
	 */
	public function actionCallback($pageId, $institutionId)
	{
		$model = Institution::findOne($institutionId);
		$session = Yii::$app->session;
		$model->fbAppId = $session['tmpFbAppId'];
		unset($session['tmpFbAppId']);
		$model->fbAppSecret = $session['tmpFbAppSecret'];
		unset($session['tmpFbAppSecret']);
		$fb = $this->getFacebook($model);
		$helper = $fb->getRedirectLoginHelper();
		try
		{
			$accessToken = $helper->getAccessToken();
			$appId = $model->fbAppId;
			$appSecret = $model->fbAppSecret;
			$response = $fb->get('/oauth/access_token?grant_type=fb_exchange_token&client_id=' . $appId .
				'&client_secret=' . $appSecret . '&fb_exchange_token=' . $accessToken, $accessToken);
			$extended = $response->getAccessToken();
			$account = $fb->get('/me?access_token=' . $extended, $extended)->getDecodedBody();
			$response = $fb->get('/' . $account['id'] . '/accounts?access_token=' . $accessToken, $accessToken)->getDecodedBody();
			$permanentToken = false;
			foreach($response['data'] as $page)
			{
				if($page['id'] == $pageId)
				{
					$permanentToken = $page['access_token'];
					break;
				}
			}
			if(!$permanentToken) throw new BadRequestHttpException(Yii::t('app', 'Token not found!'));

		}
		catch(FacebookSDKException $e)
		{
			throw new BadRequestHttpException(Yii::t('app', 'Facebook SDK returned an error: ' . $e->getMessage()));
		}

		if(isset($extended))
		{
			$model->updateAttributes([
				'fbPageId' => $pageId, 'fbPageToken' => $permanentToken,
				'fbAppId' => $model->fbAppId, 'fbAppSecret' => $model->fbAppSecret,
				'updatedById' => Yii::$app->user->id, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);

			return $this->redirect(['profile/facebook']);
		}
		throw new BadRequestHttpException(Yii::t('app', 'Unknown error.'));
	}

	/**
	 * Approved / refuse repost
	 * @param int $postId
	 * @param int $institutionId
	 * @param int $approve
	 * @return Response
	 * @throws ForbiddenHttpException
	 */
	public function actionToggleRepostApprove($postId, $institutionId, $approve)
	{
		$model = PostRepost::findOne(['postId' => $postId, 'institutionId' => $institutionId]);
		$post = Post::findOne(['id' => $postId]);
		if($model)
		{
			$isAdmin = Yii::$app->user->can($post->video ? 'ApproveVideo' : 'ApprovePost');
			if(!$isAdmin) throw new ForbiddenHttpException();

			if($approve && !$model->isApproved)
			{
				$model->isApproved = 1;
				$model->save();
			}
			elseif(!$approve && $model->isApproved)
			{
				$model->isApproved = 0;
				$model->save();
			}
		}
		return $this->redirect(Yii::$app->request->referrer);
	}

	/**
	 * Create video upload target
	 * @param int $id - post ID
	 * @return mixed|string
	 * @throws ForbiddenHttpException
	 */
	public function actionCreateVideo($id)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;
		$model = Post::findOne($id);
		if(!$model || ($model->createdBy->institutionId != Yii::$app->user->identity->institutionId && !Yii::$app->user->can('ContentAdmin'))) throw new ForbiddenHttpException();
		$isAdmin = Yii::$app->user->can('SchoolAdmin');
		$isAuthor = Yii::$app->user->can('SchoolAuthor');
		if(!$isAdmin && (!$isAuthor || $model->createdById != Yii::$app->user->id)) throw new ForbiddenHttpException();
		$botr = new BotrAPI(Yii::$app->params['jwp.api.key'], Yii::$app->params['jwp.api.secret']);
		$params = array('link' => $model->getUrl(true));
		if(isset($_GET['resumable'])) $params['resumable'] = 'True';
		return $botr->call('/videos/create', $params);
	}

	/**
	 * Analytics
	 * @return string
	 */
	public function actionAnalytics()
	{
		$limit = 5;
		$selectedPeriod = 1;

		if(isset($_POST['period']))
		{
			switch($_POST['period'])
			{
				case 2:
					$period = strtotime("-1 months");
					break;
				case 3:
					$period = strtotime("-6 months");
					break;
				case 4:
					$period = strtotime("-12 months");
					break;
				case 1:
				default:
					$period = strtotime("-7 day");
					break;
			}
			$selectedPeriod = $_POST['period'];
		}
		else $period = strtotime("-7 day");

		//$endDate = date('Y-m-d');
		$startDate =  date('Y-m-d', $period);
		$label = date('M d, Y', $period) . ' - ' .  date('M d, Y');
		$schoolName = Institution::findOne(Yii::$app->user->identity->institutionId);

		$mostWatchedSchools = $this->getMostWatchedSchool($startDate, $limit);
		$mostWatchedPost = $this->getMostWatchedPost($startDate, $limit);
		$mostActiveUser = $this->getMostActiveUser($startDate, $limit);
		$mostWatchedPostInSchool = $this->getMostWatchedPostInSchool($startDate, $limit);
		$mostLikedSchools = $this->getMostLikedSchools($startDate, $limit);
		$mostMostSubscribedUsers = $this->getMostSubscribedUsers($startDate, $limit);

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'analytics';
		$data['profileContent'] = $this->renderPartial('analytics', [
			'mostWatchedSchools' => $mostWatchedSchools,
			'mostActiveUser' => $mostActiveUser,
			'mostWatchedPost' => $mostWatchedPost,
			'mostWatchedPostInSchool' => $mostWatchedPostInSchool,
			'mostLikedSchools' => $mostLikedSchools,
			'mostMostSubscribedUsers' => $mostMostSubscribedUsers,
			'selectedPeriod' => $selectedPeriod,
			'schoolName' => $schoolName->name,
			'label' => $label
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Returns most subscribed users
	 * @param string $startDate
	 * @param integer $limit
	 * @return array
	 */
	private function getMostSubscribedUsers($startDate, $limit)
	{
		$data = [];
		$rows = (new Query())
			->select('count(*) as num, User.firstName, User.lastName')
			->from('ChannelSubscribe')
			->join('INNER JOIN', 'Channel', 'Channel.id = ChannelSubscribe.channelId')
			->join('INNER JOIN', 'User', 'User.id = Channel.userId')
			->where('User.institutionId = :institutionId and Channel.userId IS NOT NULL and ChannelSubscribe.createdAt >= :startDate',
				[
					':institutionId' => Yii::$app->user->identity->institutionId,
					':startDate' => $startDate . ' 00:00:00',
				]
			)
			->groupBy('User.id')
			->orderBy('num desc')
			->limit($limit)
			->all();

		if($rows)
		{
			$data[] = ['Name', 'Number of subscribes', ''];
			$counter = 0;
			$colors = $this->getChartColors();
			foreach($rows as $row)
			{
				$data[] = [$row['firstName'] . ' ' . $row['lastName'], (int)$row['num'], $colors[$counter]];
				$counter++;
			}
		}
		return $data;
	}

	/**
	 * Returns most liked schools
	 * @param string $startDate
	 * @param integer $limit
	 * @return array
	 */
	private function getMostLikedSchools($startDate, $limit)
	{
		$data = [];
		$rows = (new Query())
			->select('count(*) as num, Institution.name as name')
			->from('InstitutionLike')
			->join('INNER JOIN', 'Institution', 'Institution.id = InstitutionLike.institutionId')
			->where('InstitutionLike.createdAt >= :startDate',
				[
					':startDate' => $startDate . ' 00:00:00',
				]
			)
			->groupBy('Institution.id')
			->orderBy('num desc')
			->limit($limit)
			->all();

		if($rows)
		{
			$data[] = ['School', 'Number of likes', ''];
			$counter = 0;
			$colors = $this->getChartColors();
			foreach($rows as $row)
			{
				$data[] = [$row['name'], (int)$row['num'], $colors[$counter]];
				$counter++;
			}
		}
		return $data;
	}

	/**
	 * Returns most watched posts in school
	 * @param string $startDate
	 * @param integer $limit
	 * @return array
	 */
	private function getMostWatchedPostInSchool($startDate, $limit)
	{
		$data = [];
		$rows = (new Query())
			->select('count(*) as num, Post.title as postTitle')
			->from('UserViews')
			->join('INNER JOIN', 'Post', 'Post.id = UserViews.viewTypeFk')
			->join('INNER JOIN', 'User', 'User.id = Post.createdById')
			->where('User.institutionId = :institutionId and UserViews.viewType = :viewType and UserViews.createdAt >= :startDate',
				[
					':institutionId' => Yii::$app->user->identity->institutionId,
					':viewType' => UserViews::VIEWTYPE_POST,
					':startDate' => $startDate . ' 00:00:00',
				]
			)
			->groupBy('viewTypeFk')
			->orderBy('num desc')
			->limit($limit)
			->all();

		if($rows)
		{
			$data[] = ['Article', 'Number of views', ''];
			$counter = 0;
			$colors = $this->getChartColors();
			foreach($rows as $row)
			{
				$data[] = [$row['postTitle'], (int)$row['num'], $colors[$counter]];
				$counter++;
			}
		}
		return $data;
	}


	/**
	 * Returns most watched posts
	 * @param string $startDate
	 * @param integer $limit
	 * @return array
	 */
	private function getMostWatchedPost($startDate, $limit)
	{
		$data = [];
		$rows = (new Query())
			->select('count(*) as num, viewTypeFk as postId, Post.title as postTitle')
			->from('UserViews')
			->join('JOIN', 'Post', 'Post.id = UserViews.viewTypeFk')
			->where('viewType = :viewType and UserViews.createdAt >= :startDate',
				[
					':viewType' => UserViews::VIEWTYPE_POST,
					':startDate' => $startDate . ' 00:00:00',
				]
			)
			->groupBy('viewTypeFk')
			->orderBy('num desc')
			->limit($limit)
			->all();

		if($rows)
		{
			$data[] = ['Article', 'Number of views', ''];
			$counter = 0;
			$colors = $this->getChartColors();
			foreach($rows as $row)
			{
				$post = Post::findOne($row['postId']);
				$data[] = [$post->title, (int)$row['num'], $colors[$counter]];
				$counter++;
			}
		}
		return $data;
	}

	/**
	 * Returns most watched school
	 * @param string $startDate
	 * @param integer $limit
	 * @return array
	 */
	private function getMostWatchedSchool($startDate, $limit)
	{
		$data = [];
		$rows = (new Query())
			->select('count(*) as num, viewTypeFk as schoolId')
			->from('UserViews')
			->where('viewType = :viewType and createdAt >= :startDate',
				[
					':viewType' => UserViews::VIEWTYPE_SCHOOL,
					':startDate' => $startDate . ' 00:00:00',
				]
			)
			->groupBy('viewTypeFk')
			->orderBy('num desc')
			->limit($limit)
			->all();

		if($rows)
		{
			$data[] = ['School', 'Number of views', ''];
			$counter = 0;
			$colors = $this->getChartColors();
			foreach($rows as $row)
			{
				$school = Institution::findOne($row['schoolId']);
				$data[] = [$school->name, (int)$row['num'], $colors[$counter]];
				$counter++;
			}
		}
		return $data;
	}

	/**
	 * Returns most active users
	 * @param string $startDate
	 * @param integer $limit
	 * @return array
	 */
	private function getMostActiveUser($startDate, $limit)
	{
		$data = [];
		$rows = (new Query())
			->select('count(*) as num, User.firstName, User.lastName')
			->from('UserActivity')
			->join('INNER JOIN', 'User', 'User.id = UserActivity.createdById')
			->where('UserActivity.activityType = :activityType and User.institutionId = :institutionId and UserActivity.createdAt >= :startDate',
				[
					':activityType' => UserActivity::ACTIVITYTYPE_POST,
					':institutionId' => Yii::$app->user->identity->institutionId,
					':startDate' => $startDate . ' 00:00:00',
				]
			)
			->groupBy('User.id')
			->orderBy('num desc')
			->limit($limit)
			->all();
		if($rows)
		{
			$data[] = ['Name', 'Number of Posts', ''];
			$counter = 0;
			$colors = $this->getChartColors();
			foreach($rows as $row)
			{
				$data[] = [$row['firstName'] . ' ' . $row['lastName'], (int)$row['num'], $colors[$counter]];
				$counter++;
			}
		}
		return $data;
	}

	/**
	 * Chart colors
	 * @return array
	 */
	private function getChartColors()
	{
		return ['#FF4871', '#FFAE27', '#3AB5C4', '#4CAFFE', '#7E63FF', '#F0E600'];
	}

	/**
	 * Displays a single PostMedia model.
	 * @param int $postId
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionGallery($postId)
	{
		$model = Post::findOne($postId);
		if(!$model) throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
		$mediaPath = $model->getGalleryBasePath();
		$mediaDir = $model->getGalleryBaseUrl();
		$fileSize = [];
		$postMedia = PostMedia::find()->where(['postId' => $postId])->orderBy('sort asc')->all();

		foreach($postMedia as $i => $media)
		{
			$fileSize[$i] = filesize($mediaPath . $media->filename);
		}

		$data = $this->setupProfileLayout(Yii::$app->user->id);
		$data['page'] = 'gallery';
		$data['profileContent'] = $this->renderPartial('gallery', [
			'post' => $model,
			'postMedia' => $postMedia,
			'mediaDir' 	=> $mediaDir,
			'fileSize'	=> $fileSize
		]);
		return $this->render('/site/profile', $data);
	}

	/**
	 * Saves Gallery image and thumbnail by postId sent in post. Throws exception if upload error.
	 * @return string
	 * @throws BadRequestHttpException
	 */
	public function actionUploadPostGallery()
	{
		$postId = Yii::$app->request->post('postId');
		$post = Post::findOne($postId);
		if($post)
		{
			$post->checkPostPermission();
			$upload = new PostMediaUploadForm();
			if($upload->imageFile = UploadedFile::getInstanceByName('file'))
			{
				$uploadError = !$upload->upload($post, Yii::$app->request->post('fileSort', 0));
			}
			else $uploadError = true;

			if($uploadError) throw new BadRequestHttpException(Yii::t('app', 'Error.'));
			else
			{
				if(!Yii::$app->user->can('SchoolAdmin')) $post->isActive = $post->isApproved = 0;
				else $post->isApproved = $post->isActive;
				$post->save();
			}
		}
		else throw new BadRequestHttpException(Yii::t('app', 'Error.'));
		return '';
	}

	/**
	 * Deletes gallery images from filesystem and rows from database. Throws exception if post doesn't
	 * exist or removing went failed.
	 * @return string
	 * @throws BadRequestHttpException
	 */
	public function actionDeleteOneFromGallery()
	{
		$filename = Yii::$app->request->post('filename');
		$postId = Yii::$app->request->post('postId');

		$post = Post::findOne($postId);
		if($post)
		{
			$post->checkPostPermission();
			$upload = new PostMediaUploadForm();
			if($upload->removeImageByName($post, $filename))
			{
				$ret = PostMedia::deleteAll(['filename' => $filename, 'postId' => $postId]);
				if(!$ret) throw new BadRequestHttpException(Yii::t('app', 'Error.'));
				else
				{
					if(!Yii::$app->user->can('SchoolAdmin')) $post->isActive = $post->isApproved = 0;
					else $post->isApproved = $post->isActive;
					$post->save();
				}
			}
			else throw new BadRequestHttpException(Yii::t('app', 'Error.'));
		}
		else throw new BadRequestHttpException(Yii::t('app', 'Error.'));
		return '';
	}

    /**
     * List all forms user can administer
     * @return string
     */
    public function actionForm()
    {
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        $searchModel = new FormSearch();
        $searchData = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($searchData);
        $dataProvider->pagination->pageSize = 20;

        $data = $this->setupProfileLayout(Yii::$app->user->id);
        $data['page'] = 'contest';
        $data['profileContent'] = $this->renderPartial('forms', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user
        ]);
        return $this->render('/site/profile', $data);
    }

    /**
     * List all contests user can administer
     * @return string
     */
    public function actionContest()
    {
        /**
         * @var User $user
         */
        $user = Yii::$app->user->identity;
        $searchModel = new ContestSearch();
        $searchData = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($searchData);
        $dataProvider->pagination->pageSize = 20;

        $data = $this->setupProfileLayout(Yii::$app->user->id);
        $data['page'] = 'contest';
        $data['profileContent'] = $this->renderPartial('contests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user' => $user
        ]);
        return $this->render('/site/profile', $data);
    }

    /**
     * Approved / refuse form
     * @param int $id
     * @param int $approve
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionToggleFormApprove($id, $approve)
    {
        if(!Yii::$app->user->can('SuperAdmin')){
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        $model = Form::findOne($id);
        if($model)
        {
            if($approve && !$model->isApproved)
            {
                //if(!$model->hasHeaderPhoto || !$model->hasThumbPhoto) $this->setFlash('error', Yii::t('app', 'Post can not be approved because there is no picture.'));

                $params = [
                    'isApproved' => 1,
                    'approvedById' => Yii::$app->user->id,
                    'updatedAt' => new Expression('UTC_TIMESTAMP()'),
                ];
                //if($model->isActive) $params['datePublished'] = new Expression('UTC_TIMESTAMP()');
                $model->updateAttributes($params);
                Yii::warning('2 Approved '. $id .' by '. Yii::$app->user->id);
                //$this->actionPublishToFacebook($id);
            }
            elseif(!$approve && $model->isApproved)
            {
                $model->updateAttributes(['isApproved' => 0, 'updatedAt' => new Expression('UTC_TIMESTAMP()')]);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }
}

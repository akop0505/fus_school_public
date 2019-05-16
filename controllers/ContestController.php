<?php

namespace app\controllers;

use app\controllers\common\BaseAdminController;
use \app\models\Contest;
use Yii;
use yii\db\Expression;
use app\models\common\BaseUploadForm;
use yii\web\ForbiddenHttpException;
use app\botr\BotrAPI;


class ContestController extends BaseAdminController
{
    protected function findModel($id)
    {
        return Contest::findOne($id);
    }

    public function actionCreate() {

        $this->layout = '@app/views/layouts/main';
        $isSuperAdmin = Yii::$app->user->can('SuperAdmin');
        if(!$isSuperAdmin){
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $model = new Contest();
        $model->setScenario('insert');
        $hideForm = false;

        if($model->load(Yii::$app->request->post())) {
            $model->hasHeaderPhoto = 0;
            $model->type  = 'article';
            $model->createdById = Yii::$app->user->id;
            $model->isActive = 1;
            $model->datePublished = new Expression('UTC_TIMESTAMP()');
            if($model->validate() && $model->save()) {
                $ret = $this->uploadPicture($model->id);
                if($ret) return $this->redirect(['profile/contest']);
            }
        }

        $data = $this->setupProfileLayout(Yii::$app->user->id);
        $data['page'] = 'contest';
        $data['targetModel'] = $model;
        $data['hideForm'] = $hideForm;
        $data['profileContent'] = $this->renderPartial('create', $data);
        return $this->render('/site/profile', $data);
    }

    public function actionUpdate($id) {
        $this->layout = '@app/views/layouts/main';
        $isSuperAdmin = Yii::$app->user->can('SuperAdmin');
        if(!$isSuperAdmin) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $model = Contest::findOne(["id"=>$id]);

        if($model->load(Yii::$app->request->post())) {
            try{
                if(!$model->save()) {
                    throw new \Exception("The action to update the row was failed.");
                }
                $ret = $this->uploadPicture($model->id);
                if($ret) return $this->redirect(['profile/contest']);
            }
            catch (\Exception $e){
                throw new ForbiddenHttpException(Yii::t('yii', $e->getMessage()));
            }
        }
        $data = $this->setupProfileLayout(Yii::$app->user->id);
        $data['page'] = 'contest';
        $data['targetModel'] = $model;
        $data['profileContent'] = $this->renderPartial('update', $data);
        return $this->render('/site/profile', $data);
    }

    public function actionShow() {

    }
    /**
     * @param $id
     * @return bool|array
     */
    private function uploadPicture($id)
    {
        /**
         * @var Post $modelReal - reload to get correct class
         */
        $modelReal = $this->loadModel($id);
        $uploadError = $this->handleImageUpload($modelReal, 'hasHeaderPhoto', new BaseUploadForm());
        if(!$uploadError && $modelReal->save(false)) return ['view', 'id' => $id];
        elseif($uploadError) return ['update', 'id' => $id];
        else return false;
    }


    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->checkContestPermission();
        $transaction = Yii::$app->db->beginTransaction();
        try
        {
            $model->isActive = 0;
            $model->save();

            $this->deleteDirectory($model->getUploadBasePath());

            if($model->video) $this->actionRemoveVideo($id);

            $model->delete();

            $transaction->commit();
            $this->setFlash('success', Yii::t('app', 'Post deleted.'));
        }
        catch(\Exception $e)
        {
            $transaction->rollBack();
            $this->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Remove video from post - frontend
     * @param int $id
     * @return mixed
     * @throws ForbiddenHttpException
     */
    public function actionRemoveVideo($id)
    {
        $model = $this->findModel($id);
        $model->checkContestPermission();
        $oldVideo = $model->video;
        if($oldVideo)
        {
            $updateData = [
                'video' => new Expression('NULL'),
                'updatedById' => Yii::$app->user->id,
                'updatedAt' => new Expression('UTC_TIMESTAMP()')
            ];
            $model->updateAttributes($updateData);
            $botr = new BotrAPI(Yii::$app->params['jwp.api.key'], Yii::$app->params['jwp.api.secret']);
            $botr->call('/videos/delete', ['video_key' => $oldVideo]);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

}
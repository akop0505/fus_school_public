<?php

namespace app\controllers;

use app\controllers\common\BaseController;
use app\models\Contest;
use app\models\Form;
use Yii;
use app\models\forms\PostThumbUploadForm;
use app\models\common\BaseUploadForm;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class FormController  extends BaseController
{

    protected function findModel($id)
    {
        return Form::findOne($id);
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionStore($id) {
        $contest = Contest::findOne(['id'=>$id]);
        $model = new Form();
        if($model->load(Yii::$app->request->post()))
        {
            $model->isApproved = 0;
            if($model->isApproved)
            {
                $model->approvedById = Yii::$app->user->id;
                Yii::warning('3 Approved '. $model->id .' by '. Yii::$app->user->id);
            }
            $model->contest_id = $id;
            if($model->save())
            {
                $model = new Form();
                return $this->render('main',[
                    'imagePath'=>$contest->getPicBaseUrl('hasHeaderPhoto')."1.jpg",
                    'formContent'=>$this->renderPartial("_form",["model"=>$model,"contest"=>$contest])
                ]);
            }else{
                throw new ForbiddenHttpException();
            }
        }
        return $this->render('main',[
            'imagePath'=>$contest->getPicBaseUrl('hasHeaderPhoto')."1.jpg",
            'formContent'=>$this->renderPartial("_form",["model"=>$model,"contest"=>$contest])
        ]);
    }

    public function actionView($id) {
        $this->view->params['class'] = 'general';
        $model = $this->findModel($id);
        if($model){
            if(!$model->isApproved){
                return $this->render('main',['formContent'=>$this->renderPartial("info",["message"=>"The Form is Waiting to be approved"])]);
            }
            elseif ($model->isApproved && !$model->isActive) {
                return $this->render('main',['formContent'=>$this->renderPartial("info",["message"=>"The form has been inactivated."])]);
            }
            elseif ($model->isApproved && $model->isActive) {
                $this->redirect(Yii::$app->urlManager->createUrl(['/site/article-form', 'id' => $model->id]));
            }
        }else{
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
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
        $uploadError2 = $this->handleImageUpload($modelReal, 'hasThumbPhoto', new PostThumbUploadForm());
        if(!$uploadError && !$uploadError2 && $modelReal->save(false)) return ['view', 'id' => $id];
        elseif($uploadError || $uploadError2) return ['update', 'id' => $id];
        else return false;
    }


    public function actionEditForm($id)
    {
        throw new ForbiddenHttpException();
        if(!Yii::$app->user->can('SuperAdmin')) {
            throw new NotFoundHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $model = Form::findOne(['id' => $id]);
        if($model->load(Yii::$app->request->post()))
        {
            if($model->save())
            {
                $ret = $this->uploadPicture($model->id);
                if($ret) return $this->redirect(["/form/edit-form","id"=>$model->id]);
            }
        }
        $user = Yii::$app->user->identity;
        $data = $this->setupProfileLayout(Yii::$app->user->id);
        $data['page'] = 'form-edit';
        $data['profileContent'] = $this->renderPartial('edit', [
            'model' => $model,
            'user' => $user
        ]);
        return $this->render('/site/profile', $data);
    }

    public function actionDeleteForm($id)
    {
        if(!Yii::$app->user->can('SuperAdmin')) {
            throw new NotFoundHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
        $model = Form::findOne(['id' => $id]);
        $backPath = '/profile/form/'.$model->contest_id;
        if($model){
            if($model->delete()) {
                $this->deleteDirectory($model->getGalleryBasePath());
            }
        }
        return $this->redirect([$backPath]);
    }

}
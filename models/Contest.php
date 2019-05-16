<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;
use yii\web\ForbiddenHttpException;

class  Contest extends base\Contest
{
    public $channel = [];
    /**
     * @return string
     */
    public function getContentFullTitle()
    {
        return $this->title;
    }

    public function __toString()
    {
        $ret = $this->getContentFullTitle();
        if(!$ret) $ret = $this->title;
        return $ret;
    }

    /**
     * Check permissions for create/editing/deleting contest and uploading/removing pictures from gallery
     * @throws ForbiddenHttpException
     */
    public function checkContestPermission()
    {
        if(!Yii::$app->user->can('ContentAdmin')) throw new ForbiddenHttpException();
    }

    /**
     * @return bool|string
     */
    public function getUploadBasePath()
    {
        return Yii::getAlias('@webroot/images/upload/contest/headerphoto/'. $this->id .'/');
    }
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $postData = Yii::$app->request->post('Contest');
        $id = $this->id;
        ContestChannel::deleteAll(["contestId"=>$id]);
        if(is_array($postData['channel'])){
            foreach ($postData['channel'] as $attr){
                $postChannel = new ContestChannel();
                $postChannel->channelId = $attr;
                $postChannel->contestId = $id;
                if(!$postChannel->save()) throw new BadRequestHttpException(Yii::t('app', 'Post channel save failed!'));
            }
        }
    }

}
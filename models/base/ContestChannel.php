<?php

namespace app\models\base;

use Yii;
use \app\models\common\BaseActiveRecord as ContestChannelBaseActiveRecord;
use \app\models\Channel as ContestChannelChannel;
use \app\models\User as ContestChannelUser;
use \app\models\Contest as ContestChannelContest;

/**
 * This is the base-model class for table "ContestChannel".
 *
 * @property integer $channelId
 * @property integer $contestId
 * @property string $createdAt
 * @property integer $createdById
 *
 * @property ContestChannelChannel $channel
 * @property ContestChannelUser $createdBy
 * @property ContestChannelContest $contest
 */
class ContestChannel extends ContestChannelBaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ContestChannel';
    }


    /**
     * @inheritdoc
     */
    public static function label($n = 1)
    {
        return Yii::t("app", "{n, plural, =1{ContentChannel} other{ContestChannels}}", ["n" =>  $n]);
    }

    /**
     * Return rule to mark attributes as unsafe or false if none
     * @return bool|array
     */
    protected function getUnsafeRule()
    {
        return [['!createdAt', '!createdById'], 'safe'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $r = [
            [['channelId', 'contestId', 'createdAt', 'createdById'], 'required'],
            [['channelId', 'contestId', 'createdById'], 'integer'],
            [['createdAt'], 'safe'],
            [['channelId'], 'exist', 'skipOnError' => true, 'targetClass' => ContestChannelChannel::className(), 'targetAttribute' => ['channelId' => 'id']],
            [['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => ContestChannelUser::className(), 'targetAttribute' => ['createdById' => 'id']],
            [['contestId'], 'exist', 'skipOnError' => true, 'targetClass' => ContestChannelContest::className(), 'targetAttribute' => ['contestId' => 'id']]
        ];
        if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
        return $r;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ret = [
            'channelId' => Yii::t('models', 'Channel ID'),
            'contestId' => Yii::t('models', 'Contest ID'),
            'createdAt' => Yii::t('models', 'Created At'),
            'createdById' => Yii::t('models', 'Created By ID'),
            'createdBy' => Yii::t('models', 'Created By'),
        ];
        if($this->getScenario() == 'formModel')
        {
            $ret['channelId'] = false;
            $ret['createdById'] = false;
            $ret['contestId'] = false;
        }
        return $ret;
    }

    /**
     * @relations
     */
    public function relations()
    {
        return [
            'channel' => array('BELONGS_TO', ContestChannelChannel::className(), 'channelId'),
            'createdBy' => array('BELONGS_TO', ContestChannelUser::className(), 'createdById'),
            'contest' => array('BELONGS_TO', ContestChannelContest::className(), 'contestId'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(ContestChannelChannel::className(), ['id' => 'channelId']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(ContestChannelUser::className(), ['id' => 'createdById']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContest()
    {
        return $this->hasOne(ContestChannelContest::className(), ['id' => 'contestId']);
    }

    /**
     * @inheritdoc
     * @return \app\models\ContestChannel|null ActiveRecord instance matching the condition, or `null` if nothing matches.
     */
    public static function findOne($condition)
    {
        return parent::findOne($condition);
    }
}

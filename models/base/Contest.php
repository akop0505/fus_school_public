<?php

namespace app\models\base;

use \app\models\common\BaseActiveRecord;
use app\models\Form;
use \app\models\User;
use Yii;

/**
 * This is the base-model class for table "Post".
 *
 *
 */
class Contest extends BaseActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Contest';
    }

    /**
     * @inheritdoc
     */
    protected function getUnsafeRule()
    {
        return [['!createdAt', '!createdById', '!updatedAt', '!hasHeaderPhoto', '!datePublished'], 'safe'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $r = [
            [['title','content','type','createdById','isActive','createdAt', 'datePublished'], 'required'],
            [['video','updatedAt'], 'safe'],
            [['isActive','createdById'], 'integer'],
            ['type','in','range'=>['article']],
            [['hasHeaderPhoto'], 'required', 'on' => 'insert'],
            [['title', 'content','video','type'], 'string'],
            [['title', 'video'], 'string', 'max' => 255],
            ['title', 'filter', 'filter' => function ($value) {
                return trim(strip_tags($value));
            }],
            [['createdById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['createdById' => 'id']],
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
            'id' => Yii::t('models', 'ID'),
            'title' => Yii::t('models', 'Title'),
            'content' => Yii::t('models', 'Contest Text'),
            'hasHeaderPhoto' => Yii::t('models', 'Has Header Photo'),
            'video' => Yii::t('models', 'Video'),
            'type' => Yii::t('models', 'Type'),
            'createdById' => Yii::t('models', 'Created By ID'),
            'isActive' => Yii::t('models', 'Is Active'),
            'createdAt' => Yii::t('models', 'Created At'),
            'updatedAt' => Yii::t('models', 'Updated At'),
            'datePublished' => Yii::t('models', 'Date Published'),
        ];

        if($this->getScenario() == 'formModel')
        {
            $ret['createdById'] = false;
        }
        if($tmp = $this->getUnsafeRule()) $r[] = $tmp;
        return $r;
    }

    /**
     * @relations
     */
    public function relations()
    {
        return [
            'createdBy' => array('BELONGS_TO', User::className(), 'createdById'),
            'waitingToApproveCount' => array('HAS_MANY', Form::className(), ''),
            'channels' => array('HAS_MANY', ContestChannel::className(), ''),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'createdById']);
    }

    /**
     * @return int|string
     */
    public function getWaitingToApproveCount()
    {
        return $this->hasMany(Form::className(), ['contest_id' => 'id'])
            ->where(["isApproved"=>0])
            ->count();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannels()
    {
        return $this->hasMany(Channel::className(), ['id' => 'channelId'])->viaTable('ContestChannel', ['contestId' => 'id']);
    }
}
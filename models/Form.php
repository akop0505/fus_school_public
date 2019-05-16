<?php

namespace app\models;

use \app\models\common\BaseActiveRecord;
use Yii;

/**
 * Class Form
 */

class Form extends BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Form';
    }

    /**
     * @inheritdoc
     */
    protected function getUnsafeRule()
    {
        return [['!createdAt', '!updatedAt'], 'safe'];
    }

    public function rules()
    {
        $r = [
            [['first_name','last_name','email', 'contest_id','school', 'createdAt', 'updatedAt'], 'required'],
            ['email', 'email'],
            [['isApproved'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['school'], 'string', 'max' => 255],
            [['approvedById'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['approvedById' => 'id']]
        ];
        return $r;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $ret = [
            'id' => Yii::t('models', 'ID'),
            'first_name' => Yii::t('models', 'First Name'),
            'last_name' => Yii::t('models', 'Last Name'),
            'email' => Yii::t('models', 'Email'),
            'school' => Yii::t('models', 'School'),
            'contest_id' => Yii::t('models', 'Contest'),
            'isApproved' => Yii::t('models', 'Is Approved'),
            'createdAt' => Yii::t('models', 'Created At'),
            'updatedAt' => Yii::t('models', 'Updated At'),
            'approvedById' => Yii::t('models', 'Approved By ID'),
        ];

        if($this->getScenario() == 'formModel')
        {
            $ret['approvedById'] = false;
        }
        return $ret;
    }

    /**
     * @relations
     */
    public function relations()
    {
        return [
            'approvedBy' => array('BELONGS_TO', User::className(), 'approvedById'),
            'contest' => array('BELONGS_TO', Contest::className(), 'contest_id'),
        ];
    }

    /**
     * Return
     * @return array
     */
    public static function dropdownTypesValues()
    {
        return [];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApprovedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'approvedById']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContest()
    {
        return $this->hasOne(Contest::className(), ['id' => 'contest_id']);
    }

    /**
     * @return bool|string
     */
    public function getGalleryBasePath()
    {
        return Yii::getAlias('@webroot/images/upload/form/gallery/'. $this->id .'/');
    }

    /**
     * @return bool|string
     */
    public function getGalleryBaseUrl()
    {
        return Yii::getAlias('@web/images/upload/form/gallery/'. $this->id .'/');
    }
}
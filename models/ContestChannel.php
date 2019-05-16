<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ContestChannel".
 */
class ContestChannel extends base\ContestChannel
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return null;
    }
}

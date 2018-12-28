<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "is_rating".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $subscriber_id
 * @property integer $created_at
 * @property integer $status
 */
class IsRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'is_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'subscriber_id', 'created_at', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'subscriber_id' => 'Subscriber ID',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

    public static function addIsRating($type = SubscriberActivity::ACTION_WEATHER, $subscriber_id)
    {
        /** @var  $isRating IsRating */
        $isRating = IsRating::find()
            ->andWhere(['type'=>$type])
            ->andWhere(['subscriber_id'=>$subscriber_id])
            ->one();
        if(!$isRating){
            $isRating = new IsRating();
            $isRating->type = $type;
            $isRating->subscriber_id = $subscriber_id;
            $isRating->created_at = time();
            $isRating->status = Subscriber::STATUS_ACTIVE;
            $isRating->save();
        }else{
            if(time() - $isRating->created_at >= 30 * 24 * 3600){
                $isRating->created_at = time();
                $isRating->save();
            }
        }
    }

}

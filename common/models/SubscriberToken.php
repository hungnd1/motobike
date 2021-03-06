<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%subscriber_token}}".
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property string $token
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $expired_at
 * @property integer $status
 * @property integer $channel
 *
 * @property Subscriber $subscriber
 */
class SubscriberToken extends \yii\db\ActiveRecord
{
    const TYPE_WIFI_PASSWORD = 1;
    const TYPE_ACCESS_TOKEN = 2;

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    const CHANNEL_ANDROID = 2;
    const CHANNEL_IOS = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subscriber_token}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_id', 'token'], 'required'],
            [['subscriber_id', 'type', 'created_at', 'expired_at', 'status', 'channel','updated_at'], 'integer'],
            [['token'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subscriber_id' => Yii::t('app', 'Subscriber ID'),
            'token' => Yii::t('app', 'Token'),
            'type' => Yii::t('app', 'Type'),
            'created_at' => Yii::t('app', 'Ngày tạo'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'status' => Yii::t('app', 'Trạng thái'),
            'channel' => Yii::t('app', 'Channel'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(Subscriber::className(), ['id' => 'subscriber_id']);
    }

    /**
     * @param $subscriber_id
     * @param $channel
     * @return SubscriberToken|null
     * @throws \Exception
     */
    public static function generateToken($subscriber_id, $channel){
        /** @var  $st SubscriberToken*/
        $st = SubscriberToken::find()->where(['subscriber_id' => $subscriber_id,'channel'=>$channel])->one();
        if($st){
            $st->token = Yii::$app->security->generateRandomString();
            $st->created_at = time();
            $st->expired_at = time() + Yii::$app->params['api.AccessTokenExpire'];
            $st->status = SubscriberToken::STATUS_ACTIVE;
            if($st->update()){
                return $st;
            }
            return null;
        }else{
            $s = new SubscriberToken();
            $s->subscriber_id = $subscriber_id;
            $s->token = Yii::$app->security->generateRandomString();
            $s->created_at = time();
            $s->expired_at = time() + Yii::$app->params['api.AccessTokenExpire'];
            $s->type = SubscriberToken::TYPE_WIFI_PASSWORD;
            $s->status = self::STATUS_ACTIVE;
            $s->channel = $channel;
            if($s->save()){
                return $s;
            }
            return null;
        }
    }

    public static function _generateToken($wifi_password, $subscriber_id, $msisdn){
        /**
         *
         * HungNV edit: 15/3/2016
         *
         * 1. get subscriber
         * 2. if exist
         *      yes: check token expried?
         *          yes: -> set new expried -> save -> return
         *          no -> return
         *      no:
         *          create new Subscriber_token, expired -> save -> return
         */
        /**
         * old code block
         */

        /*
        $subscriber_token = new SubscriberToken();
        $subscriber_token->subscriber_id = $subscriber_id;
        $subscriber_token->token = Yii::$app->security->generateRandomString();
        $subscriber_token->created_at = time();
        $subscriber_token->expired_at = time() + Yii::$app->params['api.AccessTokenExpire'];
        $subscriber_token->type = $wifi_password;
        $subscriber_token->msisdn = $msisdn;
        $subscriber_token->status = self::STATUS_ACTIVE;
//        $subscriber_token->channel = $channel;
        $subscriber_token->ip_address = Yii::$app->request->getUserIP();

        if($subscriber_token->save()){
            return $subscriber_token->token;
        }
        return null;
        */

        /**
         * new code block
         */
        $subscriber = SubscriberToken::find()->andWhere(['subscriber_id' => $subscriber_id])
            ->andWhere(['msisdn' => $msisdn])
            ->one();
        if(isset($subscriber)){
            if($subscriber->expired_at <= time()){
                $subscriber->expired_at = time() + Yii::$app->params['api.AccessTokenExpire'];
                $subscriber->update();
            }
            return $subscriber->token;
        }else{
            $subscriber_token = new SubscriberToken();
            $subscriber_token->subscriber_id =  $subscriber_id;
            $subscriber_token->msisdn = $msisdn;
            $subscriber_token->token = Yii::$app->security->generateRandomString();
            $subscriber_token->created_at = time();
            $subscriber_token->expired_at = time() + Yii::$app->params['api.AccessTokenExpire'];
            $subscriber_token->type = $wifi_password;
            $subscriber_token->status = self::STATUS_ACTIVE;
//            $subscriber_token->channel = $channel;
            if($subscriber_token->save()){
                return $subscriber_token->token;
            }else{
                return null;
            }
        }
    }

    public static function findByAccessToken($token)
    {
        return SubscriberToken::find()
            ->andWhere(['status' => SubscriberToken::STATUS_ACTIVE, 'token' => $token])
            ->andWhere("expired_at is null OR expired_at > :time", [":time" => time()])
            ->one();
    }


}

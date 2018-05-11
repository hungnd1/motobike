<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 29-Jun-17
 * Time: 10:16 PM
 */

namespace api\models;


use common\models\SubscriberServiceAsm;

class Service extends \common\models\Service
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['image'] = function ($model) {
            /* @var $model \common\models\Service */
            return $model->getImageLink();
        };
        $fields['is_my_package'] = function ($model) {
            /* @var $model \common\models\Service */
            /** @var  $subscriberServiceAsm  SubscriberServiceAsm */
            $subscriberServiceAsm = SubscriberServiceAsm::find()
                ->andWhere(['service_id' => $model->id])
                ->andWhere(['status' => SubscriberServiceAsm::STATUS_ACTIVE])
                ->andWhere(['subscriber_id' => \Yii::$app->user->id])
                ->orderBy(['updated_at' => SORT_DESC])->one();
            if ($subscriberServiceAsm) {
                if ($subscriberServiceAsm->time_expired - time() >= 0) {
                    return true;
                }
                return false;
            }
            return false;
        };
        return $fields;
    }

}
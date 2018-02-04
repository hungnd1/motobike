<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 11:29 AM
 */

namespace api\models;


use common\models\Subscriber;

class QuestionAnswer extends \common\models\QuestionAnswer
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['image'] = function ($model) {
            /* @var $model \common\models\QuestionAnswer */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };

        $fields['subscriber_name'] = function ($model) {
            /* @var $model \common\models\QuestionAnswer */
            if($model->subscriber_id){
                $subscriber = Subscriber::findOne($model->subscriber_id);
                if($subscriber){
                    return $subscriber->username;
                }
            }
            return 'Chưa cập nhật';
        };

        $fields['full_name'] = function ($model) {
            /* @var $model \common\models\QuestionAnswer */
            if($model->subscriber_id){
                $subscriber = Subscriber::findOne($model->subscriber_id);
                if($subscriber){
                    return $subscriber->full_name ? $subscriber->full_name : $subscriber->username;
                }
            }
            return 'Chưa cập nhật';
        };

        return $fields;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 16-Jun-17
 * Time: 5:10 PM
 */

namespace api\models;


class Subscriber extends \common\models\Subscriber
{
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['avatar_url']);
        unset($fields['skype_id']);
        unset($fields['google_id']);
        unset($fields['facebook_id']);
        unset($fields['password']);
        unset($fields['verification_code']);
        unset($fields['using_promotion']);
        unset($fields['user_agent']);
        unset($fields['client_type']);

        $fields['avatar'] = function ($model) {
            /* @var $model \common\models\Subscriber */
            if($model->avatar_url){
                return $model->getImageLink();
            }
            return '';
        };


        return $fields;
    }
}
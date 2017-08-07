<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 07-Aug-17
 * Time: 4:42 PM
 */

namespace api\models;


class LogData extends \common\models\LogData
{
    public function fields()
    {
        $fields = parent::fields();
//        unset($fields['content']);
        unset($fields['created_at']);
        unset($fields['updated_at']);
        unset($fields['type_coffee']);

        return $fields;
    }
}
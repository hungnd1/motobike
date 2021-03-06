<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 30-Jun-17
 * Time: 11:29 AM
 */

namespace api\models;


use common\models\Subscriber;

class CompanyQA extends \common\models\CompanyQa
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['image'] = function ($model) {
            /* @var $model \common\models\CompanyQa */
            if($model->image){
                return $model->getImageLink();
            }
            return '';
        };

        $fields['subscriber_name'] = function ($model) {
            /* @var $model \common\models\CompanyQa */
            /** @var  $companyProfile CompanyProfile */
            $companyProfile = \common\models\CompanyProfile::find()->andWhere(['id' => $model->farmer_id])->one();
            if ($companyProfile) {
                return $companyProfile->ho . " " . $companyProfile->ten;
            }
            return 'Chưa cập nhật';
        };


        return $fields;
    }
}
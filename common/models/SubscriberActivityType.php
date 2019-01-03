<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "subscriber_activity_type".
 *
 * @property integer $id
 * @property integer $report_date
 * @property integer $weather
 * @property integer $price
 * @property integer $gap
 * @property integer $buy
 * @property integer $gap_disease
 * @property integer $qa
 * @property integer $tracuusuco
 * @property integer $nongnghiepthongminh
 * @property integer $biendoikhihau
 * @property integer $tuvansudungphanbon
 */
class SubscriberActivityType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $total_via_site;
    public static function tableName()
    {
        return 'subscriber_activity_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_date','total_via_site', 'weather', 'price', 'gap', 'buy', 'gap_disease', 'qa', 'tracuusuco', 'nongnghiepthongminh', 'biendoikhihau', 'tuvansudungphanbon'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'total_via_site' => \Yii::t('app', 'Tổng lượt truy cập'),
            'report_date' => \Yii::t('app', 'Ngày'),
            'weather' => Yii::t('app', 'Thời tiết'),
            'price' => Yii::t('app', 'Giá cả'),
            'buy' => Yii::t('app', 'Mua bán'),
            'gap_disease' => Yii::t('app', 'Sâu bệnh'),
            'gap' => Yii::t('app', 'Tài liệu kỹ thuật canh tác'),
            'qa' => Yii::t('app', 'Hỏi đáp'),
            'tracuusuco' => Yii::t('app', 'Tra cứu sự cố bất thường'),
            'nongnghiepthongminh' => Yii::t('app', 'Nông nghiệp thông minh'),
            'biendoikhihau' => Yii::t('app', 'Biến đổi khí hậu'),
            'tuvansudungphanbon' => Yii::t('app', 'Tư vấn sử dụng phân bón'),
        ];
    }
}

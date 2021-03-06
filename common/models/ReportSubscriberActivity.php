<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%report_subscriber_activity}}".
 *
 * @property integer $id
 * @property integer $report_date
 * @property integer $via_site_daily
 * @property integer $total_via_site
 * @property integer $via_android
 * @property integer $via_ios
 * @property integer $via_website
 */
class ReportSubscriberActivity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_subscriber_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_date', 'via_site_daily', 'total_via_site','via_android','via_website','via_ios'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('app', 'ID'),
            'report_date' => \Yii::t('app', 'Ngày'),
            'site_id' => \Yii::t('app', 'Site ID'),
            'via_site_daily' => \Yii::t('app', 'Số lượt truy cập trong ngày'),
            'total_via_site' => \Yii::t('app', 'Tổng lượt truy cập'),
            'via_smb' => \Yii::t('app', 'Từ Smart box'),
            'via_android' => \Yii::t('app', 'Từ android'),
            'via_ios' => \Yii::t('app', 'Từ ứng dụng IOS'),
            'via_website' => \Yii::t('app', 'Từ website'),
            'content_type' => \Yii::t('app', 'loại nội dung'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Site::className(), ['id' => 'site_id']);
    }
}

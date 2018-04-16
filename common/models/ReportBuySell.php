<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "report_buy_sell".
 *
 * @property integer $id
 * @property integer $province_id
 * @property integer $type_coffee
 * @property integer $total_buy
 * @property integer $total_sell
 * @property integer $report_date
 */
class ReportBuySell extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'report_buy_sell';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'type_coffee', 'total_buy', 'total_sell', 'report_date'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_id' => 'Province ID',
            'type_coffee' => 'Type Coffee',
            'total_buy' => 'Total Buy',
            'total_sell' => 'Total Sell',
            'report_date' => 'Report Date',
        ];
    }
}

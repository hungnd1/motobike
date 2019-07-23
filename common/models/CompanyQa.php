<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company_qa".
 *
 * @property integer $id
 * @property string $question
 * @property string $answer
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $company_id
 * @property integer $farmer_id
 */
class CompanyQa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'company_qa';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer'], 'string'],
            [['created_at', 'updated_at', 'status', 'company_id', 'farmer_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Question',
            'answer' => 'Answer',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'company_id' => 'Company ID',
            'farmer_id' => 'Farmer ID',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property string $question
 * @property string $question_en
 * @property integer $is_dropdown_list
 * @property integer $fruit_id
 */
class Question extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $answer;
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question','question_en','answer'], 'string'],
            [['question','fruit_id','answer'], 'required'],
            [['is_dropdown_list','fruit_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question' => 'Câu hỏi',
            'is_dropdown_list' => 'Is Dropdown List',
            'fruit_id' => 'Cây trồng',
            'answer' => 'Câu trả lời'
        ];
    }
}

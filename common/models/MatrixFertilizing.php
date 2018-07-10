<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "matrix_fertilizing".
 *
 * @property integer $id
 * @property integer $id_answer_1
 * @property integer $id_answer_2
 * @property integer $question_id
 * @property string $content
 */
class MatrixFertilizing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'matrix_fertilizing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_answer_1', 'id_answer_2','question_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_answer_1' => 'Đáp án 1',
            'id_answer_2' => 'Đáp án 2',
            'content' => 'Nội dung',
            'question_id' => 'Câu hỏi'
        ];
    }
}

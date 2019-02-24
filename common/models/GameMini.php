<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_mini".
 *
 * @property integer $id
 * @property string $question
 * @property string $answer_a
 * @property string $answer_b
 * @property string $answer_c
 * @property string $answer_d
 * @property string $answer_correct
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $category_id
 */
class GameMini extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_mini';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question','answer_a','answer_b','answer_a','answer_c','answer_d','answer_correct','category_id'],'required'],
            [['created_at', 'updated_at', 'status','category_id'], 'integer'],
            [['question', 'answer_a', 'answer_b', 'answer_c', 'answer_d', 'answer_correct'], 'string', 'max' => 255],
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
            'answer_a' => 'Câu trả lời A',
            'answer_b' => 'Câu trả lời B',
            'answer_c' => 'Câu trả lời C',
            'answer_d' => 'Câu trả lời D',
            'answer_correct' => 'Đáp án đúng',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
            'category_id' => 'Danh mục'
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => \Yii::t('app', 'Hoạt động'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Tạm dừng'),
        ];
        return $lst;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->status, $lst)) {
            return $lst[$this->status];
        }
        return $this->status;
    }
}

<?php

namespace common\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "question_answer".
 *
 * @property integer $id
 * @property string $question
 * @property string $answer
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property integer $subscriber_id
 * @property integer $category_id
 * @property string $image
 * @property string $answer_string
 */
class QuestionAnswer extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_answer';
    }

    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question','status'],'required'],
            [['question', 'answer','answer_string'], 'string'],
            [['created_at', 'updated_at', 'status','subscriber_id','category_id'], 'integer'],
            [['image'], 'string', 'max' => 500],
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
            'answer' => 'Trả lời',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Updated At',
            'status' => 'Trạng thái',
            'image' => 'Ảnh thông tin',
            'category_id' => 'Danh mục hỏi đáp'
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::STATUS_ACTIVE => \Yii::t('app', 'Đã trả lời'),
            self::STATUS_INACTIVE => \Yii::t('app', 'Chưa trả lời'),
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

    public function getImageLink()
    {
        return $this->image ? Url::to(Yii::getAlias('@web') . DIRECTORY_SEPARATOR . Yii::getAlias('@question') . DIRECTORY_SEPARATOR . $this->image, true) : '';
        // return $this->images ? Url::to('@web/' . Yii::getAlias('@cat_image') . DIRECTORY_SEPARATOR . $this->images, true) : '';
    }
    public static function getListStatusNameByStatus($status){
        $lst = self::listStatus();
        if (array_key_exists($status, $lst)) {
            return $lst[$status];
        }
        return $status;
    }
}

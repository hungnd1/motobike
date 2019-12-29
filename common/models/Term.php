<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "term".
 *
 * @property integer $id
 * @property string $term
 * @property string $term_en
 * @property integer $created_at
 * @property integer $updated_at
 */
class Term extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'term';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['term','term_en'], 'required'],
            [['term','term_en'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'term' => 'Nội dung quy định',
            'term_en' => 'Nội dung quy định tiếng anh',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

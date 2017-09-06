<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "version".
 *
 * @property integer $id
 * @property integer $type
 * @property string $version
 * @property string $link
 * @property integer $created_at
 * @property integer $updated_at
 */
class Version extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'version';
    }

    const TYPE_IOS = 1;
    const TYPE_ANDROID = 2;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['link','version'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'version' => 'Version',
            'link' => 'Link',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function listStatus()
    {
        $lst = [
            self::TYPE_ANDROID => \Yii::t('app', 'Android'),
            self::TYPE_IOS => \Yii::t('app', 'IOS'),
        ];
        return $lst;
    }

    public function getStatusName()
    {
        $lst = self::listStatus();
        if (array_key_exists($this->type, $lst)) {
            return $lst[$this->type];
        }
        return $this->type;
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "game_mini_log".
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property string $answer
 * @property integer $correct
 * @property integer $created_at
 */
class GameMiniLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_mini_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscriber_id', 'correct', 'created_at'], 'integer'],
            [['answer'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'subscriber_id' => 'Subscriber ID',
            'answer' => 'Answer',
            'correct' => 'Correct',
            'created_at' => 'Created At',
        ];
    }
    public static function addGameMiniLog($subscriber_id, $answer, $correct){
        $gameMini = new GameMiniLog();
        $gameMini->subscriber_id = $subscriber_id;
        $gameMini->answer = $answer;
        $gameMini->correct = $correct;
        $gameMini->created_at = time();
        $gameMini->save();
    }
}

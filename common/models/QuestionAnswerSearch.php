<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\QuestionAnswer;
use yii\data\ArrayDataProvider;

/**
 * QuestionAnswerSearch represents the model behind the search form of `common\models\QuestionAnswer`.
 */
class QuestionAnswerSearch extends QuestionAnswer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['question', 'answer', 'image','answer_string'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = QuestionAnswer::find()->orderBy(['created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }

    public function generateReportAll1($params)
    {
        $query = QuestionAnswer::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'answer', $this->answer])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }

    public function generateDetailReport($rawData, $dateFormat = 'd/m/Y')
    {
        $dataRow = [];
        //label header
        $sttLabel = Yii::t('app', 'STT');
        $dateLabel = Yii::t('app', 'Câu hỏi');
        $total_via_site_label = Yii::t('app', 'Câu trả lời');
        $total_via_site_daily_label = Yii::t('app', 'Ngày viết câu hỏi');
        $status = Yii::t("app",'Trạng thái câu hỏi');
        if (!empty($rawData)) {
            $i = 0;
            foreach ($rawData as $raw) {
                $row[$sttLabel] = ++$i;
                $row[$dateLabel] = $raw['question'];
                $row[$total_via_site_label] = $raw['answer'];
                $row[$status] = $raw['status'] == QuestionAnswer::STATUS_ACTIVE ? "Đã trả lời" : "Chưa trả lời";
                $row[$total_via_site_daily_label] = date('d/m/Y H:i:s', $raw['created_at']);
                $dataRow[] = $row;
                //kết thúc một ngày, khởi tạo thêm 1 dòng cho ngày tiếp theo
                $row = [];
            }
        }
        $excelDataProvider = new ArrayDataProvider([
            'allModels' => $dataRow,
            'pagination' => false,
        ]);
        return $excelDataProvider;
    }
}

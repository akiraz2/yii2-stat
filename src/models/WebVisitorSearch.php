<?php

namespace akiraz2\stat\models;

use common\models\EmailForm;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class WebVisitorSearch extends WebVisitor
{
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['cookie_id'], 'string', 'max' => 32],
            [['source'], 'integer', 'max' => 4, 'min' => 0],
            [['ip_address'], 'string', 'max' => 15],
            [['url', 'referrer', 'user_agent'], 'string', 'max' => 255],
            ['user_id', 'integer'],
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
        $query = WebVisitor::find();

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
            'cookie_id' => $this->cookie_id,
            'user_id' => $this->user_id,
            'source' => $this->source,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'created_at', $this->created_at])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}

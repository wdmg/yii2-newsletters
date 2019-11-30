<?php

namespace wdmg\newsletters\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use wdmg\newsletters\models\Newsletters;

/**
 * NewslettersSearch represents the model behind the search form of `wdmg\newsletters\models\Newsletters`.
 */
class NewslettersSearch extends Newsletters
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'description', 'subject', 'content', 'layouts', 'recipients', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Newsletters::find();

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
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'layouts', $this->layouts])
            ->andFilterWhere(['like', 'views', $this->views])
            ->andFilterWhere(['like', 'recipients', $this->recipients]);


        if($this->status !== "*")
            $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}

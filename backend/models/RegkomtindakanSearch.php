<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Regjnskomitmen;
use common\models\Regkomtindakan;

/**
 * RegkomtindakanSearch represents the model behind the search form of `common\models\Regkomtindakan`.
 */
class RegkomtindakanSearch extends Regkomtindakan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_jns_komitmen'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parents class
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
        $query = Regkomtindakan::find();

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

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * For the data provider on the detail komitmen, and relation with table `Regjnskomitmen` model.
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchDetail($params, $id)
    {
        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');
        $query->where(['reg_jns_komitmen.id' => $id]);

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

        return $dataProvider;
    }
}
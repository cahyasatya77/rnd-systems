<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mastervariasi;
use common\models\Mastervariasidokumen;

/**
 * MastervariasiSearch represents the model behind the search form of `common\models\Mastervariasi`.
 */
class MastervariasiSearch extends Mastervariasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['kode', 'deskripsi', 'status'], 'safe'],
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
        $query = Mastervariasi::find();

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
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'kode', $this->kode])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * Relation to table Mastervariasidokumen
     * 
     * 
     * @param array $params
     * @param $id ID
     * 
     * @return ActiveDataProvider
     */
    public function searchDokumen($params, $id)
    {
        $query = Mastervariasidokumen::find();

        // add conditions that should always apply here
        $query->joinWith('masterdokumen');
        $query->joinWith('mastervariasi');
        $query->where(['id_master_variasi' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_master_variasi' => $this->id
        ]);

        return $dataProvider;
    }
}

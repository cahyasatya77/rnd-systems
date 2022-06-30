<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Registrasikomitmen;
use common\models\Regjnskomitmen;

/**
 * RegistrasikomitmenSearch represents the model behind the search form of `common\models\Registrasikomitmen`.
 */
class RegistrasikomitmenSearch extends Registrasikomitmen
{
    public $komitmen;
    public $date;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_obat', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['nama_obat', 'keterangan', 'id_status', 'komitmen', 'date'], 'safe'],
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
        $query = Registrasikomitmen::find();
        $query->joinWith('status');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'id_status' => SORT_ASC,
                ],
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
            'id_obat' => $this->id_obat,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'nama_obat', $this->nama_obat])
            ->andFilterWhere(['like', 'id_status', $this->id_status])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchManager($params)
    {
        $query = Registrasikomitmen::find();

        // add conditions that should always apply here
        $query->where(['id_status' => 9]);
        $query->andWhere('rnd_manager IS NOT NULL');
        $query->andWhere('approve_manager IS NULL');

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
            'id_obat' => $this->id_obat,
        ]);

        $query->andFilterWhere(['like', 'id_status', $this->id_status]);

        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchRevisi($params)
    {
        $query = Registrasikomitmen::find();

        // add conditions that should always apply here
        $query->where(['id_status' => 8]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want toreturn any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_obat' => $this->id_obat,
            'id_status' => $this->id_status
        ]);

        // $query->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * For closed jenis komitmen and send data to BPOM
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchBpom($params) 
    {
        $query = Regjnskomitmen::find();

        // add condition that should always apply here
        $query->joinWith('komitmen');
        $query->where(['reg_jns_komitmen.status' => 'approve']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want toreturn any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_obat' => $this->id_obat,
        ]);

        $query->andFilterWhere(['like', 'reg_jns_komitmen.status', $this->status]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * for history jenis komitmen and after send data to BPOM
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchHistoryBpom($params)
    {
        $query = Regjnskomitmen::find();

        // add conditions that should always apply here
        $query->joinWith('komitmen');
        $query->where(['reg_jns_komitmen.status' => 'done']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'tanggal_close' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records whe validation fails.
            // $query->where('0=1')
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_jenis' => $this->komitmen,
        ]);

        $query->andFilterWhere(['registrasi_komitmen.id_obat' => $this->id_obat])
                ->andFilterWhere(['like', 'reg_jns_komitmen.tanggal_close', $this->date]);

        return $dataProvider;
    }
}

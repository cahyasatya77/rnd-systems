<?php

namespace backend\models;

use common\models\Meetingdokumen;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Meetingregistrasi;

/**
 * MeetingregistrasiSearch represents the model behind the search form of `common\models\Meetingregistrasi`.
 */
class MeetingregistrasiSearch extends Meetingregistrasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_produk', 'id_status', 'rnd_manager', 'note_revisi', 'tanggal_pembuatan', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['id_transaksi', 'nama_produk', 'ed_nie', 'submit_aero', 'keterangan', 'approve_manager', 'tanggal_close'], 'safe'],
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
        $query = Meetingregistrasi::find();

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
            'id_produk' => $this->id_produk,
            'submit_aero' => $this->submit_aero,
            'id_status' => $this->id_status,
            'rnd_manager' => $this->rnd_manager,
            'approve_manager' => $this->approve_manager,
            'note_revisi' => $this->note_revisi,
            'tanggal_pembuatan' => $this->tanggal_pembuatan,
            'tanggal_close' => $this->tanggal_close,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id_transaksi', $this->id_transaksi])
            ->andFilterWhere(['like', 'nama_produk', $this->nama_produk])
            ->andFilterWhere(['like', 'ed_nie', $this->ed_nie])
            ->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * 
     * @param array $params
     * @param integer $id ID model meeting registrasi
     * 
     * @return ActiveDataProvider
     */
    public function searchDokumenVariasi($params, $id)
    {
        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        // id jenis meeting 1 = jenis meeting `kategori variasi`
        $query->where(['meeting_kategori.id_jenis_meeting' => 1]);
        $query->andWhere(['meeting_registrasi.id' => $id]);

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
     * 
     * @param array $params
     * @param integer $id ID model meeting registrasi
     * 
     * @return ActiveDataProvider
     */
    public function searchDokumenEvaluasi($params, $id)
    {
        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        // id_jenis_meeting 2 = jenis meeting `Evaluasi Reg`
        $query->where(['meeting_kategori.id_jenis_meeting' => 2]);
        $query->andWhere(['meeting_registrasi.id' => $id]);

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
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchManager($params)
    {
        $query = Meetingregistrasi::find();

        // add conditions that should always apply here
        $query->where(['id_status' => 14]);
        $query->andWhere('rnd_manager IS NOT NULL');
        $query->andWhere('approve_manager IS NULL');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
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
            'id_produk' => $this->id_produk,
        ]);

        $query->andFilterWhere(['like', 'id_status', $this->id_status]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * Information status meeting revisi
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchRevisi($params)
    {
        $query = Meetingregistrasi::find();

        // add conditions that should always apply here
        $query->where(['id_status' => 13]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_produk' => $this->id_produk,
        ]);

        $query->andFilterWhere(['like', 'id_status', $this->id_status]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * 
     * @param array $params
     * @param integer $id ID model meeting registrasi
     * 
     * @return ActiveDataProvider
     */
    public function searchAero($params) 
    {
        $query = Meetingregistrasi::find();

        // Add conditions that should always apply here
        $query->joinWith('meetingkategori');
        $query->where(['meeting_kategori.id_jenis_meeting' => 1]);
        // $query->andWhere(['meeting_kategori.status' => 'approve']);
        // id_status = 17 adalah status done
        $query->andWhere(['id_status' => 17]);
        $query->andWhere(['NOT IN', 'id_status', '16']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
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
     * 
     * @param array $params
     * @param integer $id ID model meeting registrasi
     * 
     * @return ActiveDataProvider
     */
    public function searchAeroHistory($params) 
    {
        $query = Meetingregistrasi::find();

        // Add conditions that should always apply here
        // condition status product close
        $query->where(['id_status' => 16]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
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
     * 
     * @param array $params
     * @param integer $id ID model meeting registrasi
     * 
     * @return ActiveDataProvider
     */
    public function searchDokumen($params, $id)
    {
        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        $query->andWhere(['meeting_registrasi.id' => $id]);

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

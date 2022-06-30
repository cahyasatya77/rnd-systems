<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Justifikasi;

/**
 * JustifikasiSearch represents the model behind the search form of `common\models\Justifikasi`.
 */
class JustifikasiSearch extends Justifikasi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'pic_manager', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['alasan_justif', 'status', 'pic_manager_approve', 'rnd_approve', 'rnd_manager_approve'], 'safe'],
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
        $query = Justifikasi::find();

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
            'pic_manager' => $this->pic_manager,
            'pic_manager_approve' => $this->pic_manager_approve,
            'rnd_approve' => $this->rnd_approve,
            'rnd_manager_approve' => $this->rnd_manager_approve,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Creaetes data provider instance with search query applied
     * List status justifikasi `1` or `Draft`
     * 
     * @param array $param
     * 
     * @return ActiveDataProvider
     */
    public function searchDraft($params, $dept)
    {
        $query = Justifikasi::find();

        // add conditions that sholud always apply here
        $query->joinWith('registrasikomitmen');
        $query->joinWith('options');
        $query->joinWith('user');
        $query->where(['options.deskripsi' => 'draft']);
        $query->andWhere(['user.id_dept' => $dept]);

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

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Creates data provider instanve with search query applied
     * List status justifikasi `2` or `revisi`
     * 
     * @param array $param
     * 
     * @return ActiveDataProvider
     */
    public function searchRevisi($params, $dept)
    {
        $query = Justifikasi::find();

        // add conditions that should always apply here
        $query->joinWith('registrasikomitmen');
        $query->joinWith('options');
        $query->joinWith('user');
        $query->where(['options.deskripsi' => 'revisi']);
        $query->andWhere(['user.id_dept' => $dept]);

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

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     * List status justifikasi not in `1`, `2` or `Draft`, `Revisi` and not in `Approve`
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchWaiting($params, $dept)
    {
        $query = Justifikasi::find();

        $not = ['draft', 'revisi', 'approve'];

        // add conditions that should always apply here
        $query->joinWith('registrasikomitmen');
        $query->joinWith('options');
        $query->joinWith('user');
        $query->where(['NOT IN', 'options.deskripsi', $not]);
        $query->andWhere(['user.id_dept' => $dept]);

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

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * List status justifikasi with deskripsi `kirim` on options model.
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchKirim($params, $id)
    {
        $query = Justifikasi::find();

        // add conditions that should always apply here
        $query->joinWith('registrasikomitmen');
        $query->joinWith('options');
        $query->where(['pic_manager' => $id]);
        $query->andWhere(['options.deskripsi' => 'kirim']);
        $query->andWhere('pic_manager_approve IS NULL');

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

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * List status justifikasi with deskripsi `review rnd` on options model.
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchReview($params)
    {
        $query = Justifikasi::find();

        // add conditions that should always apply here
        $query->joinWith('registrasikomitmen');
        $query->joinWith('options');
        $query->where(['options.deskripsi' => 'review rnd']);
        $query->andWhere('rnd_approve IS NULL');

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

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * List status justifikasi with description `review rnd manager` on options model.
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchReviewManager($params)
    {
        $query = Justifikasi::find();

        // add conditions that should always apply here
        $query->joinWith('registrasikomitmen');
        $query->joinWith('options');
        $query->where(['options.deskripsi' => 'review rnd manager']);
        $query->andWhere(['AND', 'rnd_approve IS NOT NULL', 'rnd_manager_approve IS NULL']);

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

        $query->andFilterWhere(['like', 'alasan_justif', $this->alasan_justif])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}

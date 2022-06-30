<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Meetingdokumen;
use Yii;

/**
 * MeetingdokumenSearch represents the model behind the search form of 'common\models\Meetingdokumen'.
 */
class TagihanmeetingSearch extends Meetingdokumen
{
    public $nama_produk;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return[
            [['id'], 'integer'],
            [['nama_produk', 'pic', 'deadline'], 'safe'],
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
     * Create data provider instance with search query applied
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        // User 
        $user = Yii::$app->user->id;

        $query = Meetingdokumen::find();

        // add conditioins that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        $query->where(['pic' => $user]);
        $query->andWhere(['meeting_dokumen.status' => 'open']);
        $query->andWhere(['or', ['meeting_dokumen.status_pic' => 'open'], ['meeting_dokumen.status_pic' => 'draft']]);

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

        $query->andFilterWhere(['meeting_registrasi.id_produk' => $this->nama_produk])
                ->andFilterWhere(['like', 'deadline', $this->deadline]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * This search for manager approve from PIC dokumen.
     * 
     * @param arraya $params
     * 
     * @return ActiveDataProvider
     */
    public function searchApprove($params)
    {
        // user 
        $user = Yii::$app->user->id;

        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        $query->where(['pic_manager' => $user]);
        $query->andWhere(['status_pic' => 'review manager']);
        $query->andWhere('pic_manager_approve IS NULL');

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do note want to return any records when valiidation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * This search for pic doing to revision document.
     * 
     * @param array $params
     * 
     * @return activeDataProvider
     */
    public function searchRevisi($params)
    {
        // user
        $user = Yii::$app->user->id;

        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        $query->where(['pic' => $user]);
        $query->andWhere(['status_pic' => 'revisi']);

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
     * Create data provider instance with search query applied
     * This search for Rnd Registrasi doing to review tagihan documents.
     * 
     * @param array $param
     * 
     * @return ActiveDataProvider
     */
    public function searchReview($params)
    {
        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        $query->where(['meeting_dokumen.status' => 'review rnd']);

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
     * Create data provider instance with search query applied
     * This search for Rnd Manager doing to review tagihan document after RnD registrasi approve the dokumen.
     * 
     * @param array $param
     * 
     * @return ActiveDataProvider
     */
    public function searchReviewRndManager($params)
    {
        $query = Meetingdokumen::find();

        // add conditions that should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');
        $query->where(['meeting_dokumen.status' => 'review rnd manager']);

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
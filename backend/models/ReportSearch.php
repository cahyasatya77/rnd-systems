<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Modelstatis;
use common\models\Meetingdokumen;
use common\models\Regkomtindakan;

/**
 * ReportSearch represents the model behind the search form of `backend\models\Modelstatis`.
 */
class ReportSearch extends Modelstatis
{
    /**
     * Variabel new
     */
    public $nama_obat;
    public $pic;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_komitmen', 'komitmen'], 'integer'],
            [['komitmen', 'nama_obat', 'pic'], 'safe'],
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
     * Created data provider instance with search query applied
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchKomitmen($params)
    {
        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');

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
            'pic' => $this->pic,
        ]);

        $query->andFilterWhere(['registrasi_komitmen.id_obat' => $this->nama_obat])
            ->andFilterWhere(['reg_jns_komitmen.id_jenis' => $this->komitmen]);

        return $dataProvider;
    }

    /**
     * Created data provider instance with search query applied
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchMeeting($params)
    {
        $query = Meetingdokumen::find();

        // add conditions thath should always apply here
        $query->joinWith('kategori');
        $query->joinWith('kategori.meeting');

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
            'pic' => $this->pic
        ]);

        $query->andFilterWhere(['meeting_registrasi.id_produk' => $this->nama_obat])
            ->andFilterWhere(['meeting_kategori.id_jenis_meeting' => $this->komitmen]);

        return $dataProvider;
    }
}
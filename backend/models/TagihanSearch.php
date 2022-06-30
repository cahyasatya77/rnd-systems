<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Regkomtindakan;
use Yii;

/**
 * RegkomtindakanSearch represents the model behind the search form of 'common\models\Regkomtindakan'.
 */
class TagihanSearch extends Regkomtindakan
{
    public $nama_obat;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nama_obat', 'dead_line'], 'safe'],
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

        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');
        $query->where(['pic' => $user]);
        $query->andWhere(['reg_kom_tindakan.status' => 'open']);
        $query->andWhere(['OR', ['reg_kom_tindakan.status_pic' => 'open'], ['reg_kom_tindakan.status_pic' => 'draft']]);

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

        $query->andFilterWhere(['registrasi_komitmen.id_obat' => $this->nama_obat])
            ->andFilterWhere(['like', 'dead_line', $this->dead_line]);

        return $dataProvider;
    }

    /**
     * Create data provider instance with search query applied
     * This search for manager approve from pic komitmen.
     * 
     * @param array $params
     * 
     * @return ActiveDataProvider
     */
    public function searchApprove($params)
    {
        // user
        $user = Yii::$app->user->id;

        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');
        $query->where(['manager_pic' => $user]);
        $query->andWhere(['status_pic' => 'review manager']);
        $query->andWhere('app_manager_pic IS NULL');

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
     * This search for pic doing to revision komitmen.
     * 
     * @param array $params
     * 
     * @return activeDataProvider
     */
    public function searchRevisi($params)
    {
        // user 
        $user = Yii::$app->user->id; 

        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');
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
     * This search for Rnd Registrasi doing to review tagihan komitmen.
     * 
     * @param array $param
     * 
     * @return ActiveDataProvider
     */
    public function searchReview($params)
    {
        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');
        $query->where(['reg_kom_tindakan.status' => 'review rnd']);

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
     * This search for Rnd Manager doing to review tagihan komitmen after RnD registrasi approve the komitmen.
     * 
     * @param array $param
     * 
     * @return ActiveDataProvider
     */
    public function searchReviewRndManager($params)
    {
        $query = Regkomtindakan::find();

        // add conditions that should always apply here
        $query->joinWith('jnsKomitmen');
        $query->joinWith('jnsKomitmen.komitmen');
        $query->where(['reg_kom_tindakan.status' => 'review rnd manager']);

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
<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * This is model class for any models.
 * Modelstatis represents the any model behind the create or search form of models.
 * 
 * @property string $id
 * @property string $id_komitmen
 * @property string $komitmen
 * @property string $tindakan
 */
class Modelstatis extends Model
{
    /**
     * Property variable
     */
    public $id;
    public $id_komitmen;
    public $komitmen;
    public $tindakan;
    public $meeting_update;
    public $id_meeting;
    public $variasi;

    /**
     * Scenario from model
     */
    const SCENARIO_JUSTIFIKASI = 'justifikasi';
    const SCENARIO_UPDATE_MEETING = 'meeting';
    const SCENARIO_JUSTIF_MEETING = 'justifmeeting';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id','id_komitmen', 'komitmen', 'id_meeting', 'variasi'], 'integer'],
            [['id_komitmen', 'komitmen', 'tindakan'], 'required', 'on' => self::SCENARIO_JUSTIFIKASI],
            [['id_meeting', 'variasi', 'tindakan'], 'required', 'on' => self::SCENARIO_JUSTIF_MEETING],
            [['meeting_update'], 'safe'],
            [['meeting_update'], 'required', 'on' => self::SCENARIO_UPDATE_MEETING],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_komitmen' => 'ID Komitmen / Produk',
            'komitmen' => 'komitmen',
            'tindakan' => 'Tindakan',
            'meeting_update' => 'Update',
            'id_meeting' => 'ID Meeting / Produk',
            'variasi' => 'Variasi',
        ];
    }
}
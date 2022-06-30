<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reg_jns_komitmen".
 *
 * @property int $id
 * @property int $id_komitmen
 * @property int $id_jenis
 * @property string $deadline_komit
 * @property string|null $status
 * @property string|null $surat_pengantar
 * @property string|null $ket_bpom
 * @property string|null $tanggal_close
 *
 * @property MasterJenisKomitmen $jenis
 * @property RegistrasiKomitmen $komitmen
 * @property RegKomTindakan[] $regKomTindakans
 */
class Regjnskomitmen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reg_jns_komitmen';
    }

    /**
     * Scenario model validation
     */
    const SCENARIO_CLOSE = 'close';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_jenis', 'deadline_komit'], 'required'],
            [['ket_bpom'], 'required', 'on' => self::SCENARIO_CLOSE],
            [['id_komitmen', 'status', 'tanggal_close', 'surat_pengantar'], 'safe'],
            [['ket_bpom'], 'string'],
            [['id_komitmen', 'id_jenis'], 'integer'],
            [
                ['surat_pengantar'],
                'file',
                'extensions' => ['pdf'],
                'maxSize' => 1024*1024*2,
                'tooBig' => 'Limit is 2 Mb',
            ]
            // [['id_komitmen'], 'exist', 'skipOnError' => true, 'targetClass' => RegistrasiKomitmen::className(), 'targetAttribute' => ['id_komitmen' => 'id']],
            // [['id_jenis'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenisKomitmen::className(), 'targetAttribute' => ['id_jenis' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_komitmen' => 'Id Komitmen',
            'id_jenis' => 'Komitmen',
            'surat_pengantar' => 'Surat Pengantar',
            'deadline_komit' => 'Deadline Komitmen',
            'status' => 'Status',
            'ket_bpom' => 'Keterangan BPOM',
            'tanggal_close' => 'Tanggal Close',
        ];
    }

    /**
     * Gets query for [[Jenis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenis()
    {
        return $this->hasOne(Masterjeniskomitmen::className(), ['id' => 'id_jenis']);
    }

    /**
     * Gets query for [[Komitmen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomitmen()
    {
        return $this->hasOne(Registrasikomitmen::className(), ['id' => 'id_komitmen']);
    }

    /**
     * Gets query for [[RegKomTindakans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKomitmenTindakan()
    {
        return $this->hasMany(Regkomtindakan::className(), ['id_jns_komitmen' => 'id']);
    }
}

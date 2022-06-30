<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reg_kom_tindakan".
 *
 * @property int $id
 * @property int $id_jns_komitmen
 * @property string $dokumen
 * @property string $jenis_dokumen
 * @property string $tanggal_surat
 * @property int $pic
 * @property string $dead_line
 * @property string|null $nama_dok
 * @property string|null $tanggal_submit
 * @property string $status
 * @property string $keterangan
 * @property string|null $status_pic
 * @property string|null $manager_pic
 * @property string|null $app_manager_pic
 * @property string|null $keterangan_revisi
 * @property int|null $approve_rnd
 * @property int|null $approve_mng_rnd
 * @property string|null $revisi_rnd
 *
 * @property RegJnsKomitmen $jnsKomitmen
 */
class Regkomtindakan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reg_kom_tindakan';
    }

    /**
     * Scenario model validation
     */
    const SCENARIO_TINDAKAN = 'tindakan';
    const SCENARIO_REVISI = 'revisi';
    const SCENARIO_REVISI_RND = 'revisi_rnd';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dokumen', 'jenis_dokumen', 'pic', 'dead_line'], 'required'],
            [['manager_pic'], 'required', 'on' => self::SCENARIO_TINDAKAN],
            [['keterangan_revisi'], 'required', 'on' => self::SCENARIO_REVISI],
            [['revisi_rnd'], 'required', 'on' => self::SCENARIO_REVISI_RND],
            [['id_jns_komitmen', 'pic'], 'integer'],
            [['dokumen', 'keterangan', 'keterangan_revisi'], 'string'],
            [[
                'id_jns_komitmen', 'tanggal_surat', 'dead_line', 'tanggal_submit', 'status',
                'keterangan', 'status_pic', 'manager_pic', 'app_manager_pic', 'approve_rnd', 'approve_mng_rnd'
            ], 'safe'],
            [['nama_dok'], 'string', 'max' => 100],
            [['jenis_dokumen'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 20],
            [['nama_dok'], 'file', 'extensions' => ['pdf'], 'maxSize' => 1024*1024*2, 'tooBig' => 'Limit is 2 MB'],
            // [['id_jns_komitmen'], 'exist', 'skipOnError' => true, 'targetClass' => RegJnsKomitmen::className(), 'targetAttribute' => ['id_jns_komitmen' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_jns_komitmen' => 'Id Jns Komitmen',
            'dokumen' => 'Dokumen',
            'jenis_dokumen' => 'Jenis Dokumen',
            'tanggal_surat' => 'Tanggal Surat',
            'pic' => 'Pic',
            'dead_line' => 'Deadline',
            'nama_dok' => 'Nama Dok',
            'tanggal_submit' => 'Tanggal Submit',
            'status' => 'Status',
            'keterangan' => 'Keterangan',
            'status_pic' => 'Status PIC',
            'manager_pic' => 'Manager PIC',
            'app_manager_pic' => 'Approve Manager PIC',
            'keterangan_revisi' => 'Keterangan Revisi',
        ];
    }

    /**
     * Gets query for [[JnsKomitmen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJnsKomitmen()
    {
        return $this->hasOne(Regjnskomitmen::className(), ['id' => 'id_jns_komitmen']);
    }

    /**
     * Gets query for [[User]].
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'pic']);
    }

    /**
     * Gets query for [[Justifikasidetail]]
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getJustifikasiDetails()
    {
        return $this->hasMany(Justifikasidetail::className(), ['id_kom_tindakan' => 'id']);
    }
}

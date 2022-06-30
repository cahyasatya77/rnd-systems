<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "registrasi_komitmen".
 *
 * @property int $id
 * @property string $id_transaksi
 * @property int $id_obat
 * @property string $nama_obat
 * @property string $bentuk_sediaan
 * @property string|null $tanggal_surat
 * @property string $dead_line
 * @property string|null $keterangan
 * @property string $tanggal_pembuatan
 * @property int $id_status
 * @property string|null $tanggal_close
 * @property int|null $rnd_manager
 * @property string|null $approve_manager
 * @property string|null $note_revisi
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 */
class Registrasikomitmen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registrasi_komitmen';
    }

    /**
     * Scenario model validation
     */
    const SCENARIO_REVISI = 'revisi';

    /**
     * Timestamp, make insert created_at and updated_at otomatis from `yii\behaviors\TimestampBehavior`.
     * BlameableBehavior, make insert created_by and updated_by get from Yii::$app->user->id.
     * 
     * @return mixed
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
            ],
            'autouserid' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by']
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_obat', 'bentuk_sediaan'], 'required'],
            [['note_revisi'], 'required', 'on' => self::SCENARIO_REVISI],
            [['id_obat', 'created_by', 'created_at', 'updated_by', 'updated_at', 'rnd_manager', 'id_status'], 'integer'],
            [['nama_obat', 'keterangan', 'id_transaksi', 'tanggal_pembuatan', 'tanggal_close', 'rnd_manager', 'approve_manager'], 'safe'],
            [['keterangan', 'note_revisi'], 'string'],
            [['nama_obat'], 'string', 'max' => 100],
            [['id_transaksi'], 'unique'],
            [['bentuk_sediaan', 'id_transaksi'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_obat' => 'Nama Obat',
            'id_transaksi' => 'ID Komitmen',
            'nama_obat' => 'Nama Obat',
            'bentuk_sediaan' => 'Bentuk Sediaan',
            'keterangan' => 'Keterangan',
            'tanggal_pembuatan' => 'Tanggal Pembuatan',
            'id_status' => 'Status',
            'tanggal_close' => 'Tanaggal Close',
            'rnd_manager' => 'R&D Manager',
            'approve_manager' => 'Tanggal Approve R&D Manager',
            'note_revisi' => 'Catatan Revisi',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Generate Kode
     */
    public function generateKode()
    {
        $_date = date('Y-m');
        $_left = 'KR-'.$_date.'-';
        $_first = '0001';
        $_len = strlen($_left);
        $no = $_left . $_first;

        $last_code = $this->find()
                ->select('id_transaksi')
                ->where('left(id_transaksi, '.$_len.')=:_left')
                ->params([':_left' => $_left])
                ->orderBy('id DESC')
                ->one();
        
        if ($last_code != null) {
            $_no = substr($last_code->id_transaksi, $_len);
            $_no++;
            $_no = sprintf('%04s', $_no);
            $no = $_left . $_no;
        }

        return $no;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKomitmen()
    {
        return $this->hasMany(Regjnskomitmen::className(), ['id_komitmen' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Options::className(), ['id' => 'id_status']);
    }
}
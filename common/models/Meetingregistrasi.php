<?php

namespace common\models;

use phpDocumentor\Reflection\Types\This;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "meeting_registrasi".
 *
 * @property int $id
 * @property string $id_transaksi
 * @property int $id_produk
 * @property string $nama_produk
 * @property string $ed_nie
 * @property string $submit_aero
 * @property string|null $keterangan
 * @property int $id_status
 * @property int|null $rnd_manager
 * @property string|null $approve_manager
 * @property int|null $note_revisi
 * @property int|null $tanggal_pembuatan
 * @property string|null $tanggal_close
 * @property string|null $surat_pengantar
 * @property string|null $note_close
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property MeetingKategori[] $meetingKategoris
 */
class Meetingregistrasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting_registrasi';
    }

    /**
     * Scenario model validation
     */
    const SCENARIO_REVISI = 'revisi';
    const SCENARIO_CLOSE = 'close';

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
            [['id_produk', 'ed_nie', 'submit_aero'], 'required'],
            [['note_revisi'], 'required', 'on' => self::SCENARIO_REVISI],
            [['note_close'], 'required', 'on' => self::SCENARIO_CLOSE],
            [['id_produk', 'id_status', 'rnd_manager', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['id_transaksi', 'submit_aero', 'approve_manager', 'nama_produk', 'tanggal_pembuatan', 'tanggal_close', 'surat_pengantar', 'note_close', 'id_status', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['keterangan', 'ed_nie', 'note_revisi', 'note_close'], 'string'],
            [['id_transaksi'], 'string', 'max' => 20],
            // [['surat_pengantar'], 'string', 'max' => 100],
            [['nama_produk'], 'string', 'max' => 200],
            [
                ['surat_pengantar'],
                'file',
                'extensions' => ['pdf'],
                'maxSize' => 1024*1024*2,
                'tooBig' => 'Limit is 2 Mb',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_transaksi' => 'Id Transaksi',
            'id_produk' => 'Produk',
            'nama_produk' => 'Nama Produk',
            'ed_nie' => 'Ed Nie',
            'submit_aero' => 'Submit Aero',
            'keterangan' => 'Keterangan',
            'id_status' => 'Id Status',
            'rnd_manager' => 'Rnd Manager',
            'approve_manager' => 'Approve Manager',
            'note_revisi' => 'Note Revisi',
            'tanggal_pembuatan' => 'Tanggal Pembuatan',
            'tanggal_close' => 'Tanggal Close',
            'surat_pengantar' => 'Surat Pengantar',
            'note_close' => 'Keterangan',
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
        $_left = 'MR-'.$_date.'-';
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
     * Gets query for [[MeetingKategoris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingkategori()
    {
        return $this->hasMany(Meetingkategori::className(), ['id_meeting' => 'id']);
    }

    public function getMeetingkategorivar()
    {
        return $this->hasMany(Meetingkategori::className(), ['id_meeting' => 'id'])->andWhere(['id_jenis_meeting' => 1]);
    }

    public function getMeetingkategorieval()
    {
        return $this->hasMany(Meetingkategori::className(), ['id_meeting' => 'id'])->andWhere(['id_jenis_meeting' => 2]);
    }

    /**
     * Gets query for [[Options]].
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(Options::className(), ['id' => 'id_status']);
    }
}

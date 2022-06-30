<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meeting_dokumen".
 *
 * @property int $id
 * @property int $id_kategori
 * @property int $id_dokumen
 * @property string $kode_dokumen
 * @property string $dokumen
 * @property int $pic
 * @property string $deadline
 * @property string|null $note_rnd
 * @property string|null $keterangan
 * @property string $status
 * @property string|null $status_pic
 * @property string|null $nama_dok
 * @property string|null $tanggal_submit
 * @property int|null $pic_manager
 * @property string|null $pic_manager_approve
 * @property string|null $registrasi_approve
 * @property string|null $manager_rnd_approve
 * @property string|null $revisi_mng_pic
 * @property string|null $revisi_reg
 * @property string|null $revisi_mng_rnd
 *
 * @property MeetingKategori $kategori
 * @property User $user
 */
class Meetingdokumen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting_dokumen';
    }

    /**
     * Scenario modle validation
     */
    // const SCENARIO_UPDATE_PIC = 'update_pic';
    const SCENARIO_TINDAKAN = 'tindakan';
    const SCENARIO_REVISI = 'revisi';
    const SCENARIO_REVISI_REG = 'revisi_rnd';
    const SCENARIO_REVISI_MNG_RND = 'revisi_mng_rnd';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen'], 'required'],
            // [['pic', 'deadline'], 'required', 'on' => self::SCENARIO_UPDATE_PIC],
            [['pic', 'deadline'], 'required', 'when' => function ($model) {
                return !empty($model->id_dokumen);
            }],
            [['pic_manager'], 'required', 'on' => self::SCENARIO_TINDAKAN],
            [['revisi_mng_pic'], 'required', 'on' => self::SCENARIO_REVISI],
            [['revisi_reg'], 'required', 'on' => self::SCENARIO_REVISI_REG],
            [['revisi_mng_rnd'], 'required', 'on' => self::SCENARIO_REVISI_MNG_RND],
            [['id_kategori', 'pic', 'pic_manager', 'id_dokumen'], 'integer'],
            [['dokumen', 'keterangan', 'revisi_mng_pic', 'revisi_reg', 'revisi_mng_rnd', 'kode_dokumen', 'note_rnd'], 'string'],
            [['deadline', 'tanggal_submit', 'pic_manager_approve', 'registrasi_approve', 'manager_rnd_approve', 'status', 'kode_dokumen', 'dokumen', 'note_rnd'], 'safe'],
            [['status', 'status_pic'], 'string', 'max' => 20],
            [['nama_dok'], 'string', 'max' => 100],
            [['nama_dok'], 'file', 'extensions' => ['pdf'], 'maxSize' => 1024*1024*2, 'tooBig' => 'Limit is 2 MB'],
            // [['id_kategori'], 'exist', 'skipOnError' => true, 'targetClass' => MeetingKategori::className(), 'targetAttribute' => ['id_kategori' => 'id']],
            [['pic'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['pic' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_kategori' => 'Id Kategori',
            'dokumen' => 'Dokumen',
            'pic' => 'Pic',
            'deadline' => 'Deadline',
            'keterangan' => 'Keterangan',
            'note_rnd' => 'Catatan',
            'status' => 'Status',
            'status_pic' => 'Status Pic',
            'nama_dok' => 'Nama Dok',
            'tanggal_submit' => 'Tanggal Submit',
            'pic_manager' => 'Pic Manager',
            'pic_manager_approve' => 'Pic Manager Approve',
            'registrasi_approve' => 'Registrasi Approve',
            'manager_rnd_approve' => 'Manager Rnd Approve',
            'revisi_mng_pic' => 'Revisi Mng Pic',
            'revisi_reg' => 'Revisi Reg',
            'revisi_mng_rnd' => 'Revisi Mng Rnd',
        ];
    }

    /**
     * Gets query for [[Kategori]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(Meetingkategori::className(), ['id' => 'id_kategori']);
    }

    /**
     * Gets query for [[Pic0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'pic']);
    }

    /**
     * Gets query for [[Meetingjustifikasidetail]]
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getJustifikasiDetails()
    {
        return $this->hasMany(Meetingjustifikasidetail::className(), ['id_dokumen' => 'id']);
    }
}

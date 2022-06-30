<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meeting_kategori".
 *
 * @property int $id
 * @property int $id_meeting
 * @property int $id_jenis_meeting
 * @property int $id_variasi
 * @property string $kode
 * @property string $deskripsi
 * @property string $status
 *
 * @property MasterJenisMeetingReg $jenisMeeting
 * @property MeetingRegistrasi $meeting
 * @property MeetingDokumen[] $meetingDokumens
 */
class Meetingkategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_variasi'], 'required'],
            [['id_meeting', 'id_jenis_meeting', 'id_variasi'], 'integer'],
            [['deskripsi', 'status', 'kode'], 'string'],
            [['status', 'kode'], 'safe'],
            // [['id_meeting'], 'exist', 'skipOnError' => true, 'targetClass' => MeetingRegistrasi::className(), 'targetAttribute' => ['id_meeting' => 'id']],
            // [['id_jenis_meeting'], 'exist', 'skipOnError' => true, 'targetClass' => MasterJenisMeetingReg::className(), 'targetAttribute' => ['id_jenis_meeting' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_meeting' => 'Id Meeting',
            'id_jenis_meeting' => 'Id Jenis Meeting',
            'id_variasi' => 'Id Kategori Variasi',
            'kode' => 'Kode',
            'deskripsi' => 'Deskripsi',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[JenisMeeting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisMeeting()
    {
        return $this->hasOne(Masterjenismeetingreg::className(), ['id' => 'id_jenis_meeting']);
    }

    /**
     * Gets query for [[Meeting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeeting()
    {
        return $this->hasOne(Meetingregistrasi::className(), ['id' => 'id_meeting']);
    }

    /**
     * Gets query for [[MeetingDokumens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingdokumen()
    {
        return $this->hasMany(Meetingdokumen::className(), ['id_kategori' => 'id']);
    }
}

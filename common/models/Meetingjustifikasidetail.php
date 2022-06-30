<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "meeting_justifikasi_detail".
 *
 * @property int $id
 * @property int $id_justifikasi
 * @property int $id_dokumen
 * @property string $deadline_old
 * @property string $deadline_new
 *
 * @property MeetingDokumen $dokumen
 * @property MeetingJustifikasi $justifikasi
 */
class Meetingjustifikasidetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting_justifikasi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_dokumen', 'deadline_old', 'deadline_new'], 'required'],
            [['id_justifikasi', 'id_dokumen'], 'integer'],
            [['deadline_old', 'deadline_new', 'id_justifikasi'], 'safe'],
            // [['id_justifikasi'], 'exist', 'skipOnError' => true, 'targetClass' => MeetingJustifikasi::className(), 'targetAttribute' => ['id_justifikasi' => 'id']],
            [['id_dokumen'], 'exist', 'skipOnError' => true, 'targetClass' => Meetingdokumen::className(), 'targetAttribute' => ['id_dokumen' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_justifikasi' => 'Id Justifikasi',
            'id_dokumen' => 'Id Dokumen',
            'deadline_old' => 'Deadline Old',
            'deadline_new' => 'Deadline New',
        ];
    }

    /**
     * Gets query for [[Dokumen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDokumen()
    {
        return $this->hasOne(Meetingdokumen::className(), ['id' => 'id_dokumen']);
    }

    /**
     * Gets query for [[Justifikasi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJustifikasi()
    {
        return $this->hasOne(Meetingjustifikasi::className(), ['id' => 'id_justifikasi']);
    }
}

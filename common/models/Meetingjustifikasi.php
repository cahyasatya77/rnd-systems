<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "meeting_justifikasi".
 *
 * @property int $id
 * @property int $id_registrasi
 * @property int $id_kategori
 * @property string $alasan_justif
 * @property int|null $id_status
 * @property int $pic_manager
 * @property string|null $pic_manager_approve
 * @property string|null $rnd_approve
 * @property string|null $rnd_manager_approve
 * @property string|null $revisi_pic_manager
 * @property string|null $revisi_rnd
 * @property string|null $revisi_rnd_manager
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property MeetingJustifikasiDetail[] $meetingJustifikasiDetails
 */
class Meetingjustifikasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meeting_justifikasi';
    }

    /**
     * Scenario
     */
    const SCENARIO_REVISI_PIC = 'revisipic';
    const SCENARIO_REVISI_RND = 'revisirnd';
    const SCENARIO_REVISI_MANAGER = 'revisimanager';

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
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_by'],
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
            [['alasan_justif', 'pic_manager'], 'required'],
            [['revisi_pic_manager'], 'required', 'on' => self::SCENARIO_REVISI_PIC],
            [['revisi_rnd'], 'required', 'on' => self::SCENARIO_REVISI_RND],
            [['revisi_rnd_manager'], 'required', 'on' => self::SCENARIO_REVISI_MANAGER],
            [['id', 'id_registrasi', 'id_kategori', 'id_status', 'pic_manager', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['alasan_justif', 'revisi_pic_manager', 'revisi_rnd', 'revisi_rnd_manager'], 'string'],
            [['pic_manager_approve', 'rnd_approve', 'rnd_manager_approve', 'id_registrasi', 'id_kategori', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'safe'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_registrasi' => 'Id Registrasi',
            'id_kategori' => 'Id Kategori',
            'alasan_justif' => 'Alasan Justif',
            'id_status' => 'Status',
            'pic_manager' => 'Pic Manager',
            'pic_manager_approve' => 'Pic Manager Approve',
            'rnd_approve' => 'Rnd Approve',
            'rnd_manager_approve' => 'Rnd Manager Approve',
            'revisi_pic_manager' => 'Revisi Pic Manager',
            'revisi_rnd' => 'Revisi Rnd',
            'revisi_rnd_manager' => 'Revisi Rnd Manager',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[MeetingJustifikasiDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasMany(Meetingjustifikasidetail::className(), ['id_justifikasi' => 'id']);
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

    /**
     * Gets query for [[Meetingregistasi]].
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrasi()
    {
        return $this->hasOne(Meetingregistrasi::className(), ['id' => 'id_registrasi']);
    }

    /**
     * Gets query for [[Meetingkategori]]
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getKategori()
    {
        return $this->hasOne(Meetingkategori::className(), ['id' => 'id_kategori']);
    }

    /**
     * Gets query for [[User]]
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }
}

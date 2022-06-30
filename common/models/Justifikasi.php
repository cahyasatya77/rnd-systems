<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "justifikasi".
 *
 * @property int $id
 * @property int $id_komitmen
 * @property int $id_jenis_komitmen
 * @property string $alasan_justif
 * @property int $status
 * @property int|null $pic_manager
 * @property string|null $pic_manager_approve
 * @property string|null $rnd_approve
 * @property string|null $rnd_manager_approve
 * @property string|null $note_revisi
 * @property string|null $revisi_rnd
 * @property string|null $revisi_rnd_manager
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property JustifikasiDetail[] $justifikasiDetails
 */
class Justifikasi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'justifikasi';
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
            [['note_revisi'], 'required', 'on' => self::SCENARIO_REVISI_PIC],
            [['revisi_rnd'], 'required', 'on' => self::SCENARIO_REVISI_RND],
            [['revisi_rnd_manager'], 'required', 'on' => self::SCENARIO_REVISI_MANAGER],
            [['alasan_justif', 'note_revisi', 'revisi_rnd', 'revisi_rnd_manager'], 'string'],
            [['created_at', 'created_by', 'updated_at', 'updated_by', 'id_komitmen', 'id_jenis_komitmen', 'status'], 'integer'],
            [[
                'pic_manager_approve', 'rnd_approve', 'rnd_manager_approve', 'status','created_at', 'created_by',
                'updated_at', 'updated_by', 'id_komitmen', 'id_jenis_komitmen', 'note_revisi', 'revisi_rnd',
                'revisi_rnd_manager'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alasan_justif' => 'Alasan Justifikasi',
            'status' => 'Status',
            'pic_manager' => 'Pic Manager',
            'pic_manager_approve' => 'Pic Manager Approve',
            'rnd_approve' => 'Rnd Approve',
            'rnd_manager_approve' => 'Rnd Manager Approve',
            'note_revisi' => 'Catatan Revisi',
            'revisi_rnd' => 'Catatan Revisi',
            'revisi_rnd_manager' => 'Catatan Revisi',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[JustifikasiDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJustifikasiDetails()
    {
        return $this->hasMany(Justifikasidetail::className(), ['id_justifikasi' => 'id']);
    }

    /**
     * Gets query for [[Options]].
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getOptions()
    {
        return $this->hasOne(Options::className(), ['id' => 'status']);
    }

    /**
     * Gets query for [[Registrasikomitmen]]
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrasikomitmen()
    {
        return $this->hasOne(Registrasikomitmen::className(), ['id' => 'id_komitmen']);
    }

    /**
     * Gets query for [[Regjnskomitmen]]
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getJenisKomitmen()
    {
        return $this->hasOne(Regjnskomitmen::className(), ['id' => 'id_jenis_komitmen']);
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

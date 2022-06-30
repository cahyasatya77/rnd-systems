<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log_justifikasi_komitmen".
 *
 * @property int $id
 * @property int $id_komitmen
 * @property int $id_jenis_komitmen
 * @property string $id_tindakan
 * @property string $alasan_justif
 * @property int $create_justif
 * @property int|null $pic_manager
 * @property string|null $pic_manager_approve
 * @property string|null $rnd_approve
 * @property string|null $rnd_manager_approve
 * @property string|null $note_revisi
 * @property string|null $revisi_rnd
 * @property string|null $revisi_rnd_manager
 * @property string|null $justif_delete
 * @property int $created_at
 * @property int $created_by
 */
class Logjustifikasikomitmen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_justifikasi_komitmen';
    }

    /**
     * Timestamp, make insert created_at otomation from `yii\behaviors\TimestampBehavior`.
     * BlameableBehavior, make insert created_by get from Yii::$app->user->id.
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            'autouserid' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by'],
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
            [['id_komitmen', 'id_jenis_komitmen', 'id_tindakan', 'alasan_justif', 'create_justif', 'justif_delete'], 'required'],
            [['id_komitmen', 'id_jenis_komitmen', 'create_justif', 'pic_manager', 'created_at', 'created_by'], 'integer'],
            [['alasan_justif', 'note_revisi', 'revisi_rnd', 'revisi_rnd_manager'], 'string'],
            [['pic_manager', 'pic_manager_approve', 'rnd_approve', 'rnd_manager_approve', 'note_revisi', 'revisi_rnd', 'revisi_rnd_manager', 'created_at', 'created_by'], 'safe'],
            [['id_tindakan'], 'string', 'max' => 50],
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
            'id_jenis_komitmen' => 'Id Jenis Komitmen',
            'id_tindakan' => 'Id Tindakan',
            'alasan_justif' => 'Alasan Justif',
            'create_justif' => 'Create Justif',
            'pic_manager' => 'Pic Manager',
            'pic_manager_approve' => 'Pic Manager Approve',
            'rnd_approve' => 'Rnd Approve',
            'rnd_manager_approve' => 'Rnd Manager Approve',
            'note_revisi' => 'Note Revisi',
            'revisi_rnd' => 'Revisi Rnd',
            'revisi_rnd_manager' => 'Revisi Rnd Manager',
            'justif_delete' => 'Justif Delete',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }
}

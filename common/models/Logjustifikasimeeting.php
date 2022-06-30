<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "log_justifikasi_meeting".
 *
 * @property int $id
 * @property int $id_meeting
 * @property int $id_kategori
 * @property string $id_dokumen
 * @property string $alasan_justif
 * @property int $create_justif
 * @property int|null $pic_manager
 * @property string|null $pic_manager_approve
 * @property string|null $rnd_approve
 * @property string|null $rnd_approve_manager
 * @property string|null $note_revisi
 * @property string|null $revisi_rnd
 * @property string|null $revisi_rnd_manager
 * @property string $justif_delete
 * @property int $created_by
 * @property int $created_at
 */
class Logjustifikasimeeting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_justifikasi_meeting';
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
            [['id_meeting', 'id_kategori', 'id_dokumen', 'alasan_justif', 'create_justif', 'justif_delete'], 'required'],
            [['id_meeting', 'id_kategori', 'create_justif', 'pic_manager', 'created_by', 'created_at'], 'integer'],
            [['alasan_justif', 'note_revisi', 'revisi_rnd', 'revisi_rnd_manager'], 'string'],
            [['pic_manager_approve', 'rnd_approve', 'rnd_approve_manager', 'justif_delete', 'created_by', 'created_at'], 'safe'],
            [['id_dokumen'], 'string', 'max' => 50],
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
            'id_kategori' => 'Id Kategori',
            'id_dokumen' => 'Id Dokumen',
            'alasan_justif' => 'Alasan Justif',
            'create_justif' => 'Create Justif',
            'pic_manager' => 'Pic Manager',
            'pic_manager_approve' => 'Pic Manager Approve',
            'rnd_approve' => 'Rnd Approve',
            'rnd_approve_manager' => 'Rnd Approve Manager',
            'note_revisi' => 'Note Revisi',
            'revisi_rnd' => 'Revisi Rnd',
            'revisi_rnd_manager' => 'Revisi Rnd Manager',
            'justif_delete' => 'Justif Delete',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }
}

<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "master_jenis_meeting_reg".
 *
 * @property int $id
 * @property string $deskripsi
 * @property int $created_by
 * @property int $created_at
 *
 * @property MeetingKategori[] $meetingKategoris
 */
class Masterjenismeetingreg extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_jenis_meeting_reg';
    }

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
            [['deskripsi'], 'required'],
            [['created_by', 'created_at'], 'safe'],
            [['created_by', 'created_at'], 'integer'],
            [['deskripsi'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'deskripsi' => 'Deskripsi',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[MeetingKategoris]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeetingKategoris()
    {
        return $this->hasMany(MeetingKategori::className(), ['id_jenis_meeting' => 'id']);
    }
}

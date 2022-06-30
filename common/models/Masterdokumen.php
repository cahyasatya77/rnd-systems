<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "master_dokumen".
 *
 * @property int $id
 * @property string $kode
 * @property string $deskripsi
 * @property string $status
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property MasterVariasiDokumen[] $masterVariasiDokumens
 */
class Masterdokumen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_dokumen';
    }

    /**
     * Timestamp, make insert created_at and updated_at otomation from `yii\behaviors\TimestampBehavio`.
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
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['kode', 'deskripsi', 'status'], 'required'],
            [['deskripsi'], 'string'],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            [['kode', 'status'], 'string', 'max' => 20],
            [['created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['kode'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kode' => 'Kode',
            'deskripsi' => 'Deskripsi',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[MasterVariasiDokumens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterVariasiDokumens()
    {
        return $this->hasMany(Mastervariasidokumen::className(), ['id_master_dokumen' => 'id']);
    }
}

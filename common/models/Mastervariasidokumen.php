<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "master_variasi_dokumen".
 *
 * @property int $id
 * @property int $id_master_variasi
 * @property int $id_master_dokumen
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_by
 * @property int $updated_at
 *
 * @property MasterDokumen $masterDokumen
 * @property MasterVariasi $masterVariasi
 */
class Mastervariasidokumen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_variasi_dokumen';
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
            [['id_master_dokumen'], 'required'],
            [['id_master_variasi', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'safe'],
            [['id_master_variasi', 'id_master_dokumen', 'created_by', 'created_at', 'updated_by', 'updated_at'], 'integer'],
            // [['id_master_variasi'], 'exist', 'skipOnError' => true, 'targetClass' => MasterVariasi::className(), 'targetAttribute' => ['id_master_variasi' => 'id']],
            // [['id_master_dokumen'], 'exist', 'skipOnError' => true, 'targetClass' => MasterDokumen::className(), 'targetAttribute' => ['id_master_dokumen' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_master_variasi' => 'Id Master Variasi',
            'id_master_dokumen' => 'Id Master Dokumen',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_by' => 'Updated By',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[MasterDokumen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterdokumen()
    {
        return $this->hasOne(Masterdokumen::className(), ['id' => 'id_master_dokumen']);
    }

    /**
     * Gets query for [[MasterVariasi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMastervariasi()
    {
        return $this->hasOne(Mastervariasi::className(), ['id' => 'id_master_variasi']);
    }
}

<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "master_jenis_komitmen".
 *
 * @property int $id
 * @property string $jenis
 * @property int $created_at
 * @property int $updated_at
 */
class Masterjeniskomitmen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_jenis_komitmen';
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['jenis'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['jenis'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis' => 'Jenis',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

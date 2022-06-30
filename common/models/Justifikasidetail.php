<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "justifikasi_detail".
 *
 * @property int $id
 * @property int $id_justifikasi
 * @property int $id_kom_tindakan
 * @property string $deadline_old
 * @property string $deadline_new
 *
 * @property Justifikasi $justifikasi
 */
class Justifikasidetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'justifikasi_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_kom_tindakan', 'deadline_old', 'deadline_new'], 'required'],
            [['id_justifikasi', 'id_kom_tindakan'], 'integer'],
            [['deadline_old', 'deadline_new', 'id_justifikasi'], 'safe'],
            // [['id_justifikasi'], 'exist', 'skipOnError' => true, 'targetClass' => Justifikasi::className(), 'targetAttribute' => ['id_justifikasi' => 'id']],
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
            'id_kom_tindakan' => 'Tindakan Komitmen',
            'deadline_old' => 'Deadline Old',
            'deadline_new' => 'Deadline New',
        ];
    }

    /**
     * Gets query for [[Justifikasi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJustifikasi()
    {
        return $this->hasOne(Justifikasi::className(), ['id' => 'id_justifikasi']);
    }

    /**
     * Gets query for [[Regkomtindakan]].
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getKomitmenTindakan()
    {
        return $this->hasOne(Regkomtindakan::className(), ['id' => 'id_kom_tindakan']);
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "options".
 *
 * @property int $id
 * @property string $deskripsi
 * @property string $table
 *
 * @property Justifikasi[] $justifikasis
 */
class Options extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['deskripsi', 'table'], 'required'],
            [['deskripsi'], 'string', 'max' => 50],
            [['table'], 'string', 'max' => 100],
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
            'table' => 'Table',
        ];
    }

    /**
     * Gets query for [[Justifikasis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJustifikasis()
    {
        return $this->hasMany(Justifikasi::className(), ['status' => 'id']);
    }
}

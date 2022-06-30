<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "department".
 *
 * @property string $id
 * @property string $nama_dept
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * Timestamp, make insert created_at and updated_at otomatis from `yii\behaviors\TimestampBehavior`.
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
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_by']
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
            [['nama_dept'], 'required'],
            [['updated_at', 'created_at', 'created_by'], 'safe'],
            [['created_at', 'updated_at', 'created_by'], 'integer'],
            [['id'], 'string', 'max' => 5],
            [['nama_dept'], 'string', 'max' => 100],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Department',
            'nama_dept' => 'Department',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Generate Kode Department
     */
    public function generateKodeDept()
    {
        $_left = 'D';
        $_first = '0001';
        $_len = strlen($_left);
        $no = $_left.$_first;

        $last_kode = $this->find()
            ->select('id')
            ->where('left(id, '.$_len.')=:_left')
            ->params([':_left' => $_left])
            ->orderBy('id DESC')
            ->one();

        if ($last_kode != null) {
            $_no = substr($last_kode->id, $_len);
            $_no++;
            $no = sprintf("D%04s", $_no);
        }

        return $no;
    }
}

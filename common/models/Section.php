<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "section".
 *
 * @property string $id
 * @property string $id_dept
 * @property string $name_section
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Department $dept
 */
class Section extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section';
    }

    /**
     * Timestamp, make insert created_at and updated_at otomatis from `yii\behaviors\TimestampBehavios`.
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
            [['id', 'id_dept', 'name_section'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_at', 'updated_at'], 'integer'],
            [['id'], 'string', 'max' => 10],
            [['id_dept'], 'string', 'max' => 5],
            [['name_section'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['id_dept'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['id_dept' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_dept' => 'Id Dept',
            'name_section' => 'Name Section',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Dept]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDept()
    {
        return $this->hasOne(Department::className(), ['id' => 'id_dept']);
    }

    /**
     * Generate Kode Section
     */
    public function generateKodeSection($id)
    {
        $_left = $id.'SC';
        $_first = '001';
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
            $_no = sprintf("%03s", $_no);
            $no = $_left . $_no;
        }

        return $no;
    }
}

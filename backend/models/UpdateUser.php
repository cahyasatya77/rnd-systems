<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\base\InvalidParamException;

/**
 * Change Password form
 */
class UpdateUser extends Model
{
    public $id;
    public $password;
    public $confirm_password;
    public $email;
    public $level_access;
    public $department;
    public $section;
    public $status;
    public $username;

    const SCENARIO_UPDATE = 'update';

    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     * 
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($id, $config = [])
    {
        $this->_user = User::findIdentity($id);

        if (!$this->_user) {
            throw new InvalidParamException('Unable to find user !');
        }

        $this->id = $this->_user->id;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'confirm_password'], 'required'],
            [['username', 'email', 'department', 'status'], 'required', 'on' => self::SCENARIO_UPDATE],
            [['section','level_access'], 'safe'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            ['email', 'email'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * Update user
     */
    public function updateUs()
    {
        $user = $this->_user;
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->id_dept = $this->department;
        $user->id_section = $this->section;
        $user->level_access = $this->level_access;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save(false);
    }

    /**
     * Changes password
     * 
     * @return boolean if password was changed.
     */
    public function changePassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        
        return $user->save(false);
    }
}
<?php

namespace backend\components;

use common\models\Department;
use Yii;
use yii\web\User as WebUser;

/**
 * Extended yii\web\User
 * 
 * This allows us to do "Yii::$app->user->someThing" by adding getters
 * like "public function getSomething()";
 * 
 * so we can use variables and function directly in 'Yii::$app->user'
 */
class User extends WebUser
{
    public function getUsername()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->urlManager->baseUrl;
        }
        return Yii::$app->user->identity->username;
    }

    public function getDepartment()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->urlManager->baseUrl;
        }
        $id_dept = Yii::$app->user->identity->id_dept;
        $dept = Department::findOne($id_dept);
        return $dept->nama_dept;
    }

    public function getIdDept()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->urlManager->baseUrl;
        }
        return Yii::$app->user->identity->id_dept;
    }

    public function getIdSection()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->urlManager->baseUrl;
        }
        return Yii::$app->user->identity->id_section;
    }

    public function getLevel()
    {
        if (Yii::$app->user->isGuest) {
            return Yii::$app->urlManager->baseUrl;
        }
        return Yii::$app->user->identity->level_access;
    }
}
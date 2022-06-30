<?php

namespace backend\components;

use common\models\Department;
use Yii;
use yii\rbac\Rule;

class OnlyAdministrator extends Rule
{
    const RULE_NAME = 'administrator';

    /**
     * {@inheritdoc}
     */
    public $name = self::RULE_NAME;

    /**
     * {@inheritdoc}
     */
    public function execute($user, $item, $params)
    {
        $model = $params['post'];
        return $user == $model->created_by;
        // return Yii::$app->user->id == $model->created_by;
        // $model = Department::findOne($params['id']);
        // return $model && $user == $model->crated_by;
    }
}
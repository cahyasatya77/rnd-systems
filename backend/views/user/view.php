<?php

use common\models\Department;
use common\models\Section;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-view">
<div class="card card-outline card-info">
    <div class="card-header">
        <div class="card-title">User Detail</div>
    </div>
    <div class="card-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'username',
                'email',
                [
                    'label' => 'Department',
                    'value' => function($model) {
                        $dept = Department::findOne($model->id_dept);
                        return $dept->nama_dept;
                    }
                ],
                [
                    'label' => 'Section',
                    'value' => function($model) {
                        if ($model->id_section == null) {
                            return '';
                        } else {
                            $section = Section::findOne($model->id_section);
                            return $section->name_section;
                        }
                    }
                ],
                'level_access',
                [
                    'label' => 'Status',
                    'value' => $model->status == 10 ? 'Active' : 'In-Active',
                ],
            ],
        ])?>
    </div>
    <div class="card-footer">
        <?= Html::a('Index', ['index'], ['class' => 'btn btn-default'])?>
    </div>
</div>
</div>
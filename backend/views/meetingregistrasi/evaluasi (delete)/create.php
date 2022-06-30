<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Meetingregistrasi */
/* @var $model_kategori common\models\Meetingkategori */
/* @var $model_dokumen common\models]Meetingdokumen */

$this->title = 'Create Evaluasi Reg';
$this->params['breadcrumbs'][] = ['label' => 'Notulen', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'View', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-12">
         <div class="card card-outline card-info">
            <div class="card-header">
                <div class="card-title">Notulen Meeting</div>
            </div>
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id_transaksi',
                                'nama_produk',
                            ],
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'ed_nie',
                                'submit_aero',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('_form', [
    'model' => $model,
    'model_kategori' => $model_kategori,
    'model_dokumen' => $model_dokumen,
    'pic' => $pic,
])?>

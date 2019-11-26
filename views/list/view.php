<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model wdmg\newsletters\models\Newsletters */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/newsletters', 'Newsletters list'), 'url' => ['list/index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="newsletters-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'description',
            'subject',
            'content:html',
            'layouts',
            'recipients',
            [
                'attribute' => 'unique_token',
                'format' => 'html',
                'value' => function($data) {
                    $string = $data->unique_token;
                    $length = strlen($string);
                    $sub_len = abs($length / 5);
                    if($string && $length > 6)
                        return substr($string, 0, $sub_len) . '…' . substr($string, -$sub_len, $sub_len) . ' <span class="text-muted pull-right">[length: '.$length.']</span>';
                    else
                        return $string;
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'html',
                'value' => function($data) {
                    if ($data->status == $data::NEWSLETTERS_STATUS_DISABLED)
                        return '<span class="label label-danger">'.Yii::t('app/modules/newsletters','Disabled').'</span>';
                    elseif ($data->status == $data::NEWSLETTERS_STATUS_ACTIVE)
                        return '<span class="label label-success">'.Yii::t('app/modules/newsletters','Active').'</span>';
                    else
                        return false;
                },
            ],

            'workflow',
            'params',

            'created_at:datetime',
            'created_by',
            'updated_at:datetime',
            'updated_by',

        ],
    ]) ?>

    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/newsletters', '&larr; Back to list'), ['list/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
        <?= Html::a(Yii::t('app/modules/newsletters', 'Edit'), ['list/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app/modules/newsletters', 'Delete'), ['list/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('app/modules/newsletters', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

</div>

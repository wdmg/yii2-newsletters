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
            'views',
            [
                'attribute' => 'recipients',
                'format' => 'html',
                'value' => function($data) use ($model) {
                    $lists = [];
                    $emails = [];
                    $data = \yii\helpers\Json::decode($data->recipients);
                    foreach ($data as $key => $item) {

                        if (preg_match('/email_id:(\d)/', $key)) {
                            $emails[] = '<span class="label label-info">' . $item . '</span>';
                        } elseif (preg_match('/list_id:(\d)/', $key, $match)) {
                            $count = $model->getSubscribersCount(['list_id' => intval($match[1])]);
                            if (!is_null($count))
                                $lists[] = '<span class="label label-warning">' . $item . ' ('.$count.')</span>';
                            else
                                $lists[] = '<span class="label label-warning">' . $item .'</span>';

                        } else {
                            $emails[] = '<span class="label label-default">' . $item . '</span>';
                        }
                    }
                    return join(\yii\helpers\ArrayHelper::merge($lists, $emails), " ");
                }
            ],
            'reply_to',
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
            [
                'attribute' => 'workflow',
                'format' => 'html',
                'value' => function($data) {
                    return var_export($data->workflow, true);
                },
            ],
            'params',

            [
                'attribute' => 'created',
                'label' => Yii::t('app/modules/newsletters','Created'),
                'format' => 'html',
                'value' => function($data) {

                    $output = "";
                    if ($user = $data->createdBy) {
                        $output = Html::a($user->username, ['../admin/users/view/?id='.$user->id], [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    } else if ($data->created_by) {
                        $output = $data->created_by;
                    }

                    if (!empty($output))
                        $output .= ", ";

                    $output .= Yii::$app->formatter->format($data->updated_at, 'datetime');
                    return $output;
                }
            ],
            [
                'attribute' => 'updated',
                'label' => Yii::t('app/modules/newsletters','Updated'),
                'format' => 'html',
                'value' => function($data) {

                    $output = "";
                    if ($user = $data->updatedBy) {
                        $output = Html::a($user->username, ['../admin/users/view/?id='.$user->id], [
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]);
                    } else if ($data->updated_by) {
                        $output = $data->updated_by;
                    }

                    if (!empty($output))
                        $output .= ", ";

                    $output .= Yii::$app->formatter->format($data->updated_at, 'datetime');
                    return $output;
                }
            ],

        ],
    ]) ?>

    <div class="form-group">
        <?= Html::a(Yii::t('app/modules/newsletters', '&larr; Back to list'), ['list/index'], ['class' => 'btn btn-default pull-left']) ?>&nbsp;
        <?= Html::a(Yii::t('app/modules/newsletters', 'Edit'), ['list/update', 'id' => $model->id], [
            'class' => 'btn btn-primary',
            'data-pjax' => '0'
        ]) ?>
        <?= Html::a(Yii::t('app/modules/newsletters', 'Delete'), ['list/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger pull-right',
            'data' => [
                'confirm' => Yii::t('app/modules/newsletters', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </div>

</div>

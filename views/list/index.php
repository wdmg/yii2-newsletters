<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use wdmg\widgets\SelectInput;
use wdmg\helpers\StringHelper;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $searchModel wdmg\newsletters\models\NewslettersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/modules/newsletters', 'All newsletters');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="newsletters-index">

    <?php Pjax::begin([
        'id' => 'newslettersAjax'
    ]); ?>
    <?php /*echo $this->render('_search', ['model' => $searchModel]);*/ ?>

    <?= GridView::widget([
        'id' => 'newslettersList',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{summary}<br\/>{items}<br\/>{summary}<br\/><div class="text-center">{pager}</div>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            /*'description',*/
            'subject',
            [
                'attribute' => 'content',
                'format' => 'text',
                'value' => function($data) {
                    return StringHelper::truncateWords(StringHelper::stripTags($data->content, "", " "),12,'…');
                }
            ],
            [
                'attribute' => 'recipients',
                'format' => 'html',
                'headerOptions' => [
                    'class' => 'text-center'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'value' => function($data) use ($searchModel) {
                    $html = '';
                    $lists = [];
                    $emails = [];
                    $data = \yii\helpers\Json::decode($data->recipients);
                    foreach ($data as $key => $item) {

                        if (preg_match('/email_id:(\d+)/', $key)) {
                            $emails[] = '<span class="label label-info">' . $item . '</span>';
                        } elseif (preg_match('/list_id:(\d+)/', $key, $match)) {
                            $count = $searchModel->getSubscribersCount(['list_id' => intval($match[1]), 'status' => \wdmg\subscribers\models\Subscribers::SUBSCRIBERS_STATUS_ACTIVE]);
                            if (!is_null($count))
                                $lists[] = '<span class="label label-warning">' . $item . ' ('.$count.')</span>';
                            else
                                $lists[] = '<span class="label label-warning">' . $item .'</span>';

                        } else {
                            $emails[] = '<span class="label label-default">' . $item . '</span>';
                        }
                    }

                    $onMore = false;
                    if (count($lists) >= 2)
                        $onMore = true;

                    if (count($emails) >= 4)
                        $onMore = true;

                    if ($onMore)
                        $html .= join(array_slice($lists, 0, 2), " ");
                    else
                        $html .= join($lists, " ");

                    $html .= " ";

                    if ($onMore)
                        $html .= join(array_slice($emails, 0, 4), " ");
                    else
                        $html .= join($emails, " ");

                    if ($onMore)
                        return $html . "&nbsp;… ";
                    else
                        return $html;

                }
            ],
            'reply_to',
            [
                'attribute' => 'status',
                'format' => 'html',
                'filter' => SelectInput::widget([
                    'model' => $searchModel,
                    'attribute' => 'status',
                    'items' => $searchModel->getStatusesList(true),
                    'options' => [
                        'class' => 'form-control'
                    ]
                ]),
                'headerOptions' => [
                    'class' => 'text-center'
                ],
                'contentOptions' => [
                    'class' => 'text-center'
                ],
                'value' => function($data) {
                    if ($data->status == $data::NEWSLETTERS_STATUS_ACTIVE) {
                        if (isset($data->workflow['status'])) {
                            if ($data->workflow['status'] == "run") {
                                return Progress::widget([
                                    'percent' => $data->progress,
                                    'label' => round($data->progress) . '%',
                                    'barOptions' => [
                                        'class' => 'progress-bar-success'
                                    ],
                                    'options' => [
                                        'id' => 'progress-'.$data->id,
                                        'data-key' => $data->id,
                                        'class' => 'active progress-striped'
                                    ],
                                ]);
                            } else if ($data->workflow['status'] == "pause") {
                                return Progress::widget([
                                    'percent' => $data->progress,
                                    'label' => round($data->progress) . '%',
                                    'barOptions' => [
                                        'class' => 'progress-bar-warning'
                                    ],
                                    'options' => [
                                        'id' => 'progress-'.$data->id,
                                        'data-key' => $data->id,
                                        'class' => 'active progress-striped'
                                    ],
                                ]);
                            } else if ($data->workflow['status'] == "stop") {
                                return Progress::widget([
                                    'percent' => $data->progress,
                                    'label' => round($data->progress) . '%',
                                    'barOptions' => [
                                        'class' => 'progress-bar-danger'
                                    ],
                                    'options' => [
                                        'id' => 'progress-'.$data->id,
                                        'data-key' => $data->id
                                    ],
                                ]);
                            }
                        }

                        return '<span class="label label-success">' . Yii::t('app/modules/newsletters', 'Active') . '</span>';

                    } elseif ($data->status == $data::NEWSLETTERS_STATUS_DISABLED) {
                        return '<span class="label label-default">' . Yii::t('app/modules/newsletters', 'Disabled') . '</span>';
                    } else {
                        return $data->status;
                    }
                }
            ],
            /*'created_at:datetime',
            'updated_at:datetime',*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('app/modules/newsletters', 'Actions'),
                'contentOptions' => [
                    'class' => 'text-center',
                    'style' => 'min-width:120px',
                ],
                'buttons'=> [
                    'refresh' => function($url, $data, $key) {
                        return Html::a(Yii::t('app/modules/newsletters', 'Refresh') . '&nbsp;<span class="glyphicon glyphicon-refresh"></span>', Url::toRoute(['process/refresh', 'id' => $data['id']]), [
                            'title' => Yii::t('app/modules/newsletters', 'Refresh progress'),
                            'data-toggle' => 'refresh-progress',
                            'data-id' => $key,
                            'data-pjax' => '1'
                        ]) . '<br/>';
                    },
                    'stop' => function($url, $data, $key) {
                        return Html::a(Yii::t('app/modules/newsletters', 'Stop') . '&nbsp;<span class="glyphicon glyphicon-stop"></span>', Url::toRoute(['process/stop', 'id' => $data['id']]), [
                            'title' => Yii::t('app/modules/newsletters', 'Stop newsletter'),
                            'class' => 'text-danger',
                            'data-id' => $key,
                            'data-pjax' => '1'
                        ]) . '<br/>';
                    },
                    'pause' => function($url, $data, $key) {
                        return Html::a(Yii::t('app/modules/newsletters', 'Suspend') . '&nbsp;<span class="glyphicon glyphicon-pause"></span>', Url::toRoute(['process/pause', 'id' => $data['id']]), [
                            'title' => Yii::t('app/modules/newsletters', 'Suspend newsletter'),
                            'class' => 'text-warning',
                            'data-id' => $key,
                            'data-pjax' => '1'
                        ]) . '<br/>';
                    },
                    'play' => function($url, $data, $key) {
                        return Html::a(Yii::t('app/modules/newsletters', 'Start') . '&nbsp;<span class="glyphicon glyphicon-play"></span>', Url::toRoute(['process/run', 'id' => $data['id']]), [
                            'title' => Yii::t('app/modules/newsletters', 'Start newsletter'),
                            'class' => 'text-success',
                            'data-id' => $key,
                            'data-pjax' => '1'
                        ]) . '<br/>';
                    },
                    'preview' => function($url, $data, $key) {
                        $url = Url::toRoute(['list/preview', 'id' => $data['id']]);
                        return Html::a(Yii::t('app/modules/newsletters', 'Preview') . '&nbsp;<span class="glyphicon glyphicon-envelope"></span>', $url, [
                            'class' => 'email-preview-link',
                            'title' => Yii::t('app/modules/newsletters', 'Preview email'),
                            'data-toggle' => 'modal',
                            'data-target' => '#emailPreview',
                            'data-id' => $key,
                            'data-pjax' => '1'
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {

                    if ($action === 'view')
                        return \yii\helpers\Url::toRoute(['list/view', 'id' => $key]);

                    if ($action === 'update')
                        return \yii\helpers\Url::toRoute(['list/update', 'id' => $key]);

                    if ($action === 'delete')
                        return \yii\helpers\Url::toRoute(['list/delete', 'id' => $key]);

                },
                'visibleButtons' => [
                    'stop' => function ($model) {
                        if (isset($model->workflow['status'])) {
                            return ($model->workflow['status'] == "run");
                        }

                        return false;
                    },
                    'pause' => function ($model) {
                        if (isset($model->workflow['status'])) {
                            return ($model->workflow['status'] == "run");
                        }

                        return false;
                    },
                    'play' => function ($model) {
                        if (isset($model->workflow['status'])) {
                            return !($model->workflow['status'] == "run");
                        }

                        return true;
                    }
                ],
                'template' => '{view} {update} {delete} <br/> {refresh} {play} {stop} {pause} {preview}'
            ],
        ],
        'pager' => [
            'options' => [
                'class' => 'pagination',
            ],
            'maxButtonCount' => 5,
            'activePageCssClass' => 'active',
            'prevPageCssClass' => '',
            'nextPageCssClass' => '',
            'firstPageCssClass' => 'previous',
            'lastPageCssClass' => 'next',
            'firstPageLabel' => Yii::t('app/modules/newsletters', 'First page'),
            'lastPageLabel'  => Yii::t('app/modules/newsletters', 'Last page'),
            'prevPageLabel'  => Yii::t('app/modules/newsletters', '&larr; Prev page'),
            'nextPageLabel'  => Yii::t('app/modules/newsletters', 'Next page &rarr;')
        ],
    ]); ?>
    <hr/>
    <div>
        <?= Html::a(Yii::t('app/modules/newsletters', 'Add newsletter'), ['list/create'], ['class' => 'btn btn-success pull-right']) ?>
    </div>
    <?php Pjax::end(); ?>
</div>

<?php $this->registerJs(<<< JS
    $('body').delegate('.email-preview-link', 'click', function(event) {
        event.preventDefault();
        $.get(
            $(this).attr('href'),
            function (data) {
                $('#emailPreview .modal-body').html(data);
                $('#emailPreview').modal();
            }  
        );
    });
JS
); ?>

<?php Modal::begin([
    'id' => 'emailPreview',
    'size' => 'modal-lg',
    'header' => '<h4 class="modal-title">'.Yii::t('app/modules/newsletters', 'Email preview').'</h4>',
    'clientOptions' => [
        'show' => false
    ]
]); ?>
<?php Modal::end(); ?>

<?php echo $this->render('../_debug'); ?>

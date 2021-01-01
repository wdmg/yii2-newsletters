<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\newsletters\models\Newsletters */

$this->title = Yii::t('app/modules/newsletters', 'Update newsletter: {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/newsletters', 'Newsletters list'), 'url' => ['list/index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<?php if (Yii::$app->authManager && $this->context->module->moduleExist('rbac') && Yii::$app->user->can('updatePosts', [
        'created_by' => $model->created_by,
        'updated_by' => $model->updated_by
    ])) : ?>
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
    </div>
    <div class="newsletters-update">

        <?= $this->render('_form', [
            'module' => $module,
            'model' => $model
        ]) ?>

    </div>
<?php else: ?>
    <div class="page-header">
        <h1 class="text-danger"><?= Yii::t('app/modules/newsletters', 'Error {code}. Access Denied', [
                'code' => 403
            ]) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
    </div>
    <div class="newsletters-update-error">
        <blockquote>
            <?= Yii::t('app/modules/newsletters', 'You are not allowed to view this page.'); ?>
        </blockquote>
    </div>
<?php endif; ?>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wdmg\newsletters\models\Newsletters */

$this->title = Yii::t('app/modules/newsletters', 'Add new newsletter');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/modules/newsletters', 'Newsletters list'), 'url' => ['list/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?> <small class="text-muted pull-right">[v.<?= $this->context->module->version ?>]</small></h1>
</div>
<div class="newsletters-create">

    <?= $this->render('_form', [
        'module' => $module,
        'model' => $model
    ]) ?>

</div>

<?php

use yii\helpers\Html;

if (!$layouts = $model->getTemplateLayouts()) {
    echo $model->content;
    return;
}

if (!$views = $model->getTemplateViews()) {
    echo $model->content;
    return;
}

if (!isset($views['html']) || !isset($layouts['html'])) {
    echo $model->content;
    return;
}

echo Yii::$app->mailer->render(
    $views['html'],
    ['content' => $model->content],
    $layouts['html']
);

?>
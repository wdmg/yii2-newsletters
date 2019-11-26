<?php
use yii\helpers\Html;
/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<h1>Newsletters from <?= Yii::$app->getHomeUrl() ?></h1>
<?= $content ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>
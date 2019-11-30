<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\AssetBundle as AppAsset;

$bundle = AppAsset::register($this);
if (isset(Yii::$app->mails))
    $logotype_url = Yii::$app->mails->getTrackingUrl($bundle->baseUrl . 'images/logo.png');
else
    $logotype_url = $bundle->baseUrl;

?>
<div>
<?= Html::a(Html::img($logotype_url, ['style' => "width:160px;"]), Url::home(true)); ?>
</div>
<br/>
<h1>Newsletters from <?= Html::a(Yii::$app->name, Url::base(true), ['target' => '_blank']) ?></h1>
<?= $content; ?>
<br/>
<hr/>
<?php
if (isset(Yii::$app->mails)) {
    if ($webversion_url = Yii::$app->mails->getWebversionUrl()) {
        echo Yii::t('app/modules/admin', 'Do not see the images? Go to the {link} of this email.', [
            'link' => Html::a('web-version', $webversion_url),
        ]);
    }
}
?>



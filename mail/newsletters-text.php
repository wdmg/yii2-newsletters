<?php

use yii\helpers\Url;
use wdmg\helpers\StringHelper;

?>
Newsletters from <?= Url::base(true) ?>
=====================================
<?= StringHelper::stripTags($content, "", " ") ?>

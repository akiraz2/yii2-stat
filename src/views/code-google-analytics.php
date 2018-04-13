<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

use yii\helpers\Json;

/** @var int $id */
/** @var array $params */
?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $id ?>"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?= $id ?>');
</script>
<!-- /Global site tag (gtag.js) - Google Analytics -->
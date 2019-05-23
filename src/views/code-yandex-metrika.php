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
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function(m, e, t, r, i, k, a) {
    m[i] = m[i] || function() {(m[i].a = m[i].a || []).push(arguments);};
    m[i].l = 1 * new Date();
    k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a);
})
(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js', 'ym');

ym(<?= $id ?>, 'init', <?= Json::encode($params) ?>);
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/<?= $id ?>" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->

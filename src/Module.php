<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

namespace akiraz2\stat;


use yii\base\Module as BaseModule;


class Module extends BaseModule
{
    public $version= '0.1.1';

    /** @var string  */
    public $controllerNamespace = 'akiraz2\stat\controllers';

    public $yandexMetrika = false;

    public $googleAnalytics = false;

    public $ownStat = false;

    public function getYandexMetrika() {
        return $this->yandexMetrika;
    }

    public function getGoogleAnalytics() {
        return $this->googleAnalytics;
    }
}
<?php
/**
 * Project: yii2-stat
 * Author: akiraz2
 * License: MIT
 * Copyright (c) 2018.
 */

namespace akiraz2\stat;


use yii\base\Module as BaseModule;


class Module extends BaseModule
{
    /** @var string  */
    public $controllerNamespace = 'akiraz2\stat\controllers';

    public $yandexMetrika = false;

    public $googleAnalytics = false;

    public $ownStat = false;

}
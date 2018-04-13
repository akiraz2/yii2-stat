<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

namespace akiraz2\stat\traits;

use \akiraz2\stat\Module;

trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('stat');//return Module::getInstance();
    }

}

<?php

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

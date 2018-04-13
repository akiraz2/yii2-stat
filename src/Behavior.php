<?php
/**
 * Created by PhpStorm.
 * User: user4957
 * Date: 13.04.2018
 * Time: 12:54
 */

namespace akiraz2\stat;

use akiraz2\stat\traits\ModuleTrait;
use Yii;
use yii\web\View;

class Behavior extends \yii\base\Behavior
{
    use ModuleTrait;

    public $builder;

    public function events()
    {
        return [
            View::EVENT_END_BODY => 'onEndBody',
        ];
    }

    public function onEndBody($event)
    {
        if($this->getModule()->yandexMetrika!= false) {
            echo $this->getBuilder()->render();
        }
    }

    public function getBuilder()
    {
        if (!is_object($this->builder)) {
            $this->builder = new CodeBuilder();
        }

        return $this->builder;
    }
}
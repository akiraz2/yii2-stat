<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

namespace akiraz2\stat;

use akiraz2\stat\traits\ModuleTrait;
use Yii;
use yii\web\View;

class ViewBehavior extends \yii\base\Behavior
{
    use ModuleTrait;

    public $builder;

    public function events()
    {
        return [
            View::EVENT_END_BODY => 'onEndBody'
        ];
    }

    public function onEndBody($event)
    {
        // зачем нам счетчики в дев режиме- отключаем
        if (YII_DEBUG || YII_ENV == 'dev' || Yii::$app->request->isAjax) {
            return;
        }

        if ($this->getModule()->yandexMetrika != false) {
            echo $this->getBuilder()->render('yandexMetrika');
        }
        if ($this->getModule()->googleAnalytics != false) {
            echo $this->getBuilder()->render('googleAnalytics');
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
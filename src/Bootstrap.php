<?php

namespace akiraz2\stat;


use yii\base\BootstrapInterface;


/**
 * Предзагрузка расширения
 *
 * @package akiraz2\stat
 */
class Bootstrap implements BootstrapInterface{

    /**
     * Метод, который вызывается автоматически при каждом запросе
     *
     * @param \yii\base\Application $app
     * @return void
     */
    public function bootstrap($app)
    {

        //Правила маршрутизации
        $app->getUrlManager()->addRules([
            'statistics' => 'statistics/stat/index',
            'statistics/forms' => 'statistics/stat/forms',
        ], false);

        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле frontend/config/main.php
         *  'modules' => [
         *      'statistics' => 'akiraz2\stat\Module'
         *  ],
         */
         $app->setModule('statistics', 'akiraz2\stat\Module');


    }
}
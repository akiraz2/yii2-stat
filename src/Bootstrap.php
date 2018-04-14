<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

namespace akiraz2\stat;


use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;


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
        /*$app->getUrlManager()->addRules([
            'statistics' => 'statistics/stat/index',
            'statistics/forms' => 'statistics/stat/forms',
        ], false);*/

        /*
         * Регистрация модуля в приложении
         * (вместо указания в файле frontend/config/main.php
         *  'modules' => [
         *      'statistics' => 'akiraz2\stat\Module'
         *  ],
         */
         //$app->setModule('stat', 'akiraz2\stat\Module');
         if(! ($app instanceof ConsoleApplication)) {
             $app->get('view')->attachBehavior('ViewBehavior',[
                 'class' => ViewBehavior::class,
             ]);
             $app->attachBehavior('ControllerBehavior',[
                 'class' => ControllerBehavior::class,
             ]);
         }

        // Add module I18N category.
        if (!isset($app->i18n->translations['akiraz2/stat'])) {
            $app->i18n->translations['akiraz2/stat'] = [
                'class' => PhpMessageSource::class,
                'basePath' =>  __DIR__ .'/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'akiraz2/stat' => 'stat.php',
                ]
            ];
        }
    }
}
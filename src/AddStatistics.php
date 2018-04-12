<?php

namespace akiraz2\stat;


use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use akiraz2\stat\models\KslStatistic;



/**
 * Активирует счетчик статистики по событию EVENT_BEFORE_ACTION
 * для указанных действий контроллера в методе behaviors()
 * @package akiraz2\stat
 *
 */
class AddStatistics extends Behavior
{
    /** @var array $actions */
    public $actions; //для каких действий контроллера


    /**
     * Привязка вызова метода add к событию
     *
     * @return array
     */
    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION  => 'add',
        ];
    }


    /**
     * Сохранение данных посетителя в БД
     *
     * @return void
     */
    public function add(){
        /** @var Controller $controller */
        $controller = $this->owner;

        $action_name = $controller->action->id; //название текущего действия
        if(array_search($action_name, $this->actions)=== FALSE) return;

        $ip = Yii::$app->request->userIP; //получаем IP текущего посетителя
        // if($ip == '127.0.0.1') return;

        $count_model = new KslStatistic(); //модель

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        $str_url =  $protocol . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"]; //URL текущей страницы c параметрами

        //Проверка на бота
        $bot_name = self::isBot2();
        //$bot_name = 'rambler'; //для тестирования

        if(!$bot_name){
            //Проверка в черном списке
            $black = $count_model->inspection_black_list($ip);
            if(!$black){
                $count_model->setCount($ip, $str_url, 0);
            }
        }
    }


    /**
     * Проверяет, является ли посетитель роботом поисковой системы из перечня.
     *
     * @param string $botname
     * @return bool|string
     */
    public static function isBot1(&$botname = ''){
        $bots = array(
            'rambler','googlebot','aport','yahoo','msnbot','turtle','mail.ru','omsktele',
            'yetibot','picsearch','sape.bot','sape_context','gigabot','snapbot','alexa.com',
            'megadownload.net','askpeter.info','igde.ru','ask.com','qwartabot','yanga.co.uk',
            'scoutjet','similarpages','oozbot','shrinktheweb.com','aboutusbot','followsite.com',
            'dataparksearch','google-sitemaps','appEngine-google','feedfetcher-google',
            'liveinternet.ru','xml-sitemaps.com','agama','metadatalabs.com','h1.hrn.ru',
            'googlealert.com','seo-rus.com','yaDirectBot','yandeG','yandex',
            'yandexSomething','Copyscape.com','AdsBot-Google','domaintools.com',
            'Nigma.ru','bing.com','dotnetdotcom'
        );
        foreach($bots as $bot)
            if(stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false){
                $botname = $bot;
                return $botname;
            }
        return false;
    }

    //Альтернативный метод проверки на бота
    public static function isBot2()
    {
        $is_bot = preg_match(
            "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i",
            $_SERVER['HTTP_USER_AGENT']
        );
        return $is_bot;
    }

}

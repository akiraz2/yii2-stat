<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

namespace akiraz2\stat;

use akiraz2\stat\models\WebVisitor;
use akiraz2\stat\traits\ModuleTrait;
use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\Request;

class ControllerBehavior extends Behavior
{
    use ModuleTrait;

    /**
     * Проверяет, является ли посетитель роботом поисковой системы из перечня.
     *
     * @param string $botname
     * @return bool|string
     */
    public static function isBot1(&$botname = '')
    {
        $bots = array(
            'rambler',
            'googlebot',
            'aport',
            'yahoo',
            'msnbot',
            'turtle',
            'mail.ru',
            'omsktele',
            'yetibot',
            'picsearch',
            'sape.bot',
            'sape_context',
            'gigabot',
            'snapbot',
            'alexa.com',
            'megadownload.net',
            'askpeter.info',
            'igde.ru',
            'ask.com',
            'qwartabot',
            'yanga.co.uk',
            'scoutjet',
            'similarpages',
            'oozbot',
            'shrinktheweb.com',
            'aboutusbot',
            'followsite.com',
            'dataparksearch',
            'google-sitemaps',
            'appEngine-google',
            'feedfetcher-google',
            'liveinternet.ru',
            'xml-sitemaps.com',
            'agama',
            'metadatalabs.com',
            'h1.hrn.ru',
            'googlealert.com',
            'seo-rus.com',
            'yaDirectBot',
            'yandeG',
            'yandex',
            'yandexSomething',
            'Copyscape.com',
            'AdsBot-Google',
            'domaintools.com',
            'Nigma.ru',
            'bing.com',
            'dotnetdotcom'
        );
        foreach ($bots as $bot) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) {
                $botname = $bot;
                return $botname;
            }
        }
        return false;
    }

    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION => 'ownCounter'
        ];
    }

    /**
     * @param $event Event
     * @throws \yii\base\Exception
     */
    public function ownCounter($event)
    {
        $module = $this->getModule();
        $request = Yii::$app->request;

        //
        if (!$module->ownStat
            || (in_array($request->userIP, $module->blackIpList))
            || !in_array(Yii::$app->id, $module->appId)
            || YII_DEBUG || YII_ENV == 'dev'
            || $request->isAjax
            || ($module->onlyGuestUsers && !Yii::$app->user->isGuest)
            || !$module->countBot && self::isBot()
        ) {
            return;
        }

        $cookies = Yii::$app->request->getCookies();
        $cookie_id_name = $module->ownStatCookieId;

        if (!$cookies->has($cookie_id_name)) {
            $cookie_id = new Cookie();
            $cookie_id->name = $cookie_id_name;
            $cookie_id->value = Yii::$app->security->generateRandomString();
            $cookie_id->expire = time() + 315360000;
            Yii::$app->response->getCookies()->add($cookie_id);
        } else {
            $cookie_id = $cookies->get($cookie_id_name);
        }
        Yii::$app->db->createCommand()->insert('{{%webstat_visitor}}', [
            'ip_address' => $request->userIP,
            'source' => self::identitySource($request),
            'cookie_id' => $cookie_id->value,
            'url' => $request->getAbsoluteUrl(),
            'referrer' => $request->getReferrer(),
            'user_id' => !Yii::$app->user->isGuest ? Yii::$app->user->identity->id : null,
            'user_agent' => Yii::$app->request->userAgent,
        ])->execute();
    }

    /**
     * @return false|int
     */
    public static function isBot()
    {
        $is_bot = preg_match(
            "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i",
            Yii::$app->request->userAgent //$_SERVER['HTTP_USER_AGENT']
        );
        return $is_bot;
    }

    /**
     * @param $request Request
     * @return int
     */
    public static function identitySource($request)
    {

        if ($request->getQueryParam('utm_source')) {
            return WebVisitor::TYPE_ADS;
        }

        if (preg_match("(google|yahoo|rambler|yandex|mail)", $request->getReferrer())) {
            return WebVisitor::TYPE_SEARCH;
        }

        if ($request->getReferrer() === null) {
            return WebVisitor::TYPE_DIRECT;
        }

        if (preg_match("($request->hostName)", $request->getReferrer())) {
            return WebVisitor::TYPE_INNER;
        }

        return WebVisitor::TYPE_UNKNOWN;
    }
}

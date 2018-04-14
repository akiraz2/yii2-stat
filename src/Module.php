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
    public $version = '0.2.2';

    /** @var string */
    public $controllerNamespace = 'akiraz2\stat\controllers';

    /**
     * if enable, it should be array:
     *  [ id = > 123123123,
     *    params => [
     *      'clickmap' => true,
     *       'trackLinks' => true,
     *       'accurateTrackBounce' => true,
     *       'webvisor' => true
     *    ]
     *  ]
     *
     * @var bool|array
     */
    public $yandexMetrika = false;

    /**
     * if enable, it should be array:
     *  [ id = > UA-1144374]
     *
     * @var bool|array
     */

    public $googleAnalytics = false;

    /**
     * enable own webstat counter
     *
     * @var bool
     */
    public $ownStat = false;

    /**
     * Cookie name
     * expire 10 years
     * only for statistic
     *
     * @var string
     */
    public $ownStatCookieId = 'yii2_counter_id';

    /**
     *  if ownStat enabled, count visits only for unregistered users
     *
     * @var bool
     */
    public $onlyGuestUsers = true;

    /**
     *  if ownStat enabled, count visits from search bots?
     *
     * @var bool
     */
    public $countBot = false;

    /**
     *  if ownStat enabled, count visits only from FRONTEND APP (not for BACKEND!)
     *
     * @var array
     */
    public $appId = ['app-frontend'];

    /**
     *  if ownStat enabled, don`t count visits from these IPs
     *
     * @var array
     */
    public $blackIpList = ['127.0.0.1'];

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
     *
     * The translation will be conducted according to the message category and the target language will be used.
     *
     * You can add parameters to a translation message that will be substituted with the corresponding value after
     * translation. The format for this is to use curly brackets around the parameter name as you can see in the following example:
     *
     * ```php
     * $username = 'Alexander';
     * echo \Yii::t('app', 'Hello, {username}!', ['username' => $username]);
     * ```
     *
     * Further formatting of message parameters is supported using the [PHP intl extensions](http://www.php.net/manual/en/intro.intl.php)
     * message formatter. See [[\yii\i18n\I18N::translate()]] for more details.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     *
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return \Yii::t('akiraz2/' . $category, $message, $params, $language);
    }

    /**
     * @return array|bool
     */
    public function getYandexMetrika()
    {
        return $this->yandexMetrika;
    }

    /**
     * @return array|bool
     */
    public function getGoogleAnalytics()
    {
        return $this->googleAnalytics;
    }
}
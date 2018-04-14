# Yii2 Multi Web Statistic Module [![Packagist Version](https://img.shields.io/packagist/v/akiraz2/yii2-stat.svg?style=flat-square)](https://packagist.org/packages/akiraz2/yii2-stat) [![Total Downloads](https://img.shields.io/packagist/dt/akiraz2/yii2-stat.svg?style=flat-square)](https://packagist.org/packages/akiraz2/yii2-stat) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

> **NOTE:** Module is in initial development. Anything may change at any time. 
На данный момент текущая рабочая версия v0.2 - есть только счетчики яндекса и google и собственный счетчик в DB

Модуль статистики и аналитики для вашего сайта. Много систем на ваш выбор, подключаются либо в конфиге модуля либо в админке:

* **Яндекс-Метрика**
* **Google Analytics**
* Liveinternet
* TopMail
* **Bigmir** *(для украинской аудитории)*
* Alexa
* Hotlog
* Rambler
* Openstat
* и даже на выбор **собственная** система для отслеживания посетителей по их IP-адресам и cookie.

> **NOTE:** Используются самые свежие версии кода счетчика (yandex-metrika2, gtag.js).


Для разработки модуля Yii2 Stat были использованы наработки данных модулей:
* [klisl/yii2-statistics](https://github.com/klisl/yii2-statistics) (inspired)
* [hiqdev/yii2-yandex-metrika](https://github.com/hiqdev/yii2-yandex-metrika) (code)

Иногда не все посещения сайта фиксируются счетчиками Яндекса или Google. 
Чтобы посещение точно было засчитано (а это очень важно для отслеживания рекламных источников), 
используют серверные логи или в нашем случае можно использовать минимально рабочий счетчик на PHP.


## Features

* вы можете использовать внешние сервисы на свой вкус, выбор простым конфигурированием модуля
* есть собственная простая система сбора статистики
* данные хранятся в отдельной таблице базы данных или на ваше усмотрение (*Redis*, etc)
* статистика формируется на основе уникальных IP адресов посетителей сайта/приложения и Cookie
* можно посмотреть страну, **город**, какой браузер и расширение, **referer**
* **источник перехода** (inner, search, direct, ads (from UTM-tags), unknown)
* **отсеивание поисковых ботов** (11шт)
* есть возможность добавления IP, которые не нужны в статистике в черный спискок
* удобная фильтрация вывода результатов статистики (за день, период, по определенному IP)


Какая информация выводится по каждому отдельному посетителю:
* его уникальный IP адрес с возможностью получения информации о его местонахождении
* URL просматриваемой страницы и количество переходов
* время посещения определенной страницы


  
## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist akiraz2/yii2-stat "dev-master"
```

or add

```
"akiraz2/yii2-stat": "dev-master"
```

to the require section of your `composer.json` file.


### Migration

Migration run

```php
yii migrate --migrationPath=@akiraz2/stat/migrations
```

### Config 

Config common modules in common/config/main.php

```php
    'modules' => [
        'stat' => [
            'class' => akiraz2\stat\Module::class,
            'yandexMetrika' => [ // false by default
               'id' => 13788753,
               'params' => [
                   'clickmap' => true,
                   'trackLinks' => true,
                   'accurateTrackBounce' => true,
                   'webvisor' => true
               ]
            ],
            'googleAnalytics' => [ // false by default
                'id' => 'UA-114443409-2',
            ],
            'ownStat' => true, //false by default
            'ownStatCookieId' => 'yii2_counter_id', // 'yii2_counter_id' default
            'onlyGuestUsers' => true, // true default
            'countBot' => false, // false default
            'appId' => ['app-frontend'], // by default count visits only from Frontend App (in backend app we dont need it)
            'blackIpList' => [] // ['127.0.0.1'] by default
            
            // размещаем нашу админ панель на backend с проверкой доступа или ролями (здесь используется dektrium/user)
            'controllerMap' => [
                'dashboard' => [
                    'class' => 'akiraz2\stat\controllers\DashboardController',
                    'as access' => [
                        'class' => \yii\filters\AccessControl::class,
                        'rules' => [
                            [
                                'allow' => true,
                                'roles' => ['@'],
                                'matchCallback' => function () {
                                    return Yii::$app->user->identity->getIsAdmin();
                                },
                            ],
                        ],
                    ],
                ],
            ],
        ],
     ],    
```

## Usage
// переработать


Для перехода на страницу статистики
 - с включенным ЧПУ в настройках Вашего приложения:
**http://your-site.com/stat/dashboard/index**
- без ЧПУ:
**http://your-site.com/web/index.php?r=stat/dashboard/index**


## Development

Please translate to your language! Edit config `@vendor/akiraz2/yii2-stat/src/messages/config.php`, add your language and run script:
```php
php ./yii message/extract @akiraz2/stat/messages/config.php
```
translate file will be in `@vendor/akiraz2/yii2-stat/src/messages/` or your configured path


## Support

If you have any questions or problems with Yii2-Stat you can ask them directly
 by using following email address: `akiraz@bk.ru`.


## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.
+PSR-2 style coding.

I can apply patch, PR in 2-3 days! If not, please write me `akiraz@bk.ru`

## Licensing

Yii2-Stat is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details. 
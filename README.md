# Yii2 Multi Statistic Module [![Packagist Version](https://img.shields.io/packagist/v/akiraz2/yii2-stat.svg?style=flat-square)](https://packagist.org/packages/akiraz2/yii2-stat) [![Total Downloads](https://img.shields.io/packagist/dt/akiraz2/yii2-stat.svg?style=flat-square)](https://packagist.org/packages/akiraz2/yii2-stat) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)


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
* и даже на выбор **собственная** система для отслеживания посетителей по их IP-адресам.

> **NOTE:** Module is in initial development. Anything may change at any time.

## Features

* вы можете использовать внешние сервисы на свой вкус и выбор простым конфигурированием модуля
* есть собственная простая система сбора статистики, данные хранятся в отдельной таблице базы данных
* статистика формируется на основе уникальных IP адресов посетителей сайта/приложения
* можно посмотреть страну, город, какой браузер и расширение
* **отсеивание поисковых ботов**
* есть возможность добавления IP, которые не нужны в статистике в черный спискок.
* удобная фильтрация вывода результатов статистики (за день, период, по определенному IP).


Какая информация выводится по каждому отдельному посетителю:
*	Его уникальный IP адрес с возможностью получения информации о его местонахождении.
*	URL просматриваемой страницы и количество переходов.
*	Время посещения определенной страницы.


  
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

todo: поменять эти настройки на модульные

```php
<?php
return [

    'statistics' => [

        'days_default' => 3, //кол-во дней для вывода статистики по-умолчанию (сегодня/вчера/...)

        'password' => 'klisl', //пароль для входа на страницу статистики. Если false (без кавычек) - вход без пароля

        'authentication' => false, //если true, то статистика доступна только аутентифицированным пользователям

        'auth_route' => 'site/login', //контроллер/действие для страницы аутентификации (по-умолчанию 'site/login')

        'date_old' => 90 //удалять данные через х дней
    ]
];    
```
для этого вставить массив 'statistics' с нужными вложенными элементами.
Для включения опции "authentication" должна быть реализована аутентификация пользователей.



## Usage
// переработать

Разместить (переопределить метод behaviors) в контроллерах ответственных за вывод страниц по которым нужно собирать статистику:
```php
public function behaviors()
{
    return [

        'statistics' => [
            'class' => \Klisl\Statistics\AddStatistics::class,
            'actions' => ['index', 'contact'],
        ],
…

```
где в качестве значений массива с ключем 'actions' указать нужные действия контроллера.

В качестве альтернативы можно (не переопределяя метод behaviors) указать в каждом необходимом действии такой код:
```php
$this->attachBehavior('statistics', [
    'class' => \Klisl\Statistics\AddStatistics::class,
    'actions' => [$this->action->id]
]);

```


Для перехода на страницу статистики
 - с включенным ЧПУ в настройках Вашего приложения:
**http://your-site.com/statistics**
- без ЧПУ:
**http://your-site.com/web/index.php?r=statistics/stat/index**

Откроется форма для входа на страницу с вводом пароля или страница аутентификации (в зависимости от настроек).
После ввода правильных данных, откроется сама страница статистики с формами для фильтрации.

Пароль для входа, по-умолчанию: ***klisl***

При тестировании на локальном компьютере, в статистику попадет IP 127.0.0.1. // добавить переменную YII_DEV

После начала использования пакета на хостинге, необходимо будет добавить свой IP в черный список,
 чтобы он не выводился в статистике.



## Support

If you have any questions or problems with Yii2-Blog you can ask them directly
 by using following email address: `akiraz@bk.ru`.


## Contributing

If you'd like to contribute, please fork the repository and use a feature branch. Pull requests are warmly welcome.
+PSR-2 style coding.
I can apply patch, PR in 2-3 days! If not, please write me `akiraz@bk.ru`

## Licensing

Yii2-Blog is released under the MIT License. See the bundled [LICENSE.md](LICENSE.md)
for details. 
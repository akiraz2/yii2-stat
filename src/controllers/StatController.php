<?php
/**
 * Project: yii2-stat
 * Author: akiraz2
 * License: MIT
 * Copyright (c) 2018.
 */

namespace akiraz2\stat\controllers;

use Yii;
use yii\web\Controller;
use akiraz2\stat\models\KslStatistic;


/**
 * Class StatController
 * Контроллер модуля расширение
 * @package akiraz2\stat\controllers
 */
class StatController extends Controller
{

    /** @var string название шаблона */
    public $layout = 'main';


    /**
     * Отвечает за вывод страницы статистики
     *
     * @param array $condition
     * @param bool $stat_ip
     * @return string|\yii\web\Response
     */
    public function actionIndex($condition = [], $stat_ip = false)
    {

        $count_model = new KslStatistic();
        //Получение списка статистики
        $count_ip = $count_model->getCount($condition);
        //Преобразуем коллекцию к виду где более поздняя дата идет в начале
        $count_ip = $count_model->reverse($count_ip);

        /*
         * Устанавливается значение полей по-умолчанию для вывода в полях формы
         */
        $count_model->date_ip = time(); //сегодня

        $black_list = $count_model->count_black_list();

        return $this->render('index', [
            'count_ip'=> $count_ip, //статистика
            'stat_ip' => $stat_ip, //true если фильтр по определенному IP
            'black_list' => $black_list,
        ]);
    }

    /**
     * Обработка форм - форма входа и формы со страницы статистики
     *
     * @return bool
     */
    public function actionForms(){

        $request = Yii::$app->request->post();
        $count_model = $request;

        $session = Yii::$app->session;

        //Валидация формы входа
        if(isset($count_model['enter'])) {
            $validate = $this->validatePassword($request, $session);
            if(!$validate) return false;
        }

        /*
         * Формы выбора параметров вывода статистики
         */
        $condition = [];
        $stat_ip = false;

        $model = new KslStatistic();


        //Сброс фильтров
        if(isset($count_model['reset'])){
            $condition = [];
        }

        if(isset($count_model['date_ip'])){
            $time = strtotime($count_model['date_ip']);
            $time_max = $time + 86400;
            $condition = ["date_ip", $time , $time_max];
        }


        //За период
        if(isset($count_model['period'])){

            if(!empty($count_model['start_time'])){
                $timeStartUnix = strtotime($count_model['start_time']);
            } else {
                $sec_todey = time() - strtotime('today'); //сколько секунд прошло с начала дня
                $timeStartUnix = time() - $sec_todey;
            }

            //Если не передана дата конца - ставим текущую
            if(empty($count_model['stop_time'])) {
                $timeStopUnix = time();
            } else {
                $timeStopUnix = strtotime($count_model['stop_time']);
            }

            $timeStopUnix += 86400; //целый день (до конца суток)
            $condition = ["date_ip", $timeStartUnix , $timeStopUnix];
        }


        //По IP
        if(isset($count_model['search_ip'])){

            $condition = ["ip" => $count_model['ip']];
            $stat_ip = true;

            if(!$count_model['ip']) $session->setFlash('danger', 'Укажите IP для поиска');
        }


        //Добавить в черный список
        if(isset($count_model['add_black_list'])){

            if(!$count_model['ip']){

                $session->setFlash('danger', 'Укажите IP для добавления в черный список');

            } else {

                if(!isset($count_model['comment'])) $count_model['comment'] ='';
                $model->set_black_list($count_model['ip'], $count_model['comment']);
            }
        }

        //Удалить из черного списка
        if(isset($count_model['del_black_list'])){

            if(!$count_model['ip']){
                $session->setFlash('danger', 'Укажите IP для удаления из черного списка');

            } else {
                $model->remove_black_list($count_model['ip']);
            }
        }

        //Удалить старые данные
        if(isset($count_model['del_old'])){
            $model->remove_old();
        }

        $view = $this->actionIndex($condition, $stat_ip);
        echo $view;
    }


    /**
     * Проверка введенного пароля
     *
     * @param array $request
     * @param yii\web\Session $session
     * @return bool
     */
    public function validatePassword($request, $session)
    {
        $password_config = KslStatistic::getParameters()['password'];
        $password_enter = $request['password'];

        if ($password_config == $password_enter) {
            $session->set('ksl-statistics', $password_config);
            $this->redirect(Yii::$app->urlManager->createUrl('statistics/stat/index'))->send();

        } else {
            $session->setFlash('danger', 'Неверный пароль');
            return false;
        }
        return true;
    }

}

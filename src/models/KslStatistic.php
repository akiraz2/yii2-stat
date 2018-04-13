<?php
/**
 * Project: yii2-stat
 * Author: akiraz2
 * License: MIT
 * Copyright (c) 2018.
 */

namespace akiraz2\stat\Models;

use Yii;
use yii\db\ActiveRecord;


/**
 * Модель для работы расширения
 *
 * @package akiraz2\stat\Models
 *
 * @property string $ip
 * @property string $str_url
 * @property integer $date_ip
 * @property boolean $black_list_ip
 */
class KslStatistic extends ActiveRecord{

    /**
     * Название таблицы
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%ksl_ip_count}}';
    }

    /**
     * Правила
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['ip'], 'ip'],
            [['str_url'], 'url'],
            [['date_ip'], 'safe'],
            [['black_list_ip'], 'boolean'],
        ];
    }


    /**
     * Получение массива настроек расширения
     *
     * @return array
     */
    public static function getParameters(){
        return [

            //кол-во дней для вывода статистики по-умолчанию (сегодня/вчера/...)
            'days_default' => isset(Yii::$app->params['statistics']['days_default']) ? Yii::$app->params['statistics']['days_default'] : 3,

            //пароль для входа на страницу статистики. Если false (без кавычек) - вход без пароля
            'password' => isset(Yii::$app->params['statistics']['password']) ? Yii::$app->params['statistics']['password'] : 'klisl',

            //если true, то статистика доступна только аутентифицированным пользователям
            'authentication' => isset(Yii::$app->params['statistics']['authentication']) ? Yii::$app->params['statistics']['authentication'] :  false,

            //контроллер/действие для страницы аутентификации (по-умолчанию 'site/login')
            'auth_route' => isset(Yii::$app->params['statistics']['auth_route']) ? Yii::$app->params['statistics']['auth_route'] : null,

            //удалять данные через х дней
            'date_old' => isset(Yii::$app->params['statistics']['date_old']) ? Yii::$app->params['statistics']['date_old'] : 90,
        ];
    }


    /**
     * Проверка наличия IP в черном списке (которые не надо выводить и сохранять в БД)
     * если есть хоть одна строка, то вернет true
     *
     * @param string $ip
     * @return bool
     */
    public function inspection_black_list($ip){

        $check = $this
            ->find()
            ->where(['=', 'ip', $ip])
            ->andWhere(['=', 'black_list_ip', 1])
            ->all();

        if (count($check)) return true;
    }


    /**
     * Запись в БД
     *
     * @param string $ip
     * @param string $str_url
     * @param int $black_list_ip
     */
    public function setCount($ip, $str_url, $black_list_ip = 0){
        $this->ip = $ip;
        $this->str_url = $str_url;
        $this->date_ip = time();
        $this->black_list_ip = $black_list_ip;
        $this->save();
    }


    /**
     * @param array $condition
     * @return array|ActiveRecord[]
     */
    public function getCount($condition = []){

        $sec_todey = time() - strtotime('today'); //сколько секунд прошло с начала дня

        //за сколько дней показывать по-умолчанию
        $days_show_stat = KslStatistic::getParameters()['days_default'] - 1 ;

        $days_ago = time() - (86400 * $days_show_stat) - $sec_todey;

        //Выбор диапазона между двумя датами
        if(in_array( 'date_ip',$condition)) {

            $count_ip = $this
                ->find()
                ->where(['<', 'black_list_ip', 1])
                ->andWhere(["between", $condition[0], $condition[1] , $condition[2]])
                ->orderBy('date_ip')
                ->all();

        } elseif($condition){

            $count_ip = $this
                ->find()
                ->where(['<', 'black_list_ip', 1])
                ->andWhere(['>', 'date_ip', $days_ago])
                ->andWhere(['=', 'ip', $condition['ip']])
                ->orderBy('date_ip')
                ->all();

        } else {

            $count_ip = $this
                ->find()
                ->where(['<','black_list_ip', 1])
                ->andWhere(['>', 'date_ip', $days_ago])
                ->orderBy('date_ip')
                ->all();
        }

        return $count_ip;
    }


    /**
     * Создание массива с IP и комментариями к ним из черного списка
     *
     * @return array
     */
    public function count_black_list(){
        $black_list = (new \yii\db\Query())
            ->select('ip')
            ->from('{{%ksl_ip_count}}')
            ->where(['black_list_ip' => 1])
            ->distinct() //уникальные значения
            ->all();
        //По полученному массиву IP получаем значение ячейки "comment"
        foreach ($black_list as $key=>$arr){
            $rez = self::find()->where(['ip' => $arr['ip']])->one();
            $black_list[$key]['comment'] = $rez->comment;
        }

        return $black_list;
    }



    /**
     * Добавление в черный список
     *
     * @param string $ip
     * @param string $comment
     * @return void
     */
    public function set_black_list($ip, $comment=''){

        $verify_black_list = $this->find()->where(['=', 'ip', $ip])->all();

        //Если такой IP уже есть (коллекция не пуста)
        if(!empty($verify_black_list)){
            foreach ($verify_black_list as $str){
                $str->black_list_ip = 1;
                $str->comment = $comment;
                $res = $str->save();
            }
        } else {
            $this->ip = $ip;
            $this->str_url = '';
            $this->black_list_ip = 1;
            $this->comment = $comment;
            $res = $this->save();
        }

        $session = Yii::$app->session;

        if($res){
            $session->setFlash('success', 'IP '.$ip.' добавлен в черный список');
        } else {
            $session->setFlash('danger', 'Ошибка добавления IP в черный список');
        }
    }


    /**
     * Удаление из черного списка
     *
     * @param string $ip
     * @return void
     */
    public function remove_black_list($ip){
        $res = null;

        $verify_black_list = $this->find()->where(['=', 'ip', $ip])->all();
        foreach ($verify_black_list as $str){
            $str->black_list_ip = 0;
            $str->comment = null;
            $res = $str->save();
        }

        $session = Yii::$app->session;

        if($res){
            $session->setFlash('success', 'IP '.$ip.' удален из черного списка');
        } else {
            $session->setFlash('danger', 'Ошибка удаления IP из черного списка.');
        }
    }


    /**
     * Удаление старых данных
     *
     * @return void
     */
    public function remove_old(){

        $today = time();
        $date_old = KslStatistic::getParameters()['date_old']; //за сколько дней удалять данные из БД

        $time = $today - (86400 * $date_old);

        $old = $this->find()->where(['<', 'date_ip', $time])->all();
        foreach($old as $str){
            $str->delete();
        }

        $session = Yii::$app->session;
        if(count($old)) $session->setFlash('success', 'Удалено '. count($old) . ' строк.');
        else $session->setFlash('success', 'Нет старых данных для удаления.');

    }

    /**
     * Проверка был ли такой IP в течении текущих суток (0-24)
     * Если да, то не добавляем в общий счетчик посетителей за день
     *
     * @param string $ip
     * @param integer $date
     * @return array|ActiveRecord[]
     */
    public function find_ip_by_day($ip, $date){

        //метка времени на начало указанного дня
        $time = strtotime(date("d.m.Y",$date));

        $time_now = $date - 1; //текущее время и день минус 1 секунда


        $res = $this->find()
            ->where(['=','ip', $ip])
            ->andWhere(["between", "date_ip", $time , $time_now])
            ->limit(1)
            ->all();

        return $res;
    }


    /**
     * Преобразуем коллекцию к виду, где элементы с более поздней датой идут в начале
     * при этом часы/минуты/секунды в расчет не берутся
     * Используется для вывода в начале таблицы текущей даты и дальше по убыванию
     *
     * @param KslStatistic[] $count_ip
     * @return KslStatistic[]
     */
    public function reverse($count_ip){


        if(!empty($count_ip)){

            /*
             * Если дата у следующего элемента отличается, то
             *
             */
            $array = [];
            $count = 0;

            $first_day = date("Y-m-d",$count_ip[0]->date_ip);

            foreach ($count_ip as $item) {

                $one_day = date("Y-m-d",$item->date_ip);

                if ($first_day != $one_day) {
                    $count++;
                    $first_day = $one_day;
                    $array[$count][] = $item;
                } else {
                    $array[$count][] = $item;
                }
            };

            //соединяем массивы
            $new_array = [];
            foreach ($array as $i) {
                $new_array = array_merge($i,$new_array);
            }

            return $new_array;
        }
        return $count_ip;
    }

}
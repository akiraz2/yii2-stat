<?php
/**
 * Created by PhpStorm.
 * User: user4957
 * Date: 13.04.2018
 * Time: 12:59
 */

namespace akiraz2\stat;

use akiraz2\stat\traits\ModuleTrait;
use Yii;
use yii\base\BaseObject;

class CodeBuilder extends BaseObject
{
    use ModuleTrait;

    public $id;

    public $params = [];

    public function render()
    {
        return $this->getView()->render('@akiraz2/stat/views/code-yandex.php', $this->prepareData());
    }

    public function getView()
    {
        return Yii::$app->getView();
    }

    public function prepareData()
    {
        return [
            'id' => $this->getModule()->yandexMetrika['id'],
            'params' => $this->prepareParams(),
        ];
    }

    public function prepareParams()
    {
        return array_filter(array_merge(
            $this->getModule()->yandexMetrika['params'],
            [
                'id' => $this->getModule()->yandexMetrika['id'],
            ]));
    }
}
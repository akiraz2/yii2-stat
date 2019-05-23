<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

namespace akiraz2\stat;

use akiraz2\stat\traits\ModuleTrait;
use Yii;
use yii\base\BaseObject;
use yii\helpers\Inflector;

class CodeBuilder extends BaseObject
{
    use ModuleTrait;

    public $id;

    public $params = [];

    public function render($counter_name)
    {
        $view_name = Inflector::camel2id($counter_name);
        return $this->getView()->render("@akiraz2/stat/views/code-$view_name.php", $this->prepareData($counter_name));
    }

    public function getView()
    {
        return Yii::$app->getView();
    }

    public function prepareData($counter_name)
    {
        $module_property = $this->getModule()->__get($counter_name);

        return [
            'id' => $module_property['id'],
            'params' => isset($module_property['params']) ? $this->prepareParams($module_property) : [],
        ];
    }

    public function prepareParams($module_property)
    {
        return array_filter(array_merge(
            [
                'id' => $module_property['id'],
            ],
            $module_property['params']
        ));
    }
}

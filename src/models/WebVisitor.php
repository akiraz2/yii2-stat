<?php

namespace akiraz2\stat\models;

use akiraz2\stat\Module;
use akiraz2\stat\traits\ModuleTrait;

/**
 * This is the model class for table "{{%webstat_visitor}}".
 *
 * @property int $id
 * @property string $cookie_id
 * @property int $source
 * @property int user_id
 * @property string $ip_address
 * @property string $url
 * @property string $user_agent
 * @property string $referrer
 * @property string $created_at
 */
class WebVisitor extends \yii\db\ActiveRecord
{
    use ModuleTrait;

    const TYPE_UNKNOWN = 0;
    const TYPE_INNER = 1;
    const TYPE_DIRECT = 2;
    const TYPE_SEARCH = 3;
    const TYPE_ADS = 4;

    public $visits;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%webstat_visitor}}';
    }

    public static function getStat($source)
    {
        return self::find()->where(['source' => $source])->count('id');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cookie_id', 'ip_address', 'url'], 'required'],
            [['created_at'], 'safe'],
            [['cookie_id'], 'string', 'max' => 32],
            [['source'], 'integer', 'max' => 4, 'min' => 0],
            [['ip_address'], 'string', 'max' => 15],
            [['url', 'referrer', 'user_agent'], 'string', 'max' => 255],
            ['user_id', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('stat', 'ID'),
            'cookie_id' => Module::t('stat', 'Cookie ID'),
            'source' => Module::t('stat', 'Source'),
            'ip_address' => Module::t('stat', 'Ip Address'),
            'url' => Module::t('stat', 'Url'),
            'referrer' => Module::t('stat', 'Referrer'),
            'user_agent' => Module::t('stat', 'User Agent'),
            'created_at' => Module::t('stat', 'Created At'),
        ];
    }

    public function getSource()
    {
        $statuses = self::getSourceList();
        return (isset($this->source)) ? $statuses[$this->source] : '-';
    }

    public static function getSourceList()
    {
        return [
            self::TYPE_UNKNOWN => Module::t('stat', 'Unknown'),
            self::TYPE_INNER => Module::t('stat', 'Inner'),
            self::TYPE_DIRECT => Module::t('stat', 'Direct'),
            self::TYPE_SEARCH => Module::t('stat', 'Search'),
            self::TYPE_ADS => Module::t('stat', 'Ads'),
        ];
    }
}

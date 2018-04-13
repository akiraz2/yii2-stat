<?php
/**
 * Project: yii2-stat
 * Author: akiraz2
 * License: MIT
 * Copyright (c) 2018.
 */

use yii\db\Migration;


/**
 * Миграция для создания индексов в таблице ksl_ip_count
 */
class m170307_083010_statistics extends Migration
{

    public function up()
    {
        $this->createIndex('{{%idx-ip_count-ip}}', '{{%stat_ip_count}}', 'ip');
        $this->createIndex('{{%idx-ip_count-date_ip}}', '{{%stat_ip_count}}', 'date_ip');
        $this->createIndex('{{%idx-ip_count-black_list_ip}}', '{{%stat_ip_count}}', 'black_list_ip');

        $this->createIndex('{{%idx-ip_count-date_ip-black_list_ip}}', '{{%stat_ip_count}}', ['date_ip', 'black_list_ip']);
        $this->createIndex('{{%idx-ip_count-ip-date_ip}}', '{{%stat_ip_count}}', ['ip', 'date_ip']);
    }

    public function down()
    {
        $this->dropIndex('{{%idx-ip_count-ip}}', '{{%stat_ip_count}}');
        $this->dropIndex('{{%idx-ip_count-date_ip}}', '{{%stat_ip_count}}');
        $this->dropIndex('{{%idx-ip_count-black_list_ip}}', '{{%stat_ip_count}}');
        $this->dropIndex('{{%idx-ip_count-date_ip-black_list_ip}}', '{{%stat_ip_count}}');
        $this->dropIndex('{{%idx-ip_count-ip-date_ip}}', '{{%stat_ip_count}}');
    }

}

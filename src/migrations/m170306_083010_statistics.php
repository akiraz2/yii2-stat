<?php
/**
 * @project: yii2-stat
 * @description Multi web stat and analytics module
 * @author: akiraz2
 * @license: MIT
 * @copyright (c) 2018.
 */

use yii\db\Migration;


/**
 * Миграция для создания таблицы ksl_ip_count расширения
 */
class m170306_083010_statistics extends Migration
{

    public function safeUp()
    {
		$tableOptions = null;
		//Опции для mysql
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

		//Создание таблицы IP пользователей
		$this->createTable('{{%stat_ip_count}}', [
			'id' => $this->primaryKey(),
			'ip' => $this->string(15)->notNull(),
			'str_url' => $this->string(255),
			'date_ip' => $this->integer(),
			'black_list_ip' => $this->boolean()->defaultValue(0)->notNull(),
			'comment' => $this->string(50),
		], $tableOptions);

    }

    public function safeDown()
    {
			$this->dropTable('{{%ksl_ip_count}}');
    }
}

<?php

use yii\db\Schema;
use yii\db\Migration;

class m180414_195616_webstat_visitor extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%webstat_visitor}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'cookie_id'=> $this->string(32)->notNull(),
                'user_id'=> $this->integer(11)->null()->defaultValue(null),
                'source'=> $this->tinyInteger(1)->null()->defaultValue(null),
                'ip_address'=> $this->string(15)->notNull(),
                'url'=> $this->string(255)->notNull(),
                'referrer'=> $this->string(255)->null()->defaultValue(null),
                'user_agent'=> $this->string(255)->null()->defaultValue(null),
                'created_at'=> $this->timestamp()->notNull()->defaultExpression("CURRENT_TIMESTAMP"),
            ],$tableOptions
        );
        $this->createIndex('cookie_id','{{%webstat_visitor}}',['cookie_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('cookie_id', '{{%webstat_visitor}}');
        $this->dropTable('{{%webstat_visitor}}');
    }
}

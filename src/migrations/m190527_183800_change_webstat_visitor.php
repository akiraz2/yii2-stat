<?php

use yii\db\Migration;

class m190527_183800_change_webstat_visitor extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';
        $this->alterColumn('{{%webstat_visitor}}', 'ip_address', $this->string(45)->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%webstat_visitor}}', 'ip_address', $this->string(19)->notNull());
    }
}

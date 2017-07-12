<?php

use yii\db\Migration;

class m170712_071421_site_catalog extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%catalog}}', 'site', $this->string());
    }

    public function safeDown()
    {
        echo "m170712_071421_site_catalog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_071421_site_catalog cannot be reverted.\n";

        return false;
    }
    */
}

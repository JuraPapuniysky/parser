<?php

use yii\db\Migration;

class m170712_065231_update_catalog_add_link extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%catalog}}', 'link', $this->text());
    }

    public function safeDown()
    {
        echo "m170712_065231_update_catalog_add_link cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_065231_update_catalog_add_link cannot be reverted.\n";

        return false;
    }
    */
}

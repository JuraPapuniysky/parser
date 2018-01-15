<?php

use yii\db\Migration;

class m180115_054901_bad_links extends Migration
{
    public function safeUp()
    {
        $this->createTable('bad_links', [
            'id' => $this->primaryKey(),
            'link' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    public function safeDown()
    {
        echo "m180115_054901_bad_links cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180115_054901_bad_links cannot be reverted.\n";

        return false;
    }
    */
}

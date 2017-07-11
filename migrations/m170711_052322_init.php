<?php

use yii\db\Migration;

class m170711_052322_init extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{catalog}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text(),
            'contact' => $this->text(),
            'email' => $this->text(),
            'activity' => $this->text(),
            'address' => $this->text(),
            'comment' => $this->text()
        ]);
    }

    public function safeDown()
    {
        echo "m170711_052322_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170711_052322_init cannot be reverted.\n";

        return false;
    }
    */
}

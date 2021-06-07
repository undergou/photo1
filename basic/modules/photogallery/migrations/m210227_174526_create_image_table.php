<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%image}}`.
 */
class m210227_174526_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'author' => $this->string(50),
            'category' => $this->string(50),
            'title' => $this->string(50),
            'date' => $this->string(50),
            'status' => $this->string(50),
            'extension' => $this->string(50),
            'image' => $this->string(50),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%image}}');
    }
}

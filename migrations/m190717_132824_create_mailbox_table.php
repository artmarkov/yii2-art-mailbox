<?php

use yii\db\Migration;

class m190717_132824_create_mailbox_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mailbox}}', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
            'folder' => $this->tinyInteger()->notNull()->defaultValue('0'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'posted_at' => $this->integer(),
            'deleted_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('sender_id', '{{%mailbox}}', 'sender_id');
        $this->addForeignKey('mailbox_ibfk_1', '{{%mailbox}}', 'sender_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        
       $this->createTable('{{%mailbox_inbox}}', [
            'id' => $this->primaryKey(),
            'mailbox_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'folder' => $this->tinyInteger()->defaultValue('0'),
            'status' => $this->tinyInteger()->defaultValue('0'),
            'reading_at' => $this->integer(),
            'deleted_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('mailbox_id', '{{%mailbox_inbox}}', 'mailbox_id');
        $this->createIndex('receiver_id', '{{%mailbox_inbox}}', 'receiver_id');
        $this->addForeignKey('mailbox_inbox_ibfk_1', '{{%mailbox_inbox}}', 'receiver_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('mailbox_inbox_ibfk_2', '{{%mailbox_inbox}}', 'mailbox_id', '{{%mailbox}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%mailbox_inbox}}');
        $this->dropTable('{{%mailbox}}');
    }
}

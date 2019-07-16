<?php

use yii\db\Migration;

class m190716_134457_create_mailbox_table extends Migration
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
            'draft_flag' => $this->tinyInteger()->notNull()->defaultValue('0'),
            'remote_flag' => $this->tinyInteger()->notNull()->defaultValue('0'),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'posted_at' => $this->integer(),
            'remoted_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('sender_id', '{{%mailbox}}', 'sender_id');
        $this->addForeignKey('mailbox_ibfk_1', '{{%mailbox}}', 'sender_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->createTable('{{%mailbox_receiver}}', [
            'id' => $this->primaryKey(),
            'mailbox_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'read_flag' => $this->tinyInteger()->defaultValue('0'),
            'remote_flag' => $this->tinyInteger()->defaultValue('0'),
            'created_at' => $this->integer(),
            'reading_at' => $this->integer(),
            'remoted_at' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('mailbox_id', '{{%mailbox_receiver}}', 'mailbox_id');
        $this->createIndex('receiver_id', '{{%mailbox_receiver}}', 'receiver_id');
        $this->addForeignKey('mailbox_receiver_ibfk_1', '{{%mailbox_receiver}}', 'receiver_id', '{{%user}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('mailbox_receiver_ibfk_2', '{{%mailbox_receiver}}', 'mailbox_id', '{{%mailbox}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%mailbox_receiver}}');
        $this->dropTable('{{%mailbox}}');
    }
}

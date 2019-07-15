<?php

use yii\db\Migration;

class m160418_150101_create_block_table extends Migration
{

    const TABLE_NAME = '{{%block}}';
    const TABLE_LANG_NAME = '{{%block_lang}}';

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE_NAME, [
            'id' => $this->primaryKey(),
            'slug' => $this->string(200)->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->addForeignKey('fk_block_created_by', self::TABLE_NAME, ['created_by'], '{{%user}}', ['id'], 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk_block_updated_by', self::TABLE_NAME, ['updated_by'], '{{%user}}', ['id'], 'SET NULL', 'CASCADE');

        $this->createIndex('block_slug', self::TABLE_NAME, 'slug', true);

        $this->createTable(self::TABLE_LANG_NAME, [
            'id' => $this->primaryKey(),
            'block_id' => $this->integer()->notNull(),
            'language' => $this->string(6)->notNull(),
            'title' => $this->text(),
            'content' => $this->text(),
        ], $tableOptions);

        $this->createIndex('block_lang_post_id', self::TABLE_LANG_NAME, 'block_id');
        $this->createIndex('block_lang_language', self::TABLE_LANG_NAME, 'language');
        $this->addForeignKey('fk_block_lang', self::TABLE_LANG_NAME, 'block_id', self::TABLE_NAME, 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_block_created_by', self::TABLE_NAME);
        $this->dropForeignKey('fk_block_updated_by', self::TABLE_NAME);
        $this->dropForeignKey('fk_block_lang', self::TABLE_LANG_NAME);
        $this->dropTable(self::TABLE_LANG_NAME);
        $this->dropTable(self::TABLE_NAME);
    }

}

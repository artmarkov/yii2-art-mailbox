<?php

use yii\db\Migration;

class m160418_150644_add_block_menu_links extends Migration
{

    public function up()
    {
        $this->insert('{{%menu_link}}', ['id' => 'block', 'menu_id' => 'admin-menu', 'link' => '/block/default/index', 'image' => 'clone', 'created_by' => 1, 'order' => 18]);
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'block', 'label' => 'HTML Blocks', 'language' => 'en-US']);
    }

    public function down()
    {
        $this->delete('{{%menu_link}}', ['like', 'id', 'block']);
    }
}
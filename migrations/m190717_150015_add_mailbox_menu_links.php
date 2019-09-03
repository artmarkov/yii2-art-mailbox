<?php

use yii\db\Migration;

class m190717_150015_add_mailbox_menu_links extends Migration
{

    public function up()
    {
        $this->insert('{{%menu_link}}', ['id' => 'mailbox', 'menu_id' => 'admin-menu', 'link' => '/mailbox/default/index', 'image' => 'inbox', 'created_by' => 1, 'order' => 999]);
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'mailbox', 'label' => 'Mailbox', 'language' => 'en-US']);
    }

    public function down()
    {
        $this->delete('{{%menu_link}}', ['like', 'id', 'mailbox']);
    }
}
<?php

use yii\db\Migration;

class m190903_142545_i18n_ru_menu_mailbox extends Migration
{

    public function up()
    {
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'mailbox', 'label' => 'Почта', 'language' => 'ru']);
    }

}
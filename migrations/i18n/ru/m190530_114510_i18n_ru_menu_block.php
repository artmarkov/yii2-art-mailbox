<?php

use yii\db\Migration;

class m190530_114510_i18n_ru_menu_block extends Migration
{

    public function up()
    {
        $this->insert('{{%menu_link_lang}}', ['link_id' => 'block', 'label' => 'HTML Блоки', 'language' => 'ru']);
    }

}
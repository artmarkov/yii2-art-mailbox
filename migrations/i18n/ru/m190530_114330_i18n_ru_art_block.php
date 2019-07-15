<?php

use artsoft\db\TranslatedMessagesMigration;

class m190530_114330_i18n_ru_art_block extends TranslatedMessagesMigration
{

    public function getLanguage()
    {
        return 'ru';
    }

    public function getCategory()
    {
        return 'art/block';
    }

    public function getTranslations()
    {
        return [
            'HTML Block' => 'HTML Блок',
            'HTML Blocks' => 'HTML Блоки',
        ];        
    }
}
<?php

use artsoft\db\SourceMessagesMigration;

class m160418_233615_i18n_art_block_source extends SourceMessagesMigration
{

    public function getCategory()
    {
        return 'art/block';
    }

    public function getMessages()
    {
        return [
            'HTML Block' => 1,
            'HTML Blocks' => 1,
        ];
    }
}
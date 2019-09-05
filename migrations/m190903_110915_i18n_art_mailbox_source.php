<?php

use artsoft\db\SourceMessagesMigration;

class m190903_110915_i18n_art_mailbox_source extends SourceMessagesMigration
{

    public function getCategory()
    {
        return 'art/mailbox';
    }

    public function getMessages()
    {
        return [           
            'Are you sure you want to trash this mail?' => 1,
            'Are you sure you want to clear all trash?' => 1,
            'Are you sure you want to clear own trash?' => 1,
            'Are you sure you want to delete this mail?' => 1,
            'Back to Inbox' => 1,
            'Compose' => 1,
            'Clear all trash' => 1,
            'Clear own trash' => 1,
            'Discard' => 1,
            'Draft' => 1,
            'Drafts' => 1,
            'Deleted At' => 1,
            'Forward' => 1,
            'Folders' => 1,
            'From:' => 1,
            'Inbox' => 1,
            'Mailboxes' => 1,
            'Move to Trash' => 1,
            'Mark Read' => 1,
            'Mark Unread' => 1,
            'Print' => 1,
            'Receivers' => 1,
            'Reading At' => 1,
            'Remoted At' => 1,
            'Restore' => 1,
            'Reply' => 1,
            'Read mail' => 1,
            'Status Read' => 1,         
            'Status Post' => 1,         
            'Status Del' => 1,
            'Sender ID' => 1,
            'Sender' => 1,
            'Send' => 1,
            'Sent' => 1,
            'To:' => 1,
            'Trash' => 1,            
            'Your mail has been moved to the trash folder.' => 1,
            'Action error occurred.' => 1,
            'Your mail has been restory.' => 1,
            'Your mail has been deleted.' => 1,
            'Your cart has been emptied.' => 1,
            'Your cart is empty.' => 1,
            'All carts has been emptied.' => 1,
            'All carts is empty.' => 1,
            'Your mail has been posted.' => 1,
            'Your mail has been moved to the drafts folder.' => 1,
            'New' => 1,
            'Read' => 1,
        ];
    }
}
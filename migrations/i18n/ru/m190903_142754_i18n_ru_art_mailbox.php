<?php

use artsoft\db\TranslatedMessagesMigration;

class m190903_142754_i18n_ru_art_mailbox extends TranslatedMessagesMigration
{

    public function getLanguage()
    {
        return 'ru';
    }

    public function getCategory()
    {
        return 'art/mailbox';
    }

    public function getTranslations()
    {
        return [                      
            'Are you sure you want to trash this mail?' => 'Вы уверены, что хотите перенести в корзину это письмо?',
            'Are you sure you want to clear all trash?' => 'Вы уверены, что хотите очистить все корзины?',
            'Are you sure you want to clear own trash?' => 'Вы уверены, что хотите очистить свою корзину?',
            'Are you sure you want to delete this mail?' => 'Вы уверены, что хотите удалить это письмо?',
            'Back to Inbox' => 'Вернуться',
            'Compose' => 'Написать письмо',
            'Clear all trash' => 'Очистить все корзины',
            'Clear own trash' => 'Очистить корзину',
            'Discard' => 'Сбросить',
            'Draft' => 'Черновик',
            'Drafts' => 'Черновики',
            'Deleted At' => 'Удалено',
            'Forward' => 'Переслать',
            'Folders' => 'Папки',
            'From:' => 'От:',
            'Inbox' => 'Входящие',
            'Mailboxes' => 'Почта',
            'Move to Trash' => 'Перенести в Корзину',
            'Mark Read' => 'Пометить прочитанным',
            'Mark Unread' => 'Пометить непрочитанным',
            'Posted At' => 'Отправлено',
            'Print' => 'Печать',
            'Receivers' => 'Получатели',
            'Reading At' => 'Прочитано',
            'Remoted At' => 'Удалено',
            'Restore' => 'Востановить',
            'Reply' => 'Ответить',
            'Read mail' => 'Прочитать письмо',
            'Status Post' => 'Статус Отправки',
            'Status Del' => 'Статус Удаления',   
            'Sender ID' => 'ID Отправителя',
            'Sender' => 'Отправитель',
            'Send' => 'Отправить',
            'Sent' => 'Отправленные',
            'To:' => 'Кому:',
            'Trash' => 'Корзина',
            'Your mail has been moved to the trash folder.' => 'Ваше письмо было перемещено в корзину.',
            'Action error occurred.' => 'Произошла ошибка.',
            'Your mail has been restory.' => 'Ваше письмо было восстановлено.',
            'Your mail has been deleted.' => 'Ваше письмо было удалено.',
            'Your cart has been emptied.' => 'Ваша корзина была очищена.',
            'Your cart is empty.' => 'Ваша корзина пуста.',
            'All carts has been emptied.' => 'Все корзины были очищены.',
            'All carts is empty.' => 'Все корзины пусты.',
            'Your mail has been posted.' => 'Ваше писмо отправлено.',
            'Your mail has been moved to the drafts folder.' => 'Ваше письмо было перемещено в папку Черновики',
            'New' => 'Новое',
            'Read' => 'Прочитано',
        ];        
    }
}
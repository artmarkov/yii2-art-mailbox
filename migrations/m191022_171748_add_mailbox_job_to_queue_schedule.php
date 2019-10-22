<?php

use yii\db\Migration;

class m191022_171748_add_mailbox_job_to_queue_schedule extends Migration
{
    public function up()
    {
       $this->insert('{{%queue_schedule}}', ['class' => 'artsoft\mailbox\jobs\ClianDeletedMailJob', 'title' => 'Уничтожение удаленных писем', 'content' => 'Удаляет все письма физически из корзины.', 'cron_expression' => '0 0 */15 * *', 'priority' => 1024 ,'created_at' => time(), 'updated_at' => time(), 'created_by' => 1, 'updated_by' => 1]);
       $this->insert('{{%queue_schedule}}', ['class' => 'artsoft\mailbox\jobs\MessageNewEmailJob', 'title' => 'Оповещение пользователей о новых сообщениях', 'content' => '', 'cron_expression' => '0 8 * * *', 'priority' => 1024 ,'created_at' => time(), 'updated_at' => time(), 'created_by' => 1, 'updated_by' => 1]);
    }

    public function down()
    {
        $this->delete('{{%queue_schedule}}', ['class' => 'artsoft\mailbox\jobs\ClianDeletedMailJob']);
        $this->delete('{{%queue_schedule}}', ['class' => 'artsoft\mailbox\jobs\MessageNewEmailJob']);
    }
}

<?php

namespace artsoft\mailbox\jobs;
use artsoft\mailbox\models\MailboxInbox;

/**
 * Class MessageNewEmailstJob.
 */
class MessageNewEmailsJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        foreach ($this->getQtyNewMail() as $model) {
            sendEmail($model);
        }
    }
    
    /**
     * 
     * @return type
     */
    public static function getQtyNewMail() {
        return MailboxInbox::find()
                        ->joinWith(['receiver'])
                        ->select(['receiver_id', 'COUNT(*) AS qty'])
                        ->groupBy('receiver_id')
                        ->readNew()
                        ->asArray()
                        ->all();
    }

    public function sendEmail($model)
    {
        return Yii::$app->mailer->compose(Yii::$app->getModule('queue-schedule')->emailTemplates['message-new-emails'],
            ['model' => $model])
            ->setFrom(Yii::$app->art->emailSender)
            ->setTo($model->email)
            ->setSubject(Yii::t('art/mailbox', 'Message from the site') . ' ' . Yii::$app->name)
            ->send();
    }
}

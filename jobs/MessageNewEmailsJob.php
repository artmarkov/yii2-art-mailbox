<?php

namespace artsoft\mailbox\jobs;
use artsoft\mailbox\models\MailboxInbox;

/**
 * Class MessageNewEmailstJob.
 */
class MessageNewEmailsJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $text;

    public $file;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        
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

    public function sendEmail($id)
    {
        $model = User::find()->where(['id' => $id])->one();
        if(!$model) {
            return false;
        }
        
        return Yii::$app->mailer->compose(Yii::$app->getModule('queue-schedule')->emailTemplates['message-new-emails'],
            ['model' => $this->model])
            ->setFrom(Yii::$app->art->emailSender)
            ->setTo($model->email)
            ->setSubject(Yii::t('art/mailbox', 'Message from the site') . ' ' . Yii::$app->name)
            ->send();
    }
}

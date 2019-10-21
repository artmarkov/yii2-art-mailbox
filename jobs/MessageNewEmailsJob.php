<?php

namespace artsoft\mailbox\jobs;
use artsoft\mailbox\models\MailboxInbox;
use Yii;

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
             $this->sendEmail($model);
        }
    }
    
    /**
     * 
     * @return type
     */
    public static function getQtyNewMail() {
        return MailboxInbox::find()
                        ->joinWith(['receiver'])
                        ->select(['receiver_id', 'username', 'email', 'COUNT(*) AS qty'])
                        ->groupBy('receiver_id')
                        ->readNew()
                        ->andWhere(['status' => \artsoft\models\User::STATUS_ACTIVE])
                        ->asArray()
                        ->all();
    }

    public static function sendEmail($model)
    {
        $textBody = 'Здравствуйте ' . strip_tags($model['username']) . PHP_EOL;
        $textBody .= 'Qty ' . strip_tags($model['qty']) . PHP_EOL . PHP_EOL;

        $htmlBody = '<p><b>Здравствуйте</b>: ' . strip_tags($model['username']) . '</p>';
        $htmlBody .= '<p><b>Qty</b> ' . strip_tags($model['qty']) . '</p>';

        return Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($model['email'])
            ->setSubject('Сообщение с сайта ' . Yii::$app->name)
            ->setTextBody($textBody)
            ->setHtmlBody($htmlBody)
            ->send();
    }
}

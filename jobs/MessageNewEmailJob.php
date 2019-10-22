<?php

namespace artsoft\mailbox\jobs;
use artsoft\mailbox\models\MailboxInbox;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class MessageNewEmailstJob.
 */
class MessageNewEmailJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
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
    protected static function getQtyNewMail() {
        return MailboxInbox::find()
                        ->joinWith(['receiver'])
                        ->select(['receiver_id', 'COUNT(*) AS qty'])
                        ->groupBy('receiver_id')
                        ->readNew()
                        ->andWhere(['status' => \artsoft\models\User::STATUS_ACTIVE])
                        ->asArray()
                        ->all();
    }
    /**
     * 
     * @param type $model
     * @return type
     */
    protected static function sendEmail($model)
    {
        $link = Url::to(['/admin/mailbox'], true);
        
        $textBody = 'Здравствуйте, ' . strip_tags($model['receiver']['username']) . PHP_EOL;
        $textBody .= 'По состоянию на ' . date('d.n.Y H:i', time()) . ' в Ваш почтовый ящик поступило ' . strip_tags($model['qty']) . 'новых писем.' . PHP_EOL . PHP_EOL;
        $textBody .=  Html::a(Html::encode($link), $link);

        $htmlBody = '<p><b>Здравствуйте</b>, ' . strip_tags($model['receiver']['username']) . '</p>';
        $htmlBody .= '<p>По состоянию на ' . date('d.n.Y H:i', time()) . ' в Ваш почтовый ящик поступило <b>' . strip_tags($model['qty']) . '</b> новых писем.</p>';        
        $htmlBody .= '<p>' . Html::a(Html::encode($link), $link) . '</p>';
        
        return Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($model['receiver']['email'])
            ->setSubject('Сообщение с сайта ' . Yii::$app->name)
            ->setTextBody($textBody)
            ->setHtmlBody($htmlBody)
            ->send();
    }
}

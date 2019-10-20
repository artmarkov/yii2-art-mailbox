<?php
/**
 * @var $this yii\web\View
 * @var $user artsoft\models\User
 * @var $model artsoft\models\Mailbox
 */
use yii\helpers\Html;

$link = Yii::$app->urlManager->createAbsoluteUrl(['/auth/default/confirm-email-receive', 'token' => $model->token]);
?>

<div class="message-new-emails">
    <p><?= Yii::t('art/mailbox', 'Hello, {username}.', [
            'username' => Html::encode($user->username)
        ])
        ?></p>

    <p><?= Yii::t('art/mailbox', 'On the site {host} in Your personal account received {qty} new messages.', [
        'host' => Yii::$app->urlManager->hostInfo, 'qty' => $model->qty,
    ]) ?></p>
    <p></p>
    <p><?= Yii::t('art/mailbox', 'To view the messages follow the link:') ?></p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
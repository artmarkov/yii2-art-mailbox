<?php

use yii\widgets\DetailView;
use artsoft\helpers\Html;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\MailboxReceiver */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailbox Receivers'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailbox-receiver-view">

    <h3 class="lte-hide-title"><?=  Html::encode($this->title) ?></h3>

    <div class="panel panel-default">
        <div class="panel-body">

            <p>
               
                <?= Html::a('Delete', ['/mailbox-receiver/default/delete', 'id' => $model->id],
                    [
                    'class' => 'btn btn-sm btn-danger',
                    'data' => [
                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ])
                ?>
               
            </p>


            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
            'id',
            'mailbox_id',
            'receiver_id',
            'status',
            'reading_at',
            'remoted_at',
                ],
            ])
            ?>

        </div>
    </div>

</div>

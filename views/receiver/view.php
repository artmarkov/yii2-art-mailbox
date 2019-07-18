<?php

use yii\widgets\DetailView;
use artsoft\helpers\Html;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\MailboxReceiver */

$this->title = $model->mailbox->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailbox Receivers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailbox-receiver-view">

    <h3 class="lte-hide-title"><?= Html::encode($this->title) ?></h3>

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-body">                   

                    <?= $this->render('../_menu', compact('model')) ?>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>

                        <?=
                        Html::a('Delete', ['/mailbox/receiver/delete', 'id' => $model->id], [
                            'class' => 'btn btn-sm btn-danger',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ])
                        ?>

                    </p>


                    <?=
                    DetailView::widget([
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
    </div>
</div>



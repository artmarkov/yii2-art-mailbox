<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use artsoft\grid\GridView;
use artsoft\grid\GridQuickLinks;
use artsoft\mailbox\models\Mailbox;
use artsoft\mailbox\models\MailboxReceiver;
use artsoft\helpers\Html;
use artsoft\grid\GridPageSize;

/* @var $this yii\web\View */
/* @var $searchModel artsoft\mailbox\models\search\MailboxReceiverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('art/mailbox', 'Inbox');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mailbox-receiver-index">

    <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?=  Html::encode($this->title) ?></h3>         
        </div>
    </div>

   <div class="row">
        <div class="col-md-3">
            <?= Html::a(Yii::t('art/mailbox', 'Compose'), ['/mailbox/default/compose'], ['class' => 'btn btn-primary btn-block margin-bottom']) ?>
         
            <div class="panel panel-default">
            <div class="box-header with-border">
                <h3 class="box-title">Folders</h3>
            </div>
                
                <div class="box-body no-padding">                   

                    <?= $this->render('_menu', compact('model')) ?>

                </div>
            </div>
        </div>
	<div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            /* Uncomment this to activate GridQuickLinks */
                            /*  echo GridQuickLinks::widget([
                                'model' => Mailbox::className(),
                                'searchModel' => $searchModel,                                 
                            ]) */
                            ?>
                        </div>

                        <div class="col-sm-6 text-right">
                            <?= GridPageSize::widget(['pjaxId' => 'mailbox-receiver-grid-pjax']) ?>
                        </div>
                    </div>

                    <?php
                    Pjax::begin([
                        'id' => 'mailbox-receiver-grid-pjax',
                    ])
                    ?>

                    <?=
                    GridView::widget([
                        'id' => 'mailbox-receiver-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'bulkActionOptions' => [
                            'gridId' => 'mailbox-receiver-grid',
                            'actions' => [
                                Url::to(['bulk-mark-as-read']) => Yii::t('art/mailbox', 'Mark as read'),                       
                                Url::to(['bulk-mark-unread']) => Yii::t('art/mailbox', 'Mark unread'),  
                                Url::to(['bulk-draft']) => Yii::t('art/mailbox', 'Move to Draft'),             
                                Url::to(['bulk-trush']) => Yii::t('art/mailbox', 'Move to Trash'),                   
                                Url::to(['bulk-truncate']) => Yii::t('art/mailbox', 'Truncate'),                     
                            ] //Configure here you bulk actions
                        ],
                        'columns' => [
                            ['class' => 'artsoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                            [
                                'attribute' => 'mailboxSenderId',
                                'label' => Yii::t('art/mailbox', 'Sender'),
                                'filter' => artsoft\models\User::getUsersList(),
                                'class' => 'artsoft\grid\columns\TitleActionColumn',
                                'controller' => '/mailbox/receiver',
                                'title' => function(MailboxReceiver $model) {
                                    return Html::a($model->mailbox->senderName, ['/mailbox/default/view-receiver', 'id' => $model->id], ['data-pjax' => 0]);
                                },
                                'options' => ['style' => 'width:350px'],
                                'buttonsTemplate' => '{view} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(Yii::t('yii', 'View'),
                                            Url::to(['view-receiver', 'id' => $model->id]), [
                                                'title' => Yii::t('yii', 'View'),
                                                'data-pjax' => '0'
                                            ]
                                        );
                                    }
                                ],
                            ],
                            [
                                'attribute' => 'mailboxTitle',
                                'value' => 'mailbox.title',
                                'label' => Yii::t('art', 'Title'),
                            ],
                            [
                                'attribute' => 'mailboxContent',
                                'value' => 'mailbox.shortContent',
                                'label' => Yii::t('art', 'Content'),
                                'format' => 'html',
                            ],
//                            [
//                                'attribute' => 'receiver_id',
//                                'value' => 'receiver.username',
//                                'label' => Yii::t('art/mailbox', 'Receiver'),
//                                'filter' => artsoft\models\User::getUsersList(),
//                            ],
                            [
                                'class' => 'artsoft\grid\columns\StatusColumn',
                                'attribute' => 'status',
                                'optionsArray' => Mailbox::getStatusOptionsList(),
                                'options' => ['style' => 'width:60px'],
                            ],
                            [
                                'attribute' => 'mailboxPostedDate',
                                'value' => 'mailbox.postedDatetime',
                                'label' => Yii::t('art/mailbox', 'Posted At'),
                                'format' => 'raw',
                            ],
                        // 'reading_at',
                        // 'remoted_at',
                        ],
                    ]);
                    ?>

                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>



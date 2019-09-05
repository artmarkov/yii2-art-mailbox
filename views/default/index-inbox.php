<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use artsoft\grid\GridView;
use artsoft\grid\GridQuickLinks;
use artsoft\mailbox\models\Mailbox;
use artsoft\mailbox\models\MailboxInbox;
use artsoft\helpers\Html;
use artsoft\grid\GridPageSize;
use yii\timeago\TimeAgo;

/* @var $this yii\web\View */
/* @var $searchModel artsoft\mailbox\models\search\MailboxInboxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('art/mailbox', 'Inbox');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mailbox-inbox-index">

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
                <h3 class="box-title"><?= Yii::t('art/mailbox', 'Folders'); ?></h3>
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
                            <?= GridPageSize::widget(['pjaxId' => 'mailbox-inbox-grid-pjax']) ?>
                        </div>
                    </div>

                  
                    <?php
                  Pjax::begin([
                      'id' => 'mailbox-inbox-grid-pjax',
                  ])
                  ?>
                    <?= GridView::widget([
                        'id' => 'mailbox-inbox-grid',
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'bulkActionOptions' => [
                            'gridId' => 'mailbox-inbox-grid',
                            'actions' => [
                                Url::to(['bulk-mark-read']) => Yii::t('art/mailbox', 'Mark Read'),                       
                                Url::to(['bulk-mark-unread']) => Yii::t('art/mailbox', 'Mark Unread'),        
                                Url::to(['bulk-trash']) => Yii::t('art/mailbox', 'Move to Trash'),                   
                            ] //Configure here you bulk actions
                        ],
                        'columns' => [
                            ['class' => 'artsoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                            [
                                'attribute' => 'mailboxSenderId',
                                'label' => Yii::t('art/mailbox', 'Sender'),
                                'filter' => artsoft\models\User::getUsersList(),
                                'class' => 'artsoft\grid\columns\TitleActionColumn',
                                'controller' => '/mailbox/default',
                                'title' => function(MailboxInbox $model) {
                                    return Html::a($model->mailbox->senderName, ['/mailbox/default/view-inbox', 'id' => $model->id], ['data-pjax' => 0]);
                                },
                                'options' => ['style' => 'width:350px'],
                                'buttonsTemplate' => '{view} {trash}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::a(Yii::t('yii', 'View'), ['/mailbox/default/view-inbox', 'id' => $model->id], [
                                                    'title' => Yii::t('yii', 'View'),
                                                    'data-pjax' => '0'
                                                        ]
                                        );
                                    },
                                    'trash' => function ($url, $model, $key) {
                                        return Html::a(Yii::t('art/mailbox', 'Move to Trash'), ['/mailbox/default/trash', 'id' => $model->id], [
                                                    'title' => Yii::t('art/mailbox', 'Move to Trash'),
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
                                'attribute' => 'status_read',
                                'optionsArray' => Mailbox::getStatusOptionsList(),
                                'options' => ['style' => 'width:60px'],
                            ],
                            [
                                'value' => function($model) {                                
                                        return $model->mailbox->clip;
                                    },
                                'format' => 'html',
                            ],
//                            [
//                                'attribute' => 'mailboxPostedDate',
//                                'value' => 'mailbox.postedDatetime',
//                                'label' => Yii::t('art/mailbox', 'Posted At'),
//                                'format' => 'raw',
//                            ],
                            [
                            'attribute' => 'mailboxPostedDate',
                            'value' => function($model) {
                                    return $model->mailbox->postedDatetime . ' (' . TimeAgo::widget(
                                            [
                                                'timestamp' => $model->mailbox->posted_at, 
                                                'language' => Yii::$app->art->getDisplayLanguageShortcode(Yii::$app->language)
                                            ]) . ')';
                                    },
                            'label' => Yii::t('art/mailbox', 'Posted At'),
                            'format' => 'raw',
                        ],
                        // 'reading_at',
                        // 'deleted_at',
                        ],
                    ]);
                    ?>

                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>



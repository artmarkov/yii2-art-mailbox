<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use artsoft\grid\GridView;
use artsoft\grid\GridQuickLinks;
use artsoft\mailbox\models\Mailbox;
use artsoft\helpers\Html;
use artsoft\grid\GridPageSize;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel artsoft\mailbox\models\search\MailboxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('art/mailbox', 'Trash');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mailbox-trash-index">

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
            <?= Html::a('<i class="fa fa-recycle" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Clear all trash'), ['/mailbox/default/clian'], ['class' => 'btn btn-danger btn-block margin-bottom',
                                'data' => [
                                    'confirm' => Yii::t('art/mailbox', 'Are you sure you want to clear all trash?'),
                                    'method' => 'post',
                                ],])
                            ?>
        </div>
	<div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <?= Html::a('<i class="fa fa-recycle" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Clear trash'), ['/mailbox/default/clian-own'], [
                                'class' => 'btn btn-sm btn-danger margin-bottom',
                                'data' => [
                                    'confirm' => Yii::t('art/mailbox', 'Are you sure you want to clear own trash?'),
                                    'method' => 'post',
                                ],])
                            ?>
                            <?php
                            /* Uncomment this to activate GridQuickLinks */
                            /*  echo GridQuickLinks::widget([
                              'model' => Mailbox::className(),
                              'searchModel' => $searchModel,
                              ]) */
                            ?>
                        </div>

                        <div class="col-sm-6 text-right">
                            <?=  GridPageSize::widget(['pjaxId' => 'mailbox-trash-grid-pjax']) ?>
                        </div>
                    </div>
            <?php 
            Pjax::begin([
                'id' => 'mailbox-trash-grid-pjax',
            ])
            ?>

            <?= 
            GridView::widget([
                'id' => 'mailbox-trash-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'bulkActionOptions' => [
                    'gridId' => 'mailbox-trash-grid',
                    'confirmationText' => Yii::t('art/mailbox', 'Are you sure you want to delete this mail?'),
                    'actions' => [          
                       Url::to(['bulk-restore']) => Yii::t('art/mailbox', 'Restore'),  
                       Url::to(['bulk-delete']) => Yii::t('yii', 'Delete'),  
                    ] //Configure here you bulk actions
                ],
                'columns' => [
                    ['class' => 'artsoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                    [
                        'attribute' => 'sender_id',
                        'filter' => artsoft\models\User::getUsersList(),
                        'label' => Yii::t('art/mailbox', 'Sender'),
                        'class' => 'artsoft\grid\columns\TitleActionColumn',
                        'controller' => '/mailbox/default',
                        'title' => function (Mailbox  $model) {
                                        return $model->senderName;
                                    },
                        'options' => ['style' => 'width:350px'],
                        'format' => 'raw',
                        'buttonsTemplate' => '{restore} {delete}',
                        'buttons' => [
                            'restore' => function ($url, $model, $key) {
                                return Html::a(Yii::t('art/mailbox', 'Restore'), Url::to(['restore', 'id' => $model->id]), [
                                            'title' => Yii::t('art/mailbox', 'Restore'),
                                            'data-pjax' => '0'
                                                ]
                                );
                            }
                        ],
                    ],
                                
                    'title',           
                    [
                        'attribute' => 'content',
                        'value' => 'shortContent',
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'posted_at',
                        'value' => 'postedDatetime',
                        'format' => 'raw',
                    ],
            // 'created_at',
            // 'updated_at',
            // 'posted_at',
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

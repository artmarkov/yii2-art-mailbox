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

$this->title = Yii::t('art/mailbox', 'Inbox');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mailbox-sent-index">

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
                            <?=  GridPageSize::widget(['pjaxId' => 'mailbox-grid-pjax']) ?>
                        </div>
                    </div>
            <?php 
            Pjax::begin([
                'id' => 'mailbox-grid-pjax',
            ])
            ?>

            <?= 
            GridView::widget([
                'id' => 'mailbox-grid',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'bulkActionOptions' => [
                    'gridId' => 'mailbox-grid',
                    'actions' => [ 
                        Url::to(['bulk-draft']) => Yii::t('art/mailbox', 'Move to Draft'),                   
                        Url::to(['bulk-trush']) => Yii::t('art/mailbox', 'Move to Trash'), 
                        
                        ] //Configure here you bulk actions
                ],
                'columns' => [
                    ['class' => 'artsoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                    [
                        'attribute' => 'gridReceiverSearch',
                        'filter' => artsoft\models\User::getUsersList(),
                        'class' => 'artsoft\grid\columns\TitleActionColumn',
                        'controller' => '/mailbox/default',
                        'title' => function (Mailbox  $model) {
                            return implode(', ',
                                ArrayHelper::map($model->receivers, 'id', 'username'));
                        },
                        'options' => ['style' => 'width:350px'],
                        'format' => 'raw',
                        'buttonsTemplate' => '{view} {delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a(Yii::t('yii', 'View'),
                                    Url::to(['view-inbox', 'id' => $model->id]), [
                                        'title' => Yii::t('yii', 'View'),
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
                        'label' => Yii::t('art/mailbox', 'Posted At'),
                        'format' => 'raw',
                    ],
//                    [
//                        'attribute' => 'sender_id',
//                        'label' => Yii::t('art/mailbox', 'Sender'),
//                        'value' => function (Mailbox  $model) {
//                                        return $model->senderName;
//                                    },
//                        'filter' =>  artsoft\models\User::getUsersList(),
//                    ],
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
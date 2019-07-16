<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use artsoft\grid\GridView;
use artsoft\grid\GridQuickLinks;
use artsoft\mailbox\models\MailboxReceiver;
use artsoft\helpers\Html;
use artsoft\grid\GridPageSize;

/* @var $this yii\web\View */
/* @var $searchModel artsoft\mailbox\models\search\MailboxReceiverSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('art/mailbox', 'Mailbox Receivers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailbox-receiver-index">

    <div class="row">
        <div class="col-sm-12">
            <h3 class="lte-hide-title page-title"><?=  Html::encode($this->title) ?></h3>
         
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="row">
                <div class="col-sm-6">
                    <?php 
                    /* Uncomment this to activate GridQuickLinks */
                    /* echo GridQuickLinks::widget([
                        'model' => MailboxReceiver::className(),
                        'searchModel' => $searchModel,
                    ])*/
                    ?>
                </div>

                <div class="col-sm-6 text-right">
                    <?=  GridPageSize::widget(['pjaxId' => 'mailbox-receiver-grid-pjax']) ?>
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
                    'actions' => [ Url::to(['bulk-delete']) => 'Delete'] //Configure here you bulk actions
                ],
                'columns' => [
                    ['class' => 'artsoft\grid\CheckboxColumn', 'options' => ['style' => 'width:10px']],
                    [
                        'attribute' => 'id',
                        'class' => 'artsoft\grid\columns\TitleActionColumn',
                        'controller' => '/mailbox-receiver/default',
                        'title' => function(MailboxReceiver $model) {
                            return Html::a($model->id, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                        },
                        'buttonsTemplate' => '{view} {delete}',
                    ],

            'id',
            'mailbox_id',
            'receiver_id',
            'read_flag',
            'remote_flag',
            // 'created_at',
            // 'reading_at',
            // 'remoted_at',

                ],
            ]);
            ?>

            <?php Pjax::end() ?>
        </div>
    </div>
</div>



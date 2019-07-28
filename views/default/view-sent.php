<?php

use artsoft\helpers\Html;
use artsoft\mailbox\models\Mailbox;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\Mailbox */

$this->title = Yii::t('art/mailbox', 'Read mail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailboxes'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mailbox-sent-view">
   
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
                <div class="box-header with-border">

                    <h3 class="box-title"><?= Html::encode($this->title) ?></h3>

                    <div class="box-tools pull-right">                        
                        <ul class="pager">
                            <li><?= Html::a('<i class="fa fa-chevron-left"></i>', ['/mailbox/default/view-sent', 'id' => Mailbox::getPreviousMail($model->id)], ['class' => 'previous', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Previous']) ?></li>
                            <li><?= Html::a('<i class="fa fa-chevron-right"></i>', ['/mailbox/default/view-sent', 'id' => Mailbox::getNextMail($model->id)], ['class' => 'next', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Next']) ?></li>
                        </ul>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <div class="mailbox-read-info">
                    <h3><?= $model->title; ?></h3>
                    <h5><?= Yii::t('art/mailbox', 'From:') . ' ' . $model->senderName; ?>
                        <span class="mailbox-read-time pull-right"><?= $model->postedDatetime; ?></span></h5>
                </div>   
                <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                        
                        <?= Html::a('<i class="fa fa-trash-o"></i>', ['/mailbox/default/trash-sent', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => Yii::t('art/mailbox', 'Move to Trash')]) ?>
                        <?= Html::a('<i class="fa fa-reply"></i>', ['/mailbox/default/reply', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Reply']) ?>
                        <?= Html::a('<i class="fa fa-share"></i>', ['/mailbox/default/forward', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Forward']) ?>

                    </div>
                    <?= Html::a('<i class="fa fa-print"></i>', ['/mailbox/default/print', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Print']) ?>                         

                </div>
                           

                <div class="panel-body">
                    <div class="mailbox-read-message">

                        <?= $model->content; ?>

                    </div>
                </div>
                </div>

                <div class="box-footer">
                    
                    <div class="pull-right">

                        <?= Html::a('<i class="fa fa-reply" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Reply'), ['/mailbox/default/reply', 'id' => $model->id], ['class' => 'btn btn-default']) ?>          
                        <?= Html::a('<i class="fa fa-share" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Forward'), ['/mailbox/default/forward', 'id' => $model->id], ['class' => 'btn btn-default']) ?>

                    </div>                                
                    
                        <?= Html::a('<i class="fa fa-trash-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Move to Trash'), ['/mailbox/default/trash-sent', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
                        <?= Html::a('<i class="fa fa-print" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Print'), ['/mailbox/default/print', 'id' => $model->id], ['class' => 'btn btn-default']) ?>                           

                 
                </div>

            </div>
        </div>
    </div>
</div>
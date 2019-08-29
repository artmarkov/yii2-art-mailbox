<?php

use artsoft\helpers\Html;
use artsoft\mailbox\models\MailboxInbox;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\MailboxInbox */

$this->title = Yii::t('art/mailbox', 'Read mail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailboxs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mailbox-inbox-view">

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
                        <?= artsoft\mailbox\widgets\PagerSelector::widget([
                            'prev_id' => MailboxInbox::getPrevMail($model->id),
                            'next_id' => MailboxInbox::getNextMail($model->id),
                            'path' => '/mailbox/default/view-inbox',
                        ]);
                        ?>
                    </div>
                </div>
                <div  class="box-body no-padding">
                    <div id = "print_info" class="mailbox-read-info">
                        <h3><?= $model->mailboxTitle; ?></h3>
                        <h5><?= Yii::t('art/mailbox', 'From:') . ' ' . $model->receiver->username; ?>
                            <span class="mailbox-read-time pull-right"><?= $model->mailbox->postedDateTime; ?></span></h5>
                    </div>   
                    <div class="mailbox-controls with-border text-center">
                        <div class="btn-group">

                            <?= Html::a('<i class="fa fa-trash-o"></i>', ['/mailbox/default/trash-inbox', 'id' => $model->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => Yii::t('art/mailbox', 'Move to Trash')]) ?>
                            <?= Html::a('<i class="fa fa-reply"></i>', ['/mailbox/default/reply', 'id' => $model->mailbox->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Reply']) ?>
                            <?= Html::a('<i class="fa fa-share"></i>', ['/mailbox/default/forward', 'id' => $model->mailbox->id], ['class' => 'btn btn-default btn-sm', 'data-toggle' => 'tooltip', 'data-container' => 'body', 'title' => '', 'data-original-title' => 'Forward']) ?>

                        </div>                    
                    </div>


                    <div id = "print_body" class="panel-body">
                        <div  class="mailbox-read-message">

                            <?= $model->mailbox->content; ?>

                        </div>
                    </div>

                    <div class="panel-body">
                        <?= artsoft\fileinput\widgets\FileInput::widget([
                            'model' => $model->mailbox,
                            'pluginOptions' => [
                                'showCaption' => false,
                                'showBrowse' => false,
                                'dropZoneEnabled' => false,
                                'fileActionSettings' => [
                                    'showDrag' => false,
                                    'showRemove' => false,
                                ],
                            ],
                        ]);
                        ?>

                    </div> 
                </div>
                <div class="box-footer">
                    
                    <div class="pull-right">

                        <?= Html::a('<i class="fa fa-reply" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Reply'), ['/mailbox/default/reply', 'id' => $model->mailbox->id], ['class' => 'btn btn-default']) ?>          
                        <?= Html::a('<i class="fa fa-share" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Forward'), ['/mailbox/default/forward', 'id' => $model->mailbox->id], ['class' => 'btn btn-default']) ?>

                    </div> 
                    
                        <?= Html::a('<i class="fa fa-trash-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Move to Trash'), ['/mailbox/default/trash-inbox', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
                        <?= artsoft\printthis\PrintThis::widget([
                            'htmlOptions' => [
                                'id' => ['print_info', 'print_body'],
                                'btnOptions' => [
                                    'class' => 'btn btn-default btn-sm',
                                ],
                                'btnText' => Yii::t('art/mailbox', 'Print'),
                            ]
                        ]);
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>

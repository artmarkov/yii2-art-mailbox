<?php

use yii\widgets\DetailView;
use artsoft\helpers\Html;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\Mailbox */

$this->title = Yii::t('art/mailbox', 'Read mail');
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailboxes'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailbox-view">

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
                <div class="panel-heading">

                    <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>

                    <div class="form-group">
                        <div class="pull-right">

                            <?= Html::a('<i class="fa fa-chevron-left" style="margin-right: 5px;"></i>' . Yii::t('art', 'Previous'), ['/mailbox/default/previous'], ['class' => 'btn btn-default']) ?>          
                            <?= Html::a('<i class="fa fa-chevron-right" style="margin-right: 5px;"></i>' . Yii::t('art', 'Next'), ['/mailbox/default/next'], ['class' => 'btn btn-default']) ?>

                        </div>
                    </div>
                </div>
                <div class="mailbox-controls with-border text-center">
                    <div class="btn-group">
                        <?=
                        Html::a('<i class="fa fa-trash-o"></i>', ['/mailbox/default/delete', 'id' => $model->id], [
                            'class' => 'btn btn-default btn-sm',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ])
                        ?>
                        <?= Html::a('<i class="fa fa-reply"></i>', ['/mailbox/default/reply'], ['class' => 'btn btn-default btn-sm']) ?>
                        <?= Html::a('<i class="fa fa-share"></i>', ['/mailbox/default/forward'], ['class' => 'btn btn-default btn-sm']) ?>

                    </div>
                    <?= Html::a('<i class="fa fa-print"></i>', ['/mailbox/default/print'], ['class' => 'btn btn-default btn-sm']) ?>                           

                </div>
                <div class="">
                    <h3><?= $model->title; ?></h3>
                    <h5><?= Yii::t('art/mailbox', 'From:') . ' ' . $model->senderName; ?>
                        <span class="mailbox-read-time pull-right"><?= $model->postedDatetime; ?></span></h5>
                </div>              

                <div class="panel-body">
                    <div class="mailbox-read-message">

                        <?= $model->content; ?>

                    </div>
                </div>

                <div class="panel-footer">
                    <div class="form-group">
                        <div class="pull-right">

                            <?= Html::a('<i class="fa fa-reply" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Reply'), ['/mailbox/default/reply'], ['class' => 'btn btn-default']) ?>          
                            <?= Html::a('<i class="fa fa-share" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Forward'), ['/mailbox/default/forward'], ['class' => 'btn btn-default']) ?>


                        </div>                                

                        <?=
                        Html::a('<i class="fa fa-trash-o" style="margin-right: 5px;"></i>' . Yii::t('art', 'Delete'), ['/mailbox/default/delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ])
                        ?>
                        <?= Html::a('<i class="fa fa-print" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Print'), ['/mailbox/default/print'], ['class' => 'btn btn-default']) ?>                           

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

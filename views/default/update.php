<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\Mailbox */

$this->title = Yii::t('art', 'Update "{item}"', ['item' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailboxes'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['default/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

artsoft\mailbox\MailboxAssetsBundle::register($this);
?>
<div class="mailbox-update">
   <div class="row">
        <div class="col-sm-12">
            <h3 class="page-title"><?=  Html::encode($this->title) ?></h3>            
        </div>
   </div>
   <div class="row">
        <div class="col-md-3">
            <?= Html::a(Yii::t('art/mailbox', 'Compose'), ['/mailbox/default/create'], ['class' => 'btn btn-primary btn-block margin-bottom']) ?>
         
            <div class="panel panel-default">
            <div class="box-header with-border">
                <h3 class="box-title">Folders</h3>
            </div>
                
                <div class="box-body no-padding">                   

                    <?= $this->render('../_menu', compact('model')) ?>

                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?= $this->render('_form', compact('model')) ?>
                </div>
            </div>
        </div>
    </div>
</div>
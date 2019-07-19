<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\Mailbox */

$this->title = Yii::t('art', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('art/mailbox', 'Mailboxes'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mailbox-create">
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
                    <?= $this->render('_form', compact('model')) ?>
                </div>
            </div>
        </div>
    </div>
</div>
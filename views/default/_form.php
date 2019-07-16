<?php

use artsoft\widgets\ActiveForm;
use artsoft\mailbox\models\Mailbox;
use artsoft\models\User;
use artsoft\helpers\Html;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\Mailbox */
/* @var $form artsoft\widgets\ActiveForm */
?>

<div class="mailbox-form">

    <?php 
    $form = ActiveForm::begin([
            'id' => 'mailbox-form',
            'validateOnBlur' => false,
        ])
    ?>

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-body">

                     <?php
                    echo $form->field($model, 'receivers_ids')->widget(\nex\chosen\Chosen::className(), [
                        'items' => User::getUsersList(),
                        'multiple' => true,
                        'placeholder' => Yii::t('art/mailbox', 'Select Receivers...'),
                    ])->label(Yii::t('art/mailbox', 'Receivers'));
                    ?>
                    
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'draft_flag')->textInput() ?>

                    <?= $form->field($model, 'remote_flag')->textInput() ?>

                   

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="record-info">
                        <div class="form-group clearfix">
                            <label class="control-label" style="float: left; padding-right: 5px;"><?=  $model->attributeLabels()['id'] ?>: </label>
                            <span><?=  $model->id ?></span>
                        </div>
                        <?php  if (!$model->isNewRecord): ?>
                        <div class="form-group clearfix">
                            <label class="control-label" style="float: left; padding-right: 5px;"><?=  $model->attributeLabels()['created_at'] ?>: </label>
                            <span><?=  $model->createdDatetime ?></span>
                        </div>
                        <div class="form-group clearfix">
                            <label class="control-label" style="float: left; padding-right: 5px;"><?=  $model->attributeLabels()['updated_at'] ?>: </label>
                            <span><?=  $model->updatedDatetime ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <?php  if ($model->isNewRecord): ?>
                                <?= Html::submitButton(Yii::t('art', 'Create'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('art', 'Cancel'), ['/mailbox/default/index'], ['class' => 'btn btn-default']) ?>
                            <?php  else: ?>
                                <?= Html::submitButton(Yii::t('art', 'Save'), ['class' => 'btn btn-primary']) ?>
                                <?= Html::a(Yii::t('art', 'Delete'),
                                    ['/mailbox/default/delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    
                    <!-- other form-->
                    
                </div>
            </div>
        </div>
    </div>

    <?php  ActiveForm::end(); ?>

</div>

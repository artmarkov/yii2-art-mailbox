<?php

use artsoft\widgets\ActiveForm;
use artsoft\mailbox\models\Mailbox;
use artsoft\media\widgets\TinyMce;
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

  
                     <?php
                    echo $form->field($model, 'receivers_ids')->widget(\nex\chosen\Chosen::className(), [
                        'items' => User::getUsersList(),
                        'multiple' => true,
                        'placeholder' => Yii::t('art/mailbox', 'Select Receivers...'),
                    ])->label(Yii::t('art/mailbox', 'Receivers'));
                    ?>
                    
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'content')->widget(TinyMce::className()); ?>

                    <?= $form->field($model, 'folder')->textInput() ?>
                   

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
                        <div class="panel-footer">
                            <div class="form-group">
                                <div class="pull-right">
                                <?= Html::a(Yii::t('art', 'Draft'), ['/mailbox/default/draft'], ['class' => 'btn btn-default']) ?>          
                                <?= Html::submitButton(Yii::t('art', 'Send'), ['class' => 'btn btn-primary']) ?>
               
                                </div>
                                <?= Html::a(Yii::t('art', 'Discard'), ['/mailbox/default/index'], ['class' => 'btn btn-default']) ?>                           
                                <?php if (!$model->isNewRecord): ?>                           
                                    <?=
                                    Html::a(Yii::t('art', 'Delete'), ['/mailbox/default/delete', 'id' => $model->id], [
                                        'class' => 'btn btn-danger',
                                        'data' => [
                                            'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'method' => 'post',
                                        ],
                                    ])
                                    ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                
           

    <?php  ActiveForm::end(); ?>

</div>

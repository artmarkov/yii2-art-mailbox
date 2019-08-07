<?php

use artsoft\widgets\ActiveForm;
use artsoft\mailbox\models\Mailbox;
use artsoft\media\widgets\TinyMce;
use artsoft\models\User;
use artsoft\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model artsoft\mailbox\models\Mailbox */
/* @var $form artsoft\widgets\ActiveForm */
?>

<div class="mailbox-form">

    <?php 
    $form = ActiveForm::begin([
            'id' => 'mailbox-form',
            'validateOnBlur' => false,
            'options' => ['enctype'=>'multipart/form-data'],
            'enableClientScript' => true, // default
            ])
    ?>

  
                     <?php
                    echo $form->field($model, 'receivers_ids')->widget(\nex\chosen\Chosen::className(), [
                        'items' => User::getUsersList(),
                        'multiple' => true,
                        'placeholder' => Yii::t('art/mailbox', 'To:'),
                    ])->label(Yii::t('art/mailbox', 'Receivers'));
                    ?>
                    
                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'content')->widget(TinyMce::className()); ?>
           
                    <div class="panel-body">
                        <div class="row">

                               <!--<?//php echo '<pre>' . print_r($model->filesLinksData, true) . '</pre>'; ?>-->
                                <?= \kartik\file\FileInput::widget([
                                        'name' => 'attachment[]',
                                        'options'=>[
                                            'multiple'=>true
                                        ],
                                        'pluginOptions' => [
                                            'deleteUrl' => Url::toRoute(['/mailbox/file-manager/delete-file']),
                                            'initialPreview'=> $model->filesLinks,
                                            'initialPreviewAsData'=>true,
                                            'initialPreviewFileType' => 'image', 
                                            'overwriteInitial'=>false,
                                            'initialPreviewConfig'=>$model->filesLinksData,
                                            'maxFileSize' => 1500, // Kb
                                            'allowedFileExtensions' => ["jpg", "png", "mp4", "pdf"],
                                            'uploadUrl' => Url::to(['/mailbox/file-manager/file-upload']),
                                            'uploadExtraData' => [
                                                'FileManager[class]' => $model->formName(),
                                                'FileManager[item_id]' => $model->id
                                            ],
                                            'maxFileCount' => 10,
                                        ],
                                        'pluginEvents' => [
                                            'filesorted' => new \yii\web\JsExpression('function(event, params){
                                                  $.post("'.Url::toRoute(["/mailbox/file-manager/sort-file", "id" => $model->id]).'", {sort: params});
                                            }')
                                        ],
                                  ]);                    
                              ?>

                        </div>
                    </div> 
      
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
                               
                                <?= Html::submitButton(Yii::t('art/mailbox', 'Draft'), ['class' => 'btn btn-default', 'name' => 'status_post', 'value' => $model::STATUS_POST_DRAFT, 'data-pjax' => 0]) ?>          
                                <?= Html::submitButton(Yii::t('art/mailbox', 'Send'), ['class' => 'btn btn-primary', 'name' => 'status_post', 'value' => $model::STATUS_POST_SENT, 'data-pjax' => 0]) ?>          
               
                                </div>
                                <?= Html::a(Yii::t('art/mailbox', 'Discard'), ['/mailbox/default/index'], ['class' => 'btn btn-default']) ?>                           
                                <?php if (!$model->isNewRecord): ?>                           
                                <?= Html::a(Yii::t('art/mailbox', 'Trash'), ['/mailbox/default/delete', 'id' => $model->id], [
                                        'class' => 'btn btn-default', 
                                        'data-pjax' => 0,
                                        'data' => [
                                            'confirm' => Yii::t('art/mailbox', 'Are you sure you want to trash this mail?'),
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

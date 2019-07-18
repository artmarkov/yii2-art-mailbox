<?php

use artsoft\widgets\Nav;
?>

<div class="mailbox-nav">
    <?php
    $menuItems = [
        [
            'encode' => false,
            'label' => '<i class="fa fa-inbox" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Inbox') . '<span class="label label-success pull-right">12</span>',
            'url' => ['receiver/index'],
            'active' => true
        ],
        [
            'encode' => false,
            'label' => '<i class="fa fa-envelope-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Sent'),
            'url' => ['default/index']
        ],
        [
            'encode' => false,
            'label' => '<i class="fa fa-file-text-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Draft'),
            'url' => ['draft']
        ],
        [
            'encode' => false,
            'label' => '<i class="fa fa-trash-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Trash'),
            'url' => ['trash']
        ],
    ];
    echo Nav::widget([
        'id' => 'mailbox',
        'options' => ['class' => 'nav nav-pills nav-stacked'],
        'items' => $menuItems,
    ]);
    ?>

</div>

<?php
$css = <<<CSS

#mailbox .mm-active {
    background-color: #eee;
}
        
CSS;

$this->registerCss($css);



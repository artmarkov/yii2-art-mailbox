<?php

use artsoft\widgets\Nav;

artsoft\mailbox\MailboxAssetsBundle::register($this);
?>

<div class="mailbox-nav">
    <?php
    $menuItems = [
        [
            'encode' => false,
            'label' => '<i class="fa fa-inbox" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Inbox') . '<span class="label label-success pull-right">12</span>',
            'url' => ['default/index'],
            'active' => true
        ],
        [
            'encode' => false,
            'label' => '<i class="fa fa-envelope-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Sent'),
            'url' => ['default/index-sent']
        ],
        [
            'encode' => false,
            'label' => '<i class="fa fa-file-text-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Drafts'),
            'url' => ['default/index-draft']
        ],
        [
            'encode' => false,
            'label' => '<i class="fa fa-trash-o" style="margin-right: 5px;"></i>' . Yii::t('art/mailbox', 'Trash'),
            'url' => ['default/index-trash']
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


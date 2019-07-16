<?php

namespace artsoft\mailbox\controllers;

use Yii;
use artsoft\controllers\admin\BaseController;

/**
 * ReceiverController implements the CRUD actions for artsoft\mailbox\models\MailboxReceiver model.
 */
class ReceiverController extends BaseController 
{
    public $modelClass       = 'artsoft\mailbox\models\MailboxReceiver';
    public $modelSearchClass = 'artsoft\mailbox\models\search\MailboxReceiverSearch';

    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
            case 'update':
                return ['update', 'id' => $model->id];
                break;
            case 'create':
                return ['update', 'id' => $model->id];
                break;
            default:
                return parent::getRedirectPage($action, $model);
        }
    }
}
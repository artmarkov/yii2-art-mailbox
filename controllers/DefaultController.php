<?php

namespace artsoft\mailbox\controllers;

use Yii;
use artsoft\controllers\admin\BaseController;

/**
 * Controller implements the CRUD actions for Block model.
 */
class DefaultController extends BaseController
{
    public $modelClass = 'artsoft\mailbox\models\Mailbox';
    public $modelSearchClass = 'artsoft\mailbox\models\search\MailboxSearch';

    protected function getRedirectPage($action, $model = null)
    {
        switch ($action) {
            case 'update':
                return ['update', 'id' => $model->id];
                break;
            case 'compose':
                return ['index', 'id' => $model->id];
                break;
            default:
                return parent::getRedirectPage($action, $model);
        }
    }
     /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCompose()
    {
        /* @var $model \artsoft\db\ActiveRecord */
        $model = new $this->modelClass;
        
        if (isset(Yii::$app->request->post('draft'))) {

        }
        else {

        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art', 'Your item has been created.'));
            return $this->redirect($this->getRedirectPage('compose', $model));
        }

        return $this->renderIsAjax('compose', compact('model'));
    }
     
}
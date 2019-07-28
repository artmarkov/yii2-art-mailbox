<?php

namespace artsoft\mailbox\controllers;

use Yii;
use artsoft\controllers\admin\BaseController;
use yii\web\NotFoundHttpException;
use artsoft\helpers\ArtHelper;
use artsoft\models\OwnerAccess;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;

/**
 * Controller implements the CRUD actions for Block model.
 */
class DefaultController extends BaseController {

    public $modelClass = 'artsoft\mailbox\models\Mailbox';
    public $modelSearchClass = 'artsoft\mailbox\models\search\MailboxSearch';
    
    public $modelViaClass       = 'artsoft\mailbox\models\MailboxInbox';
    public $modelViaSearchClass = 'artsoft\mailbox\models\search\MailboxInboxSearch';
    
    public $enableOnlyActions = ['index', 'index-sent', 'index-draft', 'index-trash', 'view-inbox', 'view-sent', 'compose', 'update', 'delete','reply', 'forward',  
                                 'trash', 'trash-sent', 'restore', 'bulk-mark-read', 'bulk-mark-unread', 'bulk-trash', 'bulk-trush-sent', 'bulk-delete', 'bulk-restore'];


    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new $this->modelViaSearchClass;
        $searchName = StringHelper::basename($searchModel::className());
        
        $params = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
                    $searchName => [
                        'receiver_id' => Yii::$app->user->identity->id,
                        'mailboxStatusPost' => $this->modelClass::STATUS_POST_SENT,
                        'status_del' => $this->modelClass::STATUS_DEL_NO,
                    ]
        ]);
        $dataProvider = $searchModel->search($params);
        return $this->renderIsAjax('index-inbox', compact('dataProvider', 'searchModel'));
    }
    
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndexSent()
    {
        $searchModel = new $this->modelSearchClass;
        $searchName = StringHelper::basename($searchModel::className());
        
        $params = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
                    $searchName => [
                        'sender_id' => Yii::$app->user->identity->id,
                        'status_post' => $this->modelClass::STATUS_POST_SENT,
                        'status_del' => $this->modelClass::STATUS_DEL_NO,
                    ]
        ]);
        $dataProvider = $searchModel->search($params);
        return $this->renderIsAjax('index-sent', compact('dataProvider', 'searchModel'));
    }

    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndexDraft()
    {
        $searchModel = new $this->modelSearchClass;
        $searchName = StringHelper::basename($searchModel::className());
        
        $params = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
                    $searchName => [
                        'sender_id' => Yii::$app->user->identity->id,
                        'status_post' => $this->modelClass::STATUS_POST_DRAFT,
                        'status_del' => $this->modelClass::STATUS_DEL_NO,
                    ]
        ]);
        $dataProvider = $searchModel->search($params);
        return $this->renderIsAjax('index-draft', compact('dataProvider', 'searchModel'));
    }
    /**
     * Lists all models.
     * @return mixed
     */
     public function actionIndexTrash()
    {
        $searchModel = new $this->modelSearchClass;
        $searchName = StringHelper::basename($searchModel::className());
        
        $params = ArrayHelper::merge(Yii::$app->request->getQueryParams(), [
                    $searchName => [
                        'statusDelTrash' => $this->modelClass::STATUS_DEL_TRASH,
                    ]
        ]);
        $dataProvider = $searchModel->search($params);
        return $this->renderIsAjax('index-trash', compact('dataProvider', 'searchModel'));
    }
    /**
     * Displays a single model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewInbox($id)
    {
        $model = $this->modelViaClass::findOne($id);
        
        $model->status_read = $this->modelClass::STATUS_READ_OLD;
        $model->save();
        
        return $this->renderIsAjax('view-inbox', [
            'model' => $model,
        ]);
    } 
    /**
     * Displays a single model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewSent($id)
    {
        $model = $this->modelClass::findOne($id);
        
        return $this->renderIsAjax('view-sent', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCompose()
    {
        /* @var $model \artsoft\db\ActiveRecord */
        $model = new $this->modelClass;

        if ($model->load(Yii::$app->request->post()))
        {
            $status_post = Yii::$app->request->post('status_post');
            if (empty($status_post))
            {
                throw new NotFoundHttpException('Required status_post parameter is missing.');
            }
            $model->getComposeData($status_post);

            if ($model->save())
            {
                Yii::$app->session->setFlash('crudMessage', $model::getMessage($status_post));
                return $this->redirect($this->getRedirectPage('index', $model));
            }
        }
        return $this->renderIsAjax('compose', compact('model'));
    }

    /**
     * 
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->status_post != $this->modelClass::STATUS_POST_DRAFT)
        {
            throw new NotFoundHttpException('Editing is only allowed for the drafts folder.');
        }

        if ($model->load(Yii::$app->request->post()))
        {
            $status_post = Yii::$app->request->post('status_post');
            if (empty($status_post))
            {
                throw new NotFoundHttpException('Required status_post parameter is missing.');
            }
            $model->getComposeData($status_post);

            if ($model->save())
            {
                Yii::$app->session->setFlash('crudMessage', $model::getMessage($status_post));
                return $this->redirect($this->getRedirectPage('index', $model));
            }
        }
        return $this->renderIsAjax('update', compact('model'));
    }

    /**
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
     public function actionReply($id)
    {
         
        $model_reply = self::findModel($id);        
        $model = new $this->modelClass;        
        $model->getReplyData($model_reply);        

         if ($model->load(Yii::$app->request->post()))
        {
            $status_post = Yii::$app->request->post('status_post');
            if (empty($status_post))
            {
                throw new NotFoundHttpException('Required status_post parameter is missing.');
            }
            $model->getComposeData($status_post);

            if ($model->save())
            {
                Yii::$app->session->setFlash('crudMessage', $model::getMessage($status_post));
                return $this->redirect($this->getRedirectPage('index', $model));
            }
        }
        return $this->renderIsAjax('compose', compact('model'));
    }
    
    /**
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
     public function actionForward($id)
    {
         
        $model_reply = self::findModel($id);        
        $model = new $this->modelClass;        
        $model->getForwardData($model_reply);        

        if ($model->load(Yii::$app->request->post()))
        {
            $status_post = Yii::$app->request->post('status_post');
            if (empty($status_post))
            {
                throw new NotFoundHttpException('Required status_post parameter is missing.');
            }
            $model->getComposeData($status_post);

            if ($model->save())
            {
                Yii::$app->session->setFlash('crudMessage', $model::getMessage($status_post));
                return $this->redirect($this->getRedirectPage('index', $model));
            }
        }
        return $this->renderIsAjax('compose', compact('model'));
    }
    /**
     * @param type $id
     * @return type
     */
    public function actionTrash($id)
    {
        if ($this->modelViaClass::trashMail($id)) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been moved to the trash folder.'));
        } else {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Action error occurred.'));
        }
        return $this->redirect($this->getRedirectPage('index', $this->modelClass));
    }
    /**
     * @param type $id
     * @return type
     */
    public function actionTrashSent($id)
    {
         if ($this->modelClass::trashMail($id)) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been moved to the trash folder.'));
        } else {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Action error occurred.'));
        }
        return $this->redirect($this->getRedirectPage('index-sent', $this->modelClass));
    }
    /**
     * @param type $id
     * @return type
     */
    public function actionRestore($id) {
        
        if ($this->modelClass::restoryMail($id)) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been restory.'));
        } else {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Action error occurred.'));
        }

        return $this->redirect($this->getRedirectPage('index', $this->modelClass));
    }

    /**
     * @param type $id
     * @return type
     */
    public function actionDelete($id)
    {
       if ($this->modelClass::deleteMail($id)) {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been destroyed.'));
        } else {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Action error occurred.'));
        }
        
        return $this->redirect($this->getRedirectPage('index', $this->modelClass));
    }

    /**
     * Read all selected grid items
     */
    public function actionBulkMarkRead() {

        if (Yii::$app->request->post('selection')) {
            
            $where = ['id' => Yii::$app->request->post('selection', [])];
            $this->modelViaClass::updateAll(['status_read' => $this->modelClass::STATUS_READ_OLD], $where);
        }
    }
    /**
     * Unread all selected grid items
     */
    public function actionBulkMarkUnread() {

        if (Yii::$app->request->post('selection')) {
            
            $where = ['id' => Yii::$app->request->post('selection', [])];
            $this->modelViaClass::updateAll(['status_read' => $this->modelClass::STATUS_READ_NEW], $where);
        }
    }
    /**
     * Trash all selected grid items
     */
    public function actionBulkTrash() {

        if (Yii::$app->request->post('selection')) {
            
            $where = ['id' => Yii::$app->request->post('selection', [])];
            $this->modelViaClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_TRASH, 'deleted_at' => time()], $where);
        }
    }
    /**
     * Trash all selected grid items
     */
    public function actionBulkTrashSent() {

        if (Yii::$app->request->post('selection')) {
            
            $where = ['id' => Yii::$app->request->post('selection', [])];
            $this->modelClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_TRASH, 'deleted_at' => time()], $where);
        }
    }
    /**
     * Restore all selected grid items
     */
    public function actionBulkRestore() {

        if (Yii::$app->request->post('selection')) {
            
            $where = ['id' => Yii::$app->request->post('selection', []), 'sender_id' => Yii::$app->user->identity->id];
            $this->modelClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_NO, 'deleted_at' => NULL], $where); 
            
            $whereVia = ['mailbox_id' => Yii::$app->request->post('selection', []), 'receiver_id' => Yii::$app->user->identity->id];
            $this->modelViaClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_NO, 'deleted_at' => NULL], $whereVia);
        }
    } 
    /**
     * Delete all selected grid items
     */
    public function actionBulkDelete() {
        
        if (Yii::$app->request->post('selection')) {
            $where = ['id' => Yii::$app->request->post('selection', []), 'sender_id' => Yii::$app->user->identity->id, 'status_del' => $this->modelClass::STATUS_DEL_TRASH];
            $this->modelClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_DELETE, 'deleted_at' => time()], $where); 
            
            $whereVia = ['mailbox_id' => Yii::$app->request->post('selection', []), 'receiver_id' => Yii::$app->user->identity->id, 'status_del' => $this->modelClass::STATUS_DEL_TRASH];
            $this->modelViaClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_DELETE, 'deleted_at' => time()], $whereVia);
        }
    }
    /**
     * Delete all selected grid items
     */
    public function actionClian() {

        $id = $this->modelClass::getAllTrashMail();
         //  echo '<pre>' . print_r($id, true) . '</pre>';
        if (!empty($id)) {
            
            $where = ['id' => $id, 'sender_id' => Yii::$app->user->identity->id, 'status_del' => $this->modelClass::STATUS_DEL_TRASH];
            $this->modelClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_DELETE, 'deleted_at' => time()], $where); 
            
            $whereVia = ['mailbox_id' => $id, 'receiver_id' => Yii::$app->user->identity->id, 'status_del' => $this->modelClass::STATUS_DEL_TRASH];
            $this->modelViaClass::updateAll(['status_del' => $this->modelClass::STATUS_DEL_DELETE, 'deleted_at' => time()], $whereVia);
            
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your cart has been emptied.'));
        } else {
            Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your cart is empty.'));
            
        }
            
            return $this->redirect($this->getRedirectPage('index', $this->modelClass));
    }
}

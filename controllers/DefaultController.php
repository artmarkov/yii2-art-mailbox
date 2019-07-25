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
    
    public $modelViaClass       = 'artsoft\mailbox\models\MailboxReceiver';
    public $modelViaSearchClass = 'artsoft\mailbox\models\search\MailboxReceiverSearch';
    
    public $enableOnlyActions = ['index', 'indexDraft', 'indexTrash', 'update', 'compose', 'view', 'delete'];


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
                        'sender_id' => Yii::$app->user->identity->id,
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
        $model = $this->modelViaClass::findOne($id);
        
        $model->status_del = $this->modelClass::STATUS_DEL_TRASH;
        $model->deleted_at = time();
        
        $model->save();
        Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been moved to the trash folder.'));
        return $this->redirect($this->getRedirectPage('index', $model));
    }
    /**
     * @param type $id
     * @return type
     */
    public function actionTrashSent($id)
    {
        $model = $this->modelClass::findOne($id);
        
        $model->status_del = $this->modelClass::STATUS_DEL_TRASH;
        $model->deleted_at = time();
        
        $model->save();
        Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been moved to the trash folder.'));
        return $this->redirect($this->getRedirectPage('index-sent', $model));
    }
/**
     * @param type $id
     * @return type
     */
    public function actionRestore($id)
    {
        $model = $this->modelClass::findOne(['id' => $id, 'sender_id' => Yii::$app->user->identity->id]);
        
        $modelVia = $this->modelViaClass::findOne(['mailbox_id' => $id, 'receiver_id' => Yii::$app->user->identity->id]);
        if ($model)
        {
            $model->status_del = $this->modelClass::STATUS_DEL_NO;
            $model->deleted_at = NULL;
            $model->save();
        }
        if ($modelVia)
        {
            $modelVia->status_del = $this->modelClass::STATUS_DEL_NO;
            $modelVia->deleted_at = NULL;
            $modelVia->save();
        }
        Yii::$app->session->setFlash('crudMessage', Yii::t('art/mailbox', 'Your mail has been restory.'));
        return $this->redirect($this->getRedirectPage('index', $model));
    }

}

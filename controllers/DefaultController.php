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

    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'truncate' => ['post'],
                            'delete' => ['post'],
                        ],
                    ],
        ]);
    }

    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndexSent()
    {
        $modelClass = $this->modelClass;
        $searchModel = new $this->modelSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME) && !User::hasPermission($modelClass::getFullAccessPermission()));

            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess)
            {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }
            $params[$searchName]['status_post'] = $modelClass::STATUS_POST_SENT;
            $params[$searchName]['status_del'] = $modelClass::STATUS_DEL_NO;

            $dataProvider = $searchModel->search($params);        

        return $this->renderIsAjax('index-sent', compact('dataProvider', 'searchModel'));
    }
    
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelClass = $this->modelViaClass;
        $searchModel = new $this->modelViaSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME) && !User::hasPermission($modelClass::getFullAccessPermission()));

            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess)
            {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }            
            $params[$searchName]['status_del'] = $this->modelClass::STATUS_DEL_NO;
                //echo '<pre>' . print_r($params, true) . '</pre>';
            $dataProvider = $searchModel->search($params);
        
        return $this->renderIsAjax('index-receiver', compact('dataProvider', 'searchModel'));
    }
    
    /**
     * Displays a single model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionViewInbox($id)
    {
        $model = $this->modelClass::findOne($id);
        
        return $this->renderIsAjax('view-inbox', [
            'model' => $model,
        ]);
    } 
    /**
     * Displays a single model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionViewReceiver($id)
    {
        $model = $this->modelViaClass::findOne($id);
        $model->status_read = $this->modelClass::STATUS_READ_OLD;
        $model->save(false);
        
        return $this->renderIsAjax('view-receiver', [
            'model' => $model,
        ]);
    }
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndexDraft()
    {
        $modelClass = $this->modelClass;
        $searchModel = new $this->modelSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME) && !User::hasPermission($modelClass::getFullAccessPermission()));

            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess)
            {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }
            $params[$searchName]['status_post'] = $modelClass::STATUS_POST_DRAFT;

            $dataProvider = $searchModel->search($params);
        
        return $this->renderIsAjax('index-draft', compact('dataProvider', 'searchModel'));
    }

    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndexTrash()
    {
        $modelClass = $this->modelClass;
        $searchModel = new $this->modelSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME) && !User::hasPermission($modelClass::getFullAccessPermission()));

            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess)
            {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }
            $params[$searchName]['statusDelTrash'] = $modelClass::STATUS_DEL_TRASH;

            $dataProvider = $searchModel->search($params);
        return $this->renderIsAjax('index-trash', compact('dataProvider', 'searchModel'));
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
     * 
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
     * 
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
}

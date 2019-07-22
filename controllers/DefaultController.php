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
     public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'update' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ]);
    }
    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $modelClass = $this->modelClass;
        $searchModel =  new $this->modelSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
            && !User::hasPermission($modelClass::getFullAccessPermission()));

        if ($searchModel) {
            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess) {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }
                $params[$searchName]['folder'] = $modelClass::FOLDER_POSTED;

            $dataProvider = $searchModel->search($params);
        } else {
            $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
            $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

        return $this->renderIsAjax('index', compact('dataProvider', 'searchModel'));
    }
     /**
     * Lists all models.
     * @return mixed
     */
    public function actionDraft()
    {
        $modelClass = $this->modelClass;
        $searchModel =  new $this->modelSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
            && !User::hasPermission($modelClass::getFullAccessPermission()));

        if ($searchModel) {
            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess) {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }
                $params[$searchName]['folder'] = $modelClass::FOLDER_DRAFT;

            $dataProvider = $searchModel->search($params);
        } else {
            $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
            $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

        return $this->renderIsAjax('draft', compact('dataProvider', 'searchModel'));
    }
     /**
     * Lists all models.
     * @return mixed
     */
    public function actionTrash()
    {
        $modelClass = $this->modelClass;
        $searchModel =  new $this->modelSearchClass;
        $restrictAccess = (ArtHelper::isImplemented($modelClass, OwnerAccess::CLASSNAME)
            && !User::hasPermission($modelClass::getFullAccessPermission()));

        if ($searchModel) {
            $searchName = StringHelper::basename($searchModel::className());
            $params = Yii::$app->request->getQueryParams();

            if ($restrictAccess) {
                $params[$searchName][$modelClass::getOwnerField()] = Yii::$app->user->identity->id;
            }
                $params[$searchName]['folder'] = $modelClass::FOLDER_TRASH;

            $dataProvider = $searchModel->search($params);
        } else {
            $restrictParams = ($restrictAccess) ? [$modelClass::getOwnerField() => Yii::$app->user->identity->id] : [];
            $dataProvider = new ActiveDataProvider(['query' => $modelClass::find()->where($restrictParams)]);
        }

        return $this->renderIsAjax('trash', compact('dataProvider', 'searchModel'));
    }
    
     /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
     public function actionCompose() {
        /* @var $model \artsoft\db\ActiveRecord */
        $model = new $this->modelClass;

        if ($model->load(Yii::$app->request->post())) {
            $folder = Yii::$app->request->post('folder');

            if (empty($folder)) {
                throw new NotFoundHttpException(Yii::t('art/mailbox', 'Required Folder parameter is missing.'));
            }
            if ($model->send($folder) && $model->save()) {
//                 echo '<pre>' . print_r($model, true) . '</pre>';                
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

        if ($model->load(Yii::$app->request->post())) {
            $folder = Yii::$app->request->post('folder');

            if (empty($folder)) {
                throw new NotFoundHttpException(Yii::t('art/mailbox', 'Required Folder parameter is missing.'));
            }
            if ($model->send($folder) && $model->save()) {
//                 echo '<pre>' . print_r($model, true) . '</pre>';                
                return $this->redirect($this->getRedirectPage('index', $model));
            }
        }
            return $this->renderIsAjax('update', compact('model'));
    }
}
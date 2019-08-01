<?php

namespace artsoft\mailbox\controllers;


use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use artsoft\mailbox\models\ImageManager;
use yii\helpers\FileHelper;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
/**
 * Description of ImageManagerController
 *
 * @author markov-av
 */
class ImageManagerController extends \artsoft\controllers\admin\BaseController {

    /**
     * 
     * @return type
     * @throws BadRequestHttpException
     */
    public function actionFileUpload() {
        
        $type_array = [ 
              'jpg' => ['type' => 'image'],
              'png' => ['type' => 'image', 'filetype' => 'image/png'],
              'pdf' => ['type' => 'pdf'],
              'mp4' => ['type' => 'video' , 'filetype' => 'video/mp4'],        
          ];

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $dir = Yii::getAlias('@images') . '/' . $post['ImageManager']['class'] . '/';
            
            if (!file_exists($dir)) {
                FileHelper::createDirectory($dir);
            }

            $result_link = str_replace('admin', '', Url::home(true)) . 'uploads/images' . '/' . $post['ImageManager']['class'] . '/';
         
            foreach (UploadedFile::getInstancesByName('ImageManager[attachment]') as $file) {
           // echo '<pre>' . print_r($file, true) . '</pre>';
            $model = new ImageManager();

            $model->name = strtotime('now') . '_' . Yii::$app->getSecurity()->generateRandomString(6) . '.' . $file->extension;
            $model->orig_name = $name;
            $model->alt = $name;
            $model->type = ArrayHelper::getValue($type_array, $file->extension . '.type') ? ArrayHelper::getValue($type_array, $file->extension . '.type') : 'image';
            $model->filetype = ArrayHelper::getValue($type_array, $file->extension . '.filetype');
            //$model->url = $result_link . $model->name;
            $model->size = $file->size;
           // echo '<pre>' . print_r($model, true) . '</pre>';
            $model->load($post);

            $model->validate();
            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
                if ($file->saveAs($dir . $model->name)) {
//                    $imag = Yii::$app->image->load($dir . $model->name);
//                    $imag->resize(800, NULL, Image::PRECISE)->save($dir . $model->name, 85);
                    $result = ['filelink' => $result_link . $model->name, 'filename' => $model->name];
                } else {
                    $result = [
                        'error' => Yii::t('art', 'Error')
                    ];
                }
                $model->save();
            }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        } else {

            throw new BadRequestHttpException('Only POST is allowed.');
        }
    }

    public function actionUpload()
 {
     Yii::$app->response->format = Response::FORMAT_JSON;
     if (!Yii::$app->request->isAjax) {
         throw new BadRequestHttpException();
     }
     $post = Yii::$app->request->post();
     $files = UploadedFile::getInstancesByName('ImageManager[attachment]');
     $baseDir = Yii::getAlias(\artsoft\mailbox\MailboxModule::getInstance()->basePath);
     if (!is_dir($baseDir)) {
         mkdir($baseDir);
     }
     $dir = $baseDir .  DIRECTORY_SEPARATOR . $post['ImageManager']['class'] . DIRECTORY_SEPARATOR;
     if (!is_dir($dir)) {
         mkdir($dir);
     }
     $response = [];
     
     echo '<pre>' . print_r($post, true) . '</pre>';
     echo '<pre>' . print_r($dir, true) . '</pre>';
     foreach ($files as $key => $file) {
        
             $name = $file->name;
         
         $file->saveAs($dir . DIRECTORY_SEPARATOR . $name);
         $model = new ImageManager();
         $model->orig_name = $name;
         $model->alt = $name;
         $model->name = strtotime('now') . '_' . Yii::$app->getSecurity()->generateRandomString(6) . '.' . $file->extension;  
            $model->type = ArrayHelper::getValue($type_array, $file->extension . '.type') ? ArrayHelper::getValue($type_array, $file->extension . '.type') : 'image';
            $model->filetype = ArrayHelper::getValue($type_array, $file->extension . '.filetype');
           
            $model->size = $file->size;
            echo '<pre>' . print_r($file, true) . '</pre>';
         if ($model->save()) {
             $response = ['status' => true, 'message' => 'Success', 'html' => $this->renderAjax('_image', ['model' => $model])];
         }
         break;
     }
     return $response;
 }
    /**
     * 
     * @param type $id
     * @return boolean
     * @throws MethodNotAllowedHttpException
     */
    public function actionSortImage($id) {
        if (Yii::$app->request->isAjax) {
            
            $model = ImageManager::findOne(['item_id' => $id]);
            $post = Yii::$app->request->post('sort');
            
            if ($post['oldIndex'] > $post['newIndex']) {
                $param = ['and', ['>=', 'sort', $post['newIndex']], ['<', 'sort', $post['oldIndex']]];
                $counter = 1;
            } else {
                $param = ['and', ['<=', 'sort', $post['newIndex']], ['>', 'sort', $post['oldIndex']]];
                $counter = -1;
            }
            ImageManager::updateAllCounters(['sort' => $counter], [
                'and', ['class' => $model->class, 'item_id' => $id], $param]);
            ImageManager::updateAll(['sort' => $post['newIndex']], [
                'id' => $post['stack'][$post['newIndex']]['key']
            ]);

            return true;
        }
        throw new MethodNotAllowedHttpException();
    }

    /**
     * 
     * @return boolean
     * @throws NotFoundHttpException
     */
    public function actionDeleteImage() {
        if (($model = ImageManager::findOne(Yii::$app->request->post('key'))) and $model->delete()) {

            return true;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}

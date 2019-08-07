<?php

namespace artsoft\mailbox;

use Yii;
use yii\helpers\StringHelper;
use yii\base\InvalidConfigException;
/**
 * HTML Mailbox Module For Art CMS
 * 
 */
class MailboxModule extends \yii\base\Module
{
    /**
     * Version number of the module.
     */
    const VERSION = '0.1.0';

    public $controllerNamespace = 'artsoft\mailbox\controllers';
    
    public $view;

     /**
     * 
     * @var string 
     */
    public $basePath;
    
    public $uploadPath;
    
    public $absolutePath;
    
    
    public function init()
    {
         if (!isset($this->basePath)) {
            $this->basePath = '@frontend/web';
        }

        if (!isset($this->uploadPath)) {
            $this->uploadPath = 'uploads/files';
        }
        
         $this->absolutePath = Yii::getAlias($this->basePath);
        if (!StringHelper::endsWith($this->basePath, '/', false)) {
            $this->absolutePath .= '/';
        }
         $this->absolutePath .= $this->uploadPath;
        if (!StringHelper::endsWith($this->uploadPath, '/', false)) {
            $this->absolutePath .= '/';
        }
        
         if (!file_exists($this->absolutePath)) {
           mkdir($this->absolutePath, 0777, true);
        }
        
        if (!is_dir($this->absolutePath)) {
            throw new InvalidConfigException('Path is not directory');
        }
        if (!is_writable($this->absolutePath)) {
            throw new InvalidConfigException('Path is not writable! Check chmod!');
        }
        parent::init();


       
       
    }
}
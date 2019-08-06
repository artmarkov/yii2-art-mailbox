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
     * @var string $basePath
     */
    public $basePath = '@frontend/web/uploads/images';
    
    public function init()
    {
         $this->basePath = Yii::getAlias($this->basePath);
        if (!StringHelper::endsWith($this->basePath, '/', false)) {
            $this->basePath .= '/';
        }
         if (!file_exists($this->basePath)) {
            mkdir($this->basePath);
            chmod($this->basePath, 0755);
        }
        if (!is_dir($this->basePath)) {
            throw new InvalidConfigException('Path is not directory');
        }
        if (!is_writable($this->basePath)) {
            throw new InvalidConfigException('Path is not writable! Check chmod!');
        }
        parent::init();

        // custom initialization code goes here
    }
}
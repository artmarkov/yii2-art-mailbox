<?php

namespace artsoft\mailbox\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%image_manager}}".
 *
 * @property int $id
 * @property string $orig_name
 * @property string $name
 * @property string $class
 * @property int $item_id
 * @property int $sort
 * @property string $alt
 * @property string $type
 * @property string $filetype
 * @property string $size
 */
class ImageManager extends \yii\db\ActiveRecord {

    
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%image_manager}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['orig_name', 'name', 'type'], 'required'],
            [['item_id', 'sort', 'size'], 'integer'],
            ['sort', 'default', 'value' => function($model) {
                $count = ImageManager::find()->andWhere(['class' => $model->class])->count();
                return ($count > 0) ? $count++ : 0;
            }],
            [['type', 'filetype'], 'safe'],
            [['orig_name', 'name', 'class', 'alt'], 'string', 'max' => 256],
            //[['attachment'], 'image'],
           // [['attachment'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, pdf'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('art', 'ID'),
            'orig_name' => Yii::t('art', 'Orig Name'),
            'name' => Yii::t('art', 'Name'),
            'class' => Yii::t('art', 'Class'),
            'item_id' => Yii::t('art', 'Item ID'),
            'alt' => Yii::t('art', 'Alt'),
            'type' => Yii::t('art', 'Type'),
            'filetype' => Yii::t('art', 'Filetype'),
            'size' => Yii::t('art', 'Size'),
        ];
    }

    /**
     * 
     * @return boolean
     */
    public function beforeDelete() {
        if (parent::beforeDelete()) {
            ImageManager::updateAllCounters(['sort' => -1], [
                'and', ['class' => $this->class, 'item_id' => $this->item_id], [ '>', 'sort', $this->sort]
            ]);
            //удаляем физически
                $baseDir = Yii::getAlias(\artsoft\mailbox\MailboxModule::getInstance()->basePath);
                
                    if (file_exists($baseDir . DIRECTORY_SEPARATOR . $this->class . DIRECTORY_SEPARATOR . $this->name)) {
                        @unlink($baseDir . DIRECTORY_SEPARATOR . $this->class . DIRECTORY_SEPARATOR . $this->name);
                    }
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @return string
     */
    public function getImageUrl() {
        if ($this->name) {
            $path = str_replace('admin', '', Url::home(true)) . 'uploads/images/' . $this->class . DIRECTORY_SEPARATOR . $this->name;
        } else {
            $path = str_replace('admin', '', Url::home(true)) . 'uploads/images/nophoto.svg';
        }
        return $path;
    }
    
}

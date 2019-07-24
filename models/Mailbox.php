<?php

namespace artsoft\mailbox\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use artsoft\models\User;
use yii\helpers\HtmlPurifier;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%mailbox}}".
 *
 * @property int $id
 * @property int $sender_id
 * @property string $title
 * @property string $content
 * @property int $folder
 * @property int $created_at
 * @property int $updated_at
 * @property int $posted_at
 * @property int $remoted_at
 *
 * @property User $sender
 * @property MailboxReceiver[] $mailboxReceivers
 */
class Mailbox extends \artsoft\db\ActiveRecord
{
    public $gridReceiverSearch;
    
    const SCENARIO_COMPOSE = 'compose';
    
    const FOLDER_RECEIVER = 0;  // Приняты
    const FOLDER_POSTED = 1;    // отправленные
    const FOLDER_DRAFT = 2;     // черновик
    const FOLDER_TRASH = 3;     // в корзине   
    const FOLDER_TRUNCATE = -1; // удалено в скрытую папку   
    
    const STATUS_NEW = 0;       // не прочитано
    const STATUS_READ = 1;      // прочитано 
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mailbox}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),            
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'sender_id',
                'updatedByAttribute' => NULL,
            ],
            [
                'class' => \artsoft\behaviors\ManyHasManyBehavior::className(),
                'relations' => [
                    'receivers' => 'receivers_ids',
                ],
            ],
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['receivers_ids', 'required', 'on' => self::SCENARIO_COMPOSE, 'enableClientValidation' => false],
            [['folder', 'posted_at', 'remoted_at'], 'integer'],
            [['sender_id', 'created_at', 'updated_at'], 'safe'],
            ['receivers_ids', 'each', 'rule' => ['integer']],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['sender_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('art', 'ID'),
            'sender_id' => Yii::t('art/mailbox', 'Sender ID'),
            'receivers_ids' => Yii::t('art/mailbox', 'Receivers'),
            'title' => Yii::t('art', 'Title'),
            'content' => Yii::t('art', 'Content'),
            'folder' => Yii::t('art/mailbox', 'Folder'),
            'created_at' => Yii::t('art', 'Created'),
            'updated_at' => Yii::t('art', 'Updated'),
            'posted_at' => Yii::t('art/mailbox', 'Posted At'),
            'remoted_at' => Yii::t('art/mailbox', 'Remoted At'),
            'gridReceiverSearch' => Yii::t('art/mailbox', 'Receivers'),
        ];
    }
    
    /**
     * {@inheritdoc}
     * @return MailboxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MailboxQuery(get_called_class());
    }
    /**
     * 
     * @param type $folder
     * @return $this
     */
    public function getComposeData($folder)
    {
        $this->folder = $folder;

        switch ($folder)
        {
            case self::FOLDER_POSTED : {
                    $this->scenario = self::SCENARIO_COMPOSE;
                    $this->posted_at = time();
                }   break;
            case self::FOLDER_DRAFT : {
                    $this->remoted_at = NULL;
                }   break;
            case self::FOLDER_TRASH : {
                    $this->remoted_at = time();
                }   break;
            case self::FOLDER_TRUNCATE : {
                    $this->remoted_at = time();
                }   break;
            default: break;
        }

        return $this;
    }
    /**
     * 
     * @param type $model
     * @return $this
     */
    public function getReplyData($model)
    {
        
        $this->title = "Re:" . $model->title;
        $this->content = $this->getReplyContent($model);
        $this->receivers_ids = [
            $model->sender_id
        ];
        return $this;
    } 
    /* 
     * @param type $model
     * @return $this
     */
    public function getForwardData($model)
    {
        
        $this->title = "Fwd:" . $model->title;
        $this->content = $this->getReplyContent($model);
       
        return $this;
    }
    /**
     * 
     * @param type $model
     * @return type string
     */
    public function getReplyContent($model)
    {
        return "<blockquote>" . $model->postedDatetime . Yii::t('art/mailbox', '&nbsp;from&nbsp;') . $model->senderName . ":<br><br>" . $model->content . "</blockquote>";
       
    }

    /**
     * 
     * @param type $folder
     * @return type string
     */
    public static function getMessage($folder){
       switch ($folder) {
            case self::FOLDER_POSTED :
                return Yii::t('art/mailbox', 'Your mail has been posted.');
            case self::FOLDER_DRAFT :
                return Yii::t('art/mailbox', 'Your mail has been moved to the drafts folder.');
            case self::FOLDER_TRASH :
                return Yii::t('art/mailbox', 'Your mail has been moved to the trash folder.');
            case self::FOLDER_TRUNCATE :
                return Yii::t('art/mailbox', 'Your mail has been deleted.');
            default:
                return NULL;
        } 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceivers()
    {
        return $this->hasMany(User::className(), ['id' => 'receiver_id'])       
                ->viaTable('mailbox_receiver', ['mailbox_id' => 'id']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }
    
     /* Геттер для имени отправителя */
    public function getSenderName()
    {
        return $this->sender->username;
    }
   
    public function getShortContent($length = 64, $allowableTags = '')
    {
        $content =  strip_tags($this->content, $allowableTags);
        return HtmlPurifier::process(mb_substr(Html::encode($content), 0, $length, "UTF-8")) . ((strlen($content) > $length) ? '...' : '');
    }
    
    /**
     * getStatusList
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::STATUS_NEW => Yii::t('art/mailbox', 'New'),
            self::STATUS_READ => Yii::t('art/mailbox', 'Read'),
        );
    }
     /**
     * getStatusOptionsList
     * @return array
     */
    public static function getStatusOptionsList()
    {
        return [
            [self::STATUS_NEW, Yii::t('art/mailbox', 'New'), 'success'],
            [self::STATUS_READ, Yii::t('art/mailbox', 'Read'), 'default']
        ];
    }
    
    public function getPostedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->posted_at);
    }

    public function getRemotedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->remoted_at);
    }

    public function getPostedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->posted_at);
    }

    public function getRemotedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->remoted_at);
    }

    public function getPostedDatetime()
    {
        return "{$this->postedDate} {$this->postedTime}";
    }

    public function getRemotedDatetime()
    {
        return "{$this->remotedDate} {$this->remotedTime}";
    } 
    
    public function getCreatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->created_at);
    }

    public function getUpdatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->updated_at);
    }

    public function getCreatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->created_at);
    }

    public function getUpdatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->updated_at);
    }

    public function getCreatedDatetime()
    {
        return "{$this->createdDate} {$this->createdTime}";
    }

    public function getUpdatedDatetime()
    {
        return "{$this->updatedDate} {$this->updatedTime}";
    }
    
}

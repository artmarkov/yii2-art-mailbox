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
    
    const FOLDER_DRAFT = 0;   // черновик
    const FOLDER_POSTED = 1;  // отправленные
    const FOLDER_RECEIVER = 2; // Приняты
    const FOLDER_TRASH = 5;   // в корзине   
    
    const STATUS_NOREAD = 0; // не прочитано
    const STATUS_READ = 1;   // прочитано 
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
            [['folder', 'posted_at', 'remoted_at'], 'integer'],
            [['sender_id', 'created_at', 'updated_at', 'receivers_ids'], 'safe'],
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
   
    public function getShortContent($length = 64)
    {
        return HtmlPurifier::process(mb_substr(Html::encode($this->content), 0, $length, "UTF-8")) . ((strlen($this->content) > $length) ? '...' : '');
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

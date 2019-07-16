<?php

namespace artsoft\mailbox\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use artsoft\models\User;
use voskobovich\linker\LinkerBehavior;
use voskobovich\linker\updaters\OneToManyUpdater;

/**
 * This is the model class for table "{{%mailbox}}".
 *
 * @property int $id
 * @property int $sender_id
 * @property string $title
 * @property string $content
 * @property int $draft_flag
 * @property int $remote_flag
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
    public $receivers_ids;
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
                'class' => LinkerBehavior::className(),
                'relations' => [
                    'receivers_ids' => [
                        'receivers',
                        'updater' => [
                            'class' => OneToManyUpdater::className(),
                            
                        ]
                    ],
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
            ['title', 'required'],
            [['draft_flag', 'remote_flag', 'posted_at', 'remoted_at'], 'integer'],
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
            'title' => Yii::t('art', 'Title'),
            'content' => Yii::t('art', 'Content'),
            'draft_flag' => Yii::t('art/mailbox', 'Draft Flag'),
            'remote_flag' => Yii::t('art/mailbox', 'Remote Flag'),
            'created_at' => Yii::t('art', 'Created'),
            'updated_at' => Yii::t('art', 'Updated'),
            'posted_at' => Yii::t('art/mailbox', 'Posted At'),
            'remoted_at' => Yii::t('art/mailbox', 'Remoted At'),
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
        return $this->hasMany(MailboxReceiver::className(), ['mailbox_id' => 'id']);
    }
     /**
     * @return \yii\db\ActiveQuery
     */
    
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
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

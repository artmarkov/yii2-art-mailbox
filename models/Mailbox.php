<?php

namespace artsoft\mailbox\models;

use Yii;
use artsoft\models\User;

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
 */
class Mailbox extends \artsoft\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mailbox}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sender_id', 'title'], 'required'],
            [['sender_id', 'draft_flag', 'remote_flag', 'created_at', 'updated_at', 'posted_at', 'remoted_at'], 'integer'],
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
            'id' => Yii::t('art/mailbox', 'ID'),
            'sender_id' => Yii::t('art/mailbox', 'Sender ID'),
            'title' => Yii::t('art/mailbox', 'Title'),
            'content' => Yii::t('art/mailbox', 'Content'),
            'draft_flag' => Yii::t('art/mailbox', 'Draft Flag'),
            'remote_flag' => Yii::t('art/mailbox', 'Remote Flag'),
            'created_at' => Yii::t('art/mailbox', 'Created At'),
            'updated_at' => Yii::t('art/mailbox', 'Updated At'),
            'posted_at' => Yii::t('art/mailbox', 'Posted At'),
            'remoted_at' => Yii::t('art/mailbox', 'Remoted At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::className(), ['id' => 'sender_id']);
    }

    /**
     * {@inheritdoc}
     * @return MailboxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MailboxQuery(get_called_class());
    }
}

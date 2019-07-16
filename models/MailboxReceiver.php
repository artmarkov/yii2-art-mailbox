<?php

namespace artsoft\mailbox\models;

use Yii;
use artsoft\models\User;

/**
 * This is the model class for table "{{%mailbox_receiver}}".
 *
 * @property int $id
 * @property int $mailbox_id
 * @property int $receiver_id
 * @property int $read_flag
 * @property int $remote_flag
 * @property int $created_at
 * @property int $reading_at
 * @property int $remoted_at
 *
 * @property User $receiver
 * @property Mailbox $mailbox
 */
class MailboxReceiver extends \artsoft\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mailbox_receiver}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mailbox_id', 'receiver_id'], 'required'],
            [['mailbox_id', 'receiver_id', 'read_flag', 'remote_flag', 'created_at', 'reading_at', 'remoted_at'], 'integer'],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['receiver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('art', 'ID'),
            'mailbox_id' => Yii::t('art/mailbox', 'Mailbox ID'),
            'receiver_id' => Yii::t('art/mailbox', 'Receiver ID'),
            'read_flag' => Yii::t('art/mailbox', 'Read Flag'),
            'remote_flag' => Yii::t('art/mailbox', 'Remote Flag'),
            'created_at' => Yii::t('art', 'Created'),
            'reading_at' => Yii::t('art/mailbox', 'Reading At'),
            'remoted_at' => Yii::t('art/mailbox', 'Remoted At'),
        ];
    }

   /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::className(), ['id' => 'receiver_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailbox()
    {
        return $this->hasOne(Mailbox::className(), ['id' => 'mailbox_id']);
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

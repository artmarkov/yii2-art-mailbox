<?php

namespace artsoft\mailbox\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use artsoft\mailbox\models\MailboxReceiver;

/**
 * MailboxReceiverSearch represents the model behind the search form about `artsoft\mailbox\models\MailboxReceiver`.
 */
class MailboxReceiverSearch extends MailboxReceiver
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'mailbox_id', 'receiver_id', 'reading_at', 'remoted_at', 'folder', 'status'], 'integer'],
            [['mailboxTitle', 'mailboxContent'], 'string'],
            [['mailboxSenderId', 'mailboxPostedDate'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MailboxReceiver::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                
                'mailboxSenderId' => [
                    'asc' => ['mailbox.sender_id' => SORT_ASC],
                    'desc' => ['mailbox.sender_id' => SORT_DESC],
                ],
                              
                'mailboxTitle' => [
                    'asc' => ['mailbox.title' => SORT_ASC],
                    'desc' => ['mailbox.title' => SORT_DESC],
                ],
                
                'mailboxContent' => [
                    'asc' => ['mailbox.content' => SORT_ASC],
                    'desc' => ['mailbox.content' => SORT_DESC],
                ],
                
                'mailboxPostedDate' => [
                    'asc' => ['mailbox.posted_at' => SORT_ASC],
                    'desc' => ['mailbox.posted_at' => SORT_DESC],
                ],
                  
                'status',
                'receiver_id',
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        жадная загрузка
        $query->joinWith(['mailbox']);
        
        $query->andFilterWhere([
            'id' => $this->id,
            'mailbox_id' => $this->mailbox_id,
            'receiver_id' => $this->receiver_id,
            'reading_at' => $this->reading_at,
            'remoted_at' => $this->remoted_at,
            'folder' => $this->folder,
            'status' => $this->status,
        ]);

        $query->joinWith(['mailbox' => function ($q) {
            $q->where('mailbox.title LIKE "%' . $this->mailboxTitle . '%"');
        }]);
        $query->joinWith(['mailbox' => function ($q) {
            $q->where('mailbox.content LIKE "%' . $this->mailboxContent . '%"');
        }]);
        $query->joinWith(['mailbox' => function ($q) {
            $q->andFilterWhere(['mailbox.sender_id' => $this->mailboxSenderId]);
        }]);
         $query->joinWith(['mailbox' => function ($q) {
            $q->andFilterWhere(['mailbox.posted_at' => $this->mailboxPostedDate]);
        }]);
        
        return $dataProvider;
    }
}
<?php

namespace artsoft\mailbox\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use artsoft\mailbox\models\Mailbox;

/**
 * MailboxSearch represents the model behind the search form about `artsoft\mailbox\models\Mailbox`.
 */
class MailboxSearch extends Mailbox
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sender_id', 'created_at', 'updated_at', 'posted_at', 'deleted_at', 'status_post', 'status_del', 'statusDelTrash'], 'integer'],
            [['title', 'content'], 'safe'],
            ['gridReceiverSearch', 'string']
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
        $query = Mailbox::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
            ],
            'sort' => [
                'defaultOrder' => [
                    'posted_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        
//        жадная загрузка
        $query->with(['receivers']);
        
         if ($this->gridReceiverSearch) {
            $query->joinWith(['receivers']);
        }
                 
        $query->andFilterWhere([
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'posted_at' => $this->posted_at,
            'deleted_at' => $this->deleted_at,
            'status_post' => $this->status_post,
            'mailbox.status_del' => $this->status_del,
            'mailbox_inbox.receiver_id' => $this->gridReceiverSearch,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);
        
         if ($this->statusDelTrash)
        {
            $query->joinWith(['receivers']);
            $query->andFilterWhere(['OR', ['=', 'mailbox.sender_id', Yii::$app->user->identity->id], ['=', 'mailbox_inbox.receiver_id', Yii::$app->user->identity->id]])
                  ->andFilterWhere(['OR', ['=', 'mailbox.status_del', $this->statusDelTrash], ['=', 'mailbox_inbox.status_del', $this->statusDelTrash]]);
            
            $query->select(['mailbox.id', 'title', 'content', 'posted_at', 'sender_id'])->distinct();
        }
        return $dataProvider;
    }
}

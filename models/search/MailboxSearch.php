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
            [['id', 'sender_id', 'created_at', 'updated_at', 'posted_at', 'remoted_at', 'folder'], 'integer'],
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
                    'id' => SORT_DESC,
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
            'remoted_at' => $this->remoted_at,
            'folder' => $this->folder,
            'mailbox_receiver.receiver_id' => $this->gridReceiverSearch,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}

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
            [['id', 'mailbox_id', 'receiver_id', 'created_at', 'reading_at', 'remoted_at'], 'integer'],
            [['read_flag', 'remote_flag'], 'safe'],
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'mailbox_id' => $this->mailbox_id,
            'receiver_id' => $this->receiver_id,
            'created_at' => $this->created_at,
            'reading_at' => $this->reading_at,
            'remoted_at' => $this->remoted_at,
        ]);

        $query->andFilterWhere(['like', 'read_flag', $this->read_flag])
            ->andFilterWhere(['like', 'remote_flag', $this->remote_flag]);

        return $dataProvider;
    }
}

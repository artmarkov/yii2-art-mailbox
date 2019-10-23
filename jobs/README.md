add to ScheduleController in console\controllers
and run migrate m191022_171748_add_mailbox_job_to_queue_schedule

```
class ScheduleController extends Controller {

    public function actionIndex()
    {

        ------------------

        Yii::$app->queue->push(new \artsoft\mailbox\jobs\MessageNewEmailJob());
        Yii::$app->queue->push(new \artsoft\mailbox\jobs\ClianDeletedMailJob());
        Yii::$app->queue->push(new \artsoft\mailbox\jobs\TrashMailJob());

        $queue = Yii::$app->queue;
        $queue->run(false);
    }
}
```

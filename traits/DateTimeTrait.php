<?php

/**
 * @link https://github.com/artmarkov/yii2-art-mailbox
 * @copyright Copyright (c) 2019 Artur Markov
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace artsoft\mailbox\traits;

use Yii;

/**
 * DateTimeTrait
 * @author Artur Markov <artmarkov@mail.ru> 
 */
trait DateTimeTrait {

    /**
     * @return type string
     */
    public function getCreatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->created_at);
    }

    /**
     * @return type string
     */
    public function getCreatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->created_at);
    }

    /**
     * @return type string
     */
    public function getCreatedDatetime()
    {
        return "{$this->createdDate} {$this->createdTime}";
    }

    /**
     * @return type string
     */
    public function getUpdatedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->updated_at);
    }

    /**
     * @return type string
     */
    public function getUpdatedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->updated_at);
    }

    /**
     * @return type string
     */
    public function getUpdatedDatetime()
    {
        return "{$this->updatedDate} {$this->updatedTime}";
    }

    /**
     * @return type string
     */
    public function getDeletedDate()
    {
        return Yii::$app->formatter->asDate(($this->isNewRecord) ? time() : $this->deleted_at);
    }

    /**
     * @return type string
     */
    public function getDeletedTime()
    {
        return Yii::$app->formatter->asTime(($this->isNewRecord) ? time() : $this->deleted_at);
    }

    /**
     * @return type string
     */
    public function getDeletedDatetime()
    {
        return "{$this->deletedDate} {$this->deletedTime}";
    }

}

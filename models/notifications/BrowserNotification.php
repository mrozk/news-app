<?php

namespace app\models\notifications;

use yii\base\Component;

class BrowserNotification extends NotificationHandler implements NotificationInterface
{

    public function execute($params)
    {
        echo 'browser handler';
    }

}
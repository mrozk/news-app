<?php

namespace app\models\notifications;


interface NotificationInterface
{
    public function execute($params);
}
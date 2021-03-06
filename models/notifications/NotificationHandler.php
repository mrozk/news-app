<?php

namespace app\models\notifications;

use app\models\Notifications;
use app\models\Templates;
use app\models\User;
use yii\helpers\ArrayHelper;

class NotificationHandler implements NotificationInterface
{

    /**
     * @var NotificationInterface
     */
    public $handler;

    /**
     * @return NotificationInterface
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param NotificationInterface $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }


    public function execute($params)
    {
        /** @var Notifications $notification */
        /** @var Templates $template */
        $notification = ArrayHelper::getValue($params, 'notification');
        $groups = [];

        foreach (Notifications::$userGroups as $key => $item) {
            if ($notification->user_groups & $item) {
                $groups[] = $key;
            }
        }

        $userIds = [];
        foreach ($groups as $item) {
            $userIds = ArrayHelper::merge($userIds, \Yii::$app->authManager->getUserIdsByRole($item));
        }

        $params['user_ids'] = array_unique($userIds);
        $this->handler->execute($params);

    }


}
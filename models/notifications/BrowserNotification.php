<?php

namespace app\models\notifications;

use app\models\Templates;
use yii\helpers\ArrayHelper;

class BrowserNotification extends NotificationHandler implements NotificationInterface
{

    public function execute($params)
    {
        /** @var Templates $template */
        $template = ArrayHelper::getValue($params, 'template');
        $pooling = new PoolingImpl();
        $pooling->push('news', $template->resolveTemplate(ArrayHelper::getValue($params, 'model'), null));
    }

}
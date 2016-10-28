<?php

namespace app\models\notifications;

use app\models\Notifications;
use app\models\Templates;
use app\models\User;
use yii\helpers\ArrayHelper;

class EmailNotification extends NotificationHandler implements NotificationInterface
{


    /**
     * @param $params
     */
    public function execute($params)
    {
        /** @var User $user */
        /** @var Templates $template */
        $user = ArrayHelper::getValue($params, 'user');
        $template = ArrayHelper::getValue($params, 'template');
        $message = ArrayHelper::getValue($params, 'message');
        $this->sendMail(
            $user->username,
            [\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'],
            $template->title,
            $message
        );
    }


    public function sendMail($email, $from, $subject, $body){

        \Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom($from)
            ->setSubject($subject)
            ->setHtmlBody($body)
            ->send();
    }


}
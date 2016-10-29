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
        $userList = User::findAll(
            [
                'id' =>  ArrayHelper::getValue($params, 'user_ids'),
                'active' =>1
            ]);
        /** @var Templates $template */
        $template = ArrayHelper::getValue($params, 'template');
        foreach($userList as $item){

            $this->sendMail(
                $item->username,
                [\Yii::$app->params['adminEmail'] => \Yii::$app->name . ' robot'],
                $template->title,
                $template->resolveTemplate(ArrayHelper::getValue($params, 'model'), $item)
            );

        }

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
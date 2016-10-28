<?php

namespace app\models;

use app\models\notifications\BrowserNotification;
use app\models\notifications\EmailNotification;
use app\models\notifications\NotificationHandler;
use Yii;
use yii\base\Event;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property string $model_name object class
 * @property integer $events_list list of events for notification
 * @property integer $level type of notification(email browser telegram)
 * @property integer $user_groups user groups notification value
 * @property integer $template_id
 */
class Notifications extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_name', 'events_list', 'level', 'user_groups', 'template_id'], 'required'],
            [['events_list', 'level', 'user_groups', 'template_id'], 'integer'],
            [['model_name'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'model_name' => Yii::t('app', 'Model Name'),
            'events_list' => Yii::t('app', 'Events List'),
            'level' => Yii::t('app', 'Level'),
            'user_groups' => Yii::t('app', 'User Groups'),
            'template_id' => Yii::t('app', 'Template ID'),
        ];
    }

    public static $notificationLevel = [
        'email' => 2,
        'browser' => 4,
        // 'telegram' => 8
    ];

    public static $modelEventsList = [
        ActiveRecord::EVENT_AFTER_INSERT => 2,
        ActiveRecord::EVENT_AFTER_UPDATE => 4,
    ];


    public static $userGroups = [
        User::ROLE_READER => 2,
        User::ROLE_MODERATOR => 4,
        User::ROLE_ADMIN => 8,
    ];



    public static $eventsList = null;


    public static function notificationFactory($level){
        if( $level == 'browser'){
            return new BrowserNotification();
        }

        if( $level == 'email'){
            return new EmailNotification();
        }
    }

    public static function subscribeOnEvents(){

        foreach(self::$modelEventsList as $key => $item){
            Event::on(ActiveRecord::className(), $key, ['app\models\Notifications', 'processEvents']);
        }


    }

    public static function getModelList(){

        return[
            News::className() => News::className(),
            User::className() => User::className()
        ];

    }

    public static function processEvents($event){

        $notifications = Notifications::findAll(['model_name' => $event->sender->className()]);
        $handler = new NotificationHandler();

        foreach($notifications as $note){

            // check if this type of events we listen
            if(!($note->events_list & self::$modelEventsList[$event->name])){
                continue;
            }

            $template = Templates::findOne(['id' => $note->template_id]);
            if(!$template){
                continue;
            }
            foreach(self::$notificationLevel as $key => $item){
                if($item & $note->level){
                    $handler->setHandler(self::notificationFactory($key));
                    $handler->execute([
                        'notification' => $note,
                        'model' => $event->sender,
                        'template' => $template
                    ]);
                }
            }
        }


    }


}

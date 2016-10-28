<?php
/**
 * Created by PhpStorm.
 * User: mrozk
 * Date: 26.10.16
 * Time: 18:52
 */

namespace app\models;


use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class NotificationsForm extends Model
{

    public $model_name;
    public $user_groups;
    public $events_list;
    public $level;
    public $template;

    private $notificationModel;

    public function rules()
    {
        return [
            // username and password are both required
            [['model_name', 'user_groups', 'events_list', 'level', 'template'], 'safe'],
        ];
    }

    public function getNotifications(){
        return array_flip(Notifications::$notificationLevel);
    }

    public function getRolesList(){
        return array_flip(Notifications::$userGroups);
    }

    public function getEventsList(){
        return array_flip(Notifications::$modelEventsList);
    }

    public function getTemplatesList(){
        $templates = Templates::find()->select(['id', 'title'])->all();
        $result = ArrayHelper::map($templates, 'id', 'title');
        return $result;
    }


    public function getModelList(){
        return Notifications::getModelList();
    }

    public function generateLevel($values){
        $result = 0;
        foreach($values as $item){
            $result |= $item;
        }

        return $result;
    }


    public function saveNotification()
    {
        $notification = $this->getNotificationModel();
        $notification->events_list = $this->generateLevel($this->events_list);
        $notification->user_groups = $this->generateLevel($this->user_groups);
        $notification->level = $this->generateLevel($this->level);
        $notification->template_id = $this->template;
        $notification->model_name = $this->model_name;
        $notification->save();

        return $notification;
    }



    public function getArrayFromValue($value, $array){
        $result = [];
        foreach($array as $key => $item){
            if($value & $key){
                $result[] = $key;
            }
        }
        return $result;
    }

    /**
     * @param Notifications $param
     */
    public function setNotificationModel($param)
    {
        $this->notificationModel = $param;
        $this->model_name = $this->notificationModel->model_name;
        $this->events_list = $this->getArrayFromValue($this->notificationModel->events_list, $this->getEventsList());
        $this->level = $this->getArrayFromValue($this->notificationModel->level, $this->getNotifications());
        $this->user_groups = $this->getArrayFromValue($this->notificationModel->user_groups, $this->getRolesList());
        $this->template = $this->notificationModel->template_id;

    }



    /**
     * @return Notifications
     */
    public function getNotificationModel()
    {
        return $this->notificationModel;
    }

}
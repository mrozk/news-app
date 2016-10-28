<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "templates".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 */
class Templates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 512],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'body' => Yii::t('app', 'Body'),
        ];
    }



    private function getFieldValue($str){
        return preg_replace(['/#!/', '/!#/', '/entity/', '/user/', '/\./'], '', $str, 1);
    }

    private function getSource($str){

        if(substr_count($str, 'entity')){
            return 'entity';
        }

        if(substr_count($str, 'user')){
            return 'user';
        }

    }


    /**
     * @param $entity
     * @param User $user
     * @return mixed
     */
    public function resolveTemplate($entity, $user)
    {

        preg_match_all('/!#(entity|user)\.[a-zA-Z0-9]+#!/', $this->body, $matches);
        $matches = ArrayHelper::getValue($matches, 0);
        $replaceMap = [];
        foreach($matches as $item){

            $object = null;
            if($this->getSource($item) == 'user'){
                $object = $user;
            }elseif($this->getSource($item) == 'entity'){
                $object = $entity;
            }else{
                continue;
            }

            $value = '';
            $field = $this->getFieldValue($item);
            if(method_exists($object, $field)){
                $value = $object->$field();
            }elseif(isset($object->$field)){
                $value = $object->$field;
            }

            $replaceMap[$item] = $value;
        }
        $result = preg_replace_callback('/!#(entity|user)\.[a-zA-Z0-9]{1,}#!/', function($params) use ($replaceMap){
            return ArrayHelper::getValue($replaceMap, ArrayHelper::getValue($params, 0));
        }, $this->body);


        return $result;
    }

}

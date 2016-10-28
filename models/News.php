<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * This is the model class for table "news".
 *
 * @property integer $id
 * @property string $title
 * @property string $preview_text
 * @property string $body
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class News extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'preview_text', 'body'/*, 'user_id', 'created_at', 'updated_at'*/], 'required'],
            [['preview_text', 'body'], 'string'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
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
            'preview_text' => Yii::t('app', 'Preview Text'),
            'body' => Yii::t('app', 'Body'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }


    public function getUrl(){

        $url = Url::to([
            'news/view', 'id' => $this->id
        ]);

        return '<a href="' . Url::base(true) . $url . '">Go to news ' . $this->title . '</a>';
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
                $this->user_id = Yii::$app->getUser()->getId();
            }

            $this->updated_at = time();
            return true;
        }
        return false;
    }

}

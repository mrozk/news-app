<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $notifications_level
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $active
 * @property string approve_token
 */
class User extends ActiveRecord  implements IdentityInterface
{

    const ROLE_MODERATOR = 'moderator';
    const ROLE_ADMIN = 'admin';
    const ROLE_READER = 'reader';

    public static $notificationLevel = [
        'email' => 2,
        'browser' => 4
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username',  'auth_key', 'password_hash', 'approve_token'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            ['username', 'email'],
            ['username', 'unique'],
            [['username', 'password_hash'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            // [['email'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            // 'email' => Yii::t('app', 'Email'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }


    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'active' => 1]);
    }


    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }


    public static function findIdentityByApproveToken($token)
    {
        return static::findOne(['approve_token' => $token, 'active' => 1]);
    }


    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }



    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }


    public function generate($password){

    }


    public function setPassword($password){
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    public function generateAuthKey(){
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    public function generateApproveToken()
    {
        $this->approve_token = \Yii::$app->security->generateRandomString();;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }

            $this->updated_at = time();
            return true;
        }
        return false;
    }



}

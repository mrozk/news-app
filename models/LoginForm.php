<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\View;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{

    public $username;
    public $password;
    public $role = 'reader';
    public $password_repeat;
    public $notifications_level;
    public $rememberMe = true;

    private $loginLife = 2592000;
    private $_user = false;

    const SCENARIO_REGISTER = 'REGISTER';
    const SCENARIO_LOGIN = 'LOGIN';
    const SCENARIO_ADMIN_EDIT = 'ADMIN_EDIT';
    const SCENARIO_ADMIN_CREATE = 'ADMIN_CREATE';
    const SCENARIO_APPROVE_REGISTER = 'APPROVE_REGISTER';
    const SCENARIO_PROFILE_EDIT = 'PROFILE_EDIT';

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required'],

            [
                'username',
                'unique', 'targetClass' => 'app\models\User',
                'message' => 'This email address has already been taken.',
                'on' => [self::SCENARIO_REGISTER, self::SCENARIO_ADMIN_CREATE]
            ],

            ['username', 'email'],
            [
                'password', 'compare', 'on' => [
                self::SCENARIO_PROFILE_EDIT,
                self::SCENARIO_ADMIN_EDIT,
                self::SCENARIO_APPROVE_REGISTER
            ]
            ],

            [['password_repeat', 'password'], 'required', 'on' => self::SCENARIO_APPROVE_REGISTER],

            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword', 'on' => self::SCENARIO_LOGIN],


        ];
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['username'/*, 'password', 'password_repeat'*/];
        $scenarios[self::SCENARIO_LOGIN] = ['username', 'password', 'rememberMe'];
        $scenarios[self::SCENARIO_ADMIN_EDIT] = ['password', 'password_repeat', 'role'];
        $scenarios[self::SCENARIO_PROFILE_EDIT] = ['password', 'password_repeat'];
        $scenarios[self::SCENARIO_ADMIN_CREATE] = ['username', 'role'];
        $scenarios[self::SCENARIO_APPROVE_REGISTER] = ['password', 'password_repeat'];

        return $scenarios;
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {

        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? $this->loginLife : 0);
        }

        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }


    public function createUser($username, $password, $role = User::ROLE_ADMIN)
    {
        $this->setScenario(LoginForm::SCENARIO_ADMIN_EDIT);
        $this->username = $username;
        $this->password = $password;
        $this->password_repeat = $this->password;
        $this->role = $role;

        $user = new User();
        $user->username = $username;
        $user->active = 1;

        return $this->updateUser($user);

    }


    /**
     * @param User $user
     * @return bool
     */
    public function updateUser($user)
    {
        if (!$this->validate()) {
            return false;
        }

        if (!empty($this->password)) {
            $user->setPassword($this->password);
            $this->notification($user->username, 'Account was updated', 'New password: ' . $this->password);
        }

        if ($this->notifications_level !== null) {
            $user->notifications_level = $this->notifications_level;
        }

        $user->save(false);
        $this->setRole($user);

        return true;
    }


    /**
     *
     * Notification for admin
     *
     * @param $email
     * @param $subject
     * @param $text
     * @return bool
     */
    public function notification($email, $subject, $text)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot'])
                ->setSubject($subject)
                ->setHtmlBody($text)
                ->send();

            return true;
        }

        return false;
    }


    /**
     * @param User $user
     */
    public function setRole($user)
    {

        $roles = [User::ROLE_ADMIN, User::ROLE_MODERATOR, User::ROLE_READER];
        if (!in_array($this->role, $roles)) {
            $this->role = User::ROLE_READER;
        }


        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRole($this->role);
        $auth->revokeAll($user->id);
        $auth->assign($authorRole, $user->getId());
    }


    /**
     *
     * User Registration
     * @return User|null
     */
    public function signUp()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->generateApproveToken();
        $user->save(false);
        $this->setRole($user);
        $this->registerEmail($user);

        $this->notification(
            Yii::$app->params['adminEmail'],
            'New user has registered',
            'User ' . $user->username . ' has registered on site'
        );

        return $user;

    }


    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param User $user the target email address
     * @return bool whether the model passes validation
     */
    public function registerEmail($user)
    {
        if ($this->validate()) {
            $link = Url::toRoute(
                [
                    'site/approve-register',
                    'id' => $user->getId(),
                    'token' => $user->approve_token
                ]);
            Yii::$app->mailer->compose()
                ->setTo($user->username)
                ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->name . ' robot'])
                ->setSubject("User registration approve")
                ->setHtmlBody('<a href="' . $link . '">Approve</a>')
                ->send();

            return true;
        }

        return false;
    }


    /**
     * @param $data
     * @return User
     */
    public function checkApproveRegister($data)
    {
        if (!$data) {
            return false;
        }

        $user = User::findIdentityByApproveToken($data);
        if (!$user) {
            return null;
        }

        return $user;
    }

    public function approveRegister($data)
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->checkApproveRegister($data);
        if (!$user) {
            return false;
        }

        $user->setPassword($this->password);
        $user->active = 1;
        $user->approve_token = '';
        $user->save(false);

        return Yii::$app->user->login($user, $this->loginLife);
    }

    public function initForm($model)
    {
        $auth = Yii::$app->authManager;
        $authorRole = $auth->getRolesByUser($model->id);
        $this->role = array_pop(array_keys($authorRole));
        $this->username = $model->username;
    }

    public function setNotifications($post)
    {
        $this->notifications_level = 0;
        if(is_array($post)){
            foreach($post as $item){
                $this->notifications_level |= $item;
            }
        }
    }


}

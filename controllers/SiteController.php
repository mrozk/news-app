<?php

namespace app\controllers;

use app\models\User;
use ReflectionClass;
use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
        /*$user = new User();
        $user->trigger(ActiveRecord::EVENT_AFTER_INSERT);*/

       /* Event::on(Foo::className(), Foo::EVENT_HELLO, function ($event) {
            var_dump($event);  // displays "null"
        });

        $foo = new Foo();
        $foo->trigger(Foo::EVENT_HELLO);


        $oClass = new ReflectionClass(ActiveRecord::className());
        print_r($oClass->getConstants());*/

        //Event::trigger(Foo::className(), Foo::EVENT_HELLO);

        //return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->setScenario(LoginForm::SCENARIO_REGISTER);

        if ($model->load(Yii::$app->request->post()) && $model->signUp()) {
            echo 'Confirm registration instructions was send to your email';
            exit;
        }

        return $this->render('register', [
            'model' => $model,
        ]);

    }

    public function actionProfile()
    {
        $model = new LoginForm();
        $model->setScenario(LoginForm::SCENARIO_PROFILE_EDIT);


        if ($model->load(Yii::$app->request->post())) {
            $model->setNotifications(Yii::$app->request->post('notifications_level'));
            if($model->updateUser(\Yii::$app->getUser()->getIdentity())){
                return $this->refresh();
            }

        }

        return $this->render('profile', [
            'model' => $model,
            'user' => \Yii::$app->getUser()->getIdentity()
        ]);

    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->setScenario(LoginForm::SCENARIO_LOGIN);

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }


    public function actionApproveRegister()
    {
        $form = new LoginForm();
        $form->setScenario(LoginForm::SCENARIO_APPROVE_REGISTER);

        if(!$form->checkApproveRegister(Yii::$app->request->get('token'))){
            return $this->actionLogin();
        }

        if ($form->load(Yii::$app->request->post()) && $form->approveRegister(Yii::$app->request->get('token'))) {
            return $this->goHome();
        }

        return $this->render('password', [
            'model' => $form,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    /*public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }*/

    /**
     * Displays about page.
     *
     * @return string
     */
    /*public function actionAbout()
    {
        return $this->render('about');
    }*/

}

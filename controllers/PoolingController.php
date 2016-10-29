<?php

namespace app\controllers;

use app\models\notifications\PoolingImpl;
use yii\web\Controller;

class PoolingController extends Controller
{
    public function actionIndex(){
        $polling = new PoolingImpl();
        echo json_encode($polling->listen(['news'], \Yii::$app->request->get('time')));
    }


    public function actionView(){
        return $this->render('view', [
            //'model' => $this->findModel($id),
        ]);
    }


    public function actionPush(){
        $pooling = new PoolingImpl();
        $pooling->push("event1");
    }

}
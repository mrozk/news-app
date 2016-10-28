<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */
/* @var $user app\models\User */

use app\models\User;
use yii\bootstrap\BaseHtml;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'User profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?=$form->field($model, 'password')->passwordInput()?>
    <?=$form->field($model, 'password_repeat')->passwordInput()?>

    <?php
        $browserNote = 2;
        $emailNote = 4;
    ?>
    <div class="form-group">
        <?= Html::checkbox(
            'notifications_level[]',
            $user->notifications_level & User::$notificationLevel['email'],
            ['label' => 'Уведомление почтой', 'value' => User::$notificationLevel['email']]
        )?>
    </div>
    <div class="form-group">
        <?= Html::checkbox(
            'notifications_level[]',
            $user->notifications_level & User::$notificationLevel['browser'],
            ['label' => 'Уведомление браузером', 'value' => User::$notificationLevel['browser']])?>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Approve', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
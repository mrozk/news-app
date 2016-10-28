<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model app\models\NotificationsForm */

?>

<div class="user-form">

    <?php

    $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model_name')->dropDownList($model->getModelList())?>
    <?= $form->field($model, 'user_groups')->dropDownList($model->getRolesList(), ['multiple' => true])?>
    <?= $form->field($model, 'events_list')->dropDownList($model->getEventsList(), ['multiple' => true])?>
    <?= $form->field($model, 'level')->dropDownList($model->getNotifications(), ['multiple' => true])?>
    <?/*= $form->field($loginForm, 'username')->textInput(['maxlength' => true, 'readonly' => $model->isNewRecord ? false : true]) */?><!--
    <?/*=$form->field($loginForm, 'role')->dropDownList(
        [
            User::ROLE_ADMIN => 'Администратор',
            User::ROLE_MODERATOR => 'Модератор',
            User::ROLE_READER => 'Читатель'
        ])*/?>


    <?/*if(!$model->isNewRecord):*/?>
        <?/*= $form->field($loginForm, 'password')->textInput(['maxlength' => true]) */?>
        <?/*= $form->field($loginForm, 'password_repeat')->textInput(['maxlength' => true]) */?>
    --><?/*endif*/?>




    <?/*= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) */?><!--

    <?/*= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'approve_token')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'created_at')->textInput() */?>

    --><?/*= $form->field($model, 'updated_at')->textInput() */?>

    <div class="form-group">
        <?=Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>

        <?/*= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) */?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
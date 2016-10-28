<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Notifications */
/* @var $form yii\widgets\ActiveForm */
/* @var $formModel app\models\NotificationsForm */
?>

<div class="notifications-form">
    <?php
    $model = $formModel->getNotificationModel();
    ?>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($formModel, 'model_name')->dropDownList($formModel->getModelList()) ?>
    <?= $form->field($formModel, 'user_groups')->dropDownList($formModel->getRolesList(), ['multiple' => true]) ?>
    <?= $form->field($formModel, 'events_list')->dropDownList($formModel->getEventsList(), ['multiple' => true]) ?>
    <?= $form->field($formModel, 'level')->dropDownList($formModel->getNotifications(), ['multiple' => true]) ?>
    <?= $form->field($formModel, 'template')->dropDownList($formModel->getTemplatesList()) ?>

    <? /*= $form->field($model, 'model_name')->textInput(['maxlength' => true]) */ ?><!--

    <? /*= $form->field($model, 'events_list')->textInput() */ ?>

    <? /*= $form->field($model, 'level')->textInput() */ ?>

    <? /*= $form->field($model, 'user_groups')->textInput() */ ?>

    --><? /*= $form->field($model, 'template_id')->textInput() */ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

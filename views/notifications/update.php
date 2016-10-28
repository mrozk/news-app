<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Notifications */
/* @var $formModel app\models\NotificationsForm */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Notifications',
]) . $formModel->getNotificationModel()->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $formModel->getNotificationModel()->id, 'url' => ['view', 'id' => $formModel->getNotificationModel()->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="notifications-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'formModel' => $formModel
    ]) ?>

</div>

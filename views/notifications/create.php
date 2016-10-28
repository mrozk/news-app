<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Notifications */

$this->title = Yii::t('app', 'Create Notifications');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notifications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'formModel' => $formModel
    ]) ?>

</div>

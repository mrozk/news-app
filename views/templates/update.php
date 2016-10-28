<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Templates */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Templates',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="templates-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

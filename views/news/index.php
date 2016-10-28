<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <? if (Yii::$app->getUser()->can('updatePost')): ?>
            <?= Html::a(Yii::t('app', 'Create News'), ['create'], ['class' => 'btn btn-success']) ?>
        <? endif ?>
    </p>
    <?php
    $template = '';
    if (Yii::$app->getUser()->can('updatePost')) {
        $template = '{view} {update} {delete} {link}';
    } else if(Yii::$app->getUser()->can('readPost')){
        $template = '{view}{link}';
    }
    ?>
    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'layout'=>"{summary}\n{items}\n{pager}",

        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'preview_text:ntext',
            //'body:ntext',
            /*'user_id',*/
            // 'created_at',
            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $template,
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>

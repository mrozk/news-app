<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);


    $widgetItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Profile', 'url' => ['/site/profile']],

        ['label' => 'News', 'url' => ['/news/index']],

        Yii::$app->user->isGuest ? (
        ['label' => 'Login', 'url' => ['/site/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>'
        )
    ];

    if(Yii::$app->user->can('createUsers')){
        $widgetItems[] = ['label' => 'User', 'url' => ['/user/index']];
        $widgetItems[] = ['label' => 'Templates', 'url' => ['/templates/index']];
        $widgetItems[] = ['label' => 'Notifications', 'url' => ['/notifications/index']];

    }


    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $widgetItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>



<script type="text/javascript">

    /**
     * AJAX long-polling
     *
     * 1. sends a request to the server (without a timestamp parameter)
     * 2. waits for an answer from server.php (which can take forever)
     * 3. if server.php responds (whenever), put data_from_file into #response
     * 4. and call the function again
     *
     * @param timestamp
     */
    function getContent(timestamp)
    {
        var time = timestamp;

        $.ajax(
            {
                type: 'GET',
                dataType: 'json',
                url: '/pooling',
                data: {
                    time: time
                },
                success: function(data){
                    time = data['time'];
                    if(typeof  data['data'] != 'undefined'){
                        $('.modal-body p').html(data['data']);
                        $('#myModal').modal("show");
                        //alert('Really pretty modal window ' + data['data']);
                    }

                },
                complete: function(){
                    getContent(time);
                    //console.log(data);
                }
            }
        );
    }

    // initialize jQuery
    $(function() {
        getContent();
    });

</script>
<div class="container">
    <!--<h2>Basic Modal Example</h2>
    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
    -->
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <p>Some text in the modal.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
<?php $this->endPage() ?>

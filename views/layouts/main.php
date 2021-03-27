<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container__header">
        <div class="navbar__left">
            <?= Html::a('Главная', ['/'],['class' => 'header-link']) ?>
        </div>
        <div class="navbar__right">
        <?php 
            if (Yii::$app->user->isGuest){
                echo Html::a('Регистрация', ['/site/signup'], ['class' => 'header-link']);
                echo Html::a('Вход', ['/site/login'], ['class' => 'header-link']);
                
            }else{
                if(Yii::$app->user->identity->getRole() === 1){
                    echo Html::a('Админ-панель', ['/admin'], ['class' => 'header-link']);
                }
                echo Html::beginForm(['/site/logout', 'post'])
                .
                Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'header-btn']
                )
                .
                Html::endForm();
            }
        ?>
        </div>
    </div>

    <div class="container__body">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
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
</body>
</html>
<?php $this->endPage() ?>

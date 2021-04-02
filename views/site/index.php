<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Новости';
?>
<div class="site-index">
    <h3 class="link__title"><?= Html::encode($this->title) ?></h3>
    <div class="news">
    <?php
    print_r($news);
        foreach ($news as $new){
            echo '
                    <div class="new__block">
                        <div class="new__block-link">
                        <a href="new?id='.$new['id'].'">
                            <h1 class="new__title">'.$new['title'].'</h1>
                        </a>
                        <div class="new__date">
                            <span>date create: '.$new['date_news'].'</span>
                        </div>
                        <p class="new__text">'.$new['text'].'</p>
                        </div>
                        <div class="new__comment">Комментариев: '.count($new['comments']).'</div>
                    </div>
                <hr>';
        }
    ?>
    </div>
</div>

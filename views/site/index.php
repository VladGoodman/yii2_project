<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <h3>Записи:</h3>
    <div class="news">
        <h1>Test</h1>
    <?php 
        foreach ($news as $new){
            echo '
                    <div class="new__block">
                        <div class="new__block-link">
                        <a href="new?id='.$new['id'].'">
                            <h1>'.$new['title'].'</h1>
                        </a>
                        <p>'.$new['text'].'</p>
                        </div>
                        <div>
                            <span>date create: '.$new['date_news'].'</span>
                        </div>
                    </div>
                ';
            echo '<hr>';
        }
    ?>
    </div>
</div>

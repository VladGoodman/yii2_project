<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
?>

<?php 
    if(!isset($new)){
        echo '<h3>Новость была удалена или скрыта</h3>';
    }else{
        echo '<h1>'.$new['title'].'</h1>'.
         '<p>Creator: '.$new['creator'].'</a>'.
         '<br>'.
         'Date create: '.$new['date_news'].
        '<hr>'.
        '<p class="news_text">'.$new['text'].'</p>';
        echo '<h3>Comments:</h3>';
        foreach ($comments as $comment){
            if(Yii::$app->request->post('change_comment')==$comment['id']){
                ?>
                <!-- change comment  save-->
                <?php $form_update_comment_save = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <?= $form_update_comment_save->field($model_change_comment, 'text')->textArea() ?>
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'name'=>'save_comment', 'value'=>$model_change_comment->id]) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                <!-- --------------------------- -->
                <!-- cansel -->
                    <?php $form_cansel = ActiveForm::begin(); ?>
                        <?= Html::submitButton('Отмена', ['class' => 'btn btn-success', 'name'=>'cansel']) ?>
                    <?php ActiveForm::end(); ?>
                <!-- --------------------------- -->
                <?php
        
        }else{
            echo $comment['text'].'<br>'.
                'Date: '.$comment['date'].' '.
                '<p>Creator: '.$comment['creator'].'</a>';
            if(!Yii::$app->user->isGuest){
                if(Yii::$app->user->identity->getRole() == 1){
                    ?>
                    <!-- delete comment -->
                    <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= Html::submitButton('Удалиить', ['data-confirm'=>"Подтвердите удаление комментария:",'class' => 'btn btn-danger', 'name'=>'del_comment', 'value'=>$comment['id']]) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                <!-- --------------------------- -->
                <!-- change comment -->
                    <?php $form_change_comment = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= Html::submitButton('Изменить', ['class' => 'btn btn-success', 'name'=>'change_comment', 'value'=>$comment['id']]) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                <!-- --------------------------- -->
                    <?php
                }
            }
        }
        echo '<hr>';
    }
        echo '<hr>';
        if(!Yii::$app->user->isGuest){
        ?>
            <?php $form_create_comment = ActiveForm::begin(); ?>
                <?= $form_create_comment->field($model_create_comment, 'text') ?>
                <div class="form-group">
                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name'=>'create_comment', 'value'=>'true']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        <?php
        }else{
            echo '<h4>Чтобы оставить свой комментарий '.
                Html::a('авторизируйстесь', ['/site/login'])
            .'</h4>';
        }
}
?>
    
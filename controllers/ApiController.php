<?php

namespace app\controllers;
use Yii;
use app\models\Comments;
use yii\web\Controller;


class ApiController extends Controller
{
    public function actionComments(){
        $result = Comments::find()->all();
        return $this->asJson($result);
    }

    public function actionLogin($login, $password){
        return $login;
    }
}

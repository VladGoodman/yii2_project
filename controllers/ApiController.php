<?php

namespace app\controllers;
use Codeception\Step\Comment;
use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\News;
use app\models\User;
use app\models\Comments;
use yii\helpers\ArrayHelper;

class ApiController extends Controller{

    public function actionComment(){
        if (Yii::$app->request->isGet) {
            $news = News::find()->joinWith('comments')->andWhere(['comments.status'=>1])->where(['news.status'=>1])->asArray()->all();
        }elseif (Yii::$app->request->isPost){
            $token = explode(" ", Yii::$app->request->headers['authorization'])[1];
            if(!$token){
                return $this->asJson(['error-info'=>'Вы не авторизированы']);
            }else{
                $news_id = Yii::$app->request->getBodyParam('news_id');
                $text = Yii::$app->request->getBodyParam('text');
                if($news_id and $text){
                    $new = News::findOne($news_id);
                    if (!$new){
                        return $this->asJson(['error-info'=>'Новости с таким id не существует']);
                    }else{
                        $create_comment = new Comments;
                        $create_comment->news_id = +$news_id;
                        $create_comment->text = $text;
                        $create_comment->creator = User::findUsernameByToken($token);
                        $create_comment->save();
                        return $this->asJson(['id'=>+$news_id, 'text'=>$text, 'username'=>User::findUsernameByToken($token)]);
                    }
                }else{
                    return $this->asJson(['error-info'=>'Обязательные поля: news_id и text']);
                }
            }
        }
    //------------!!!!!!!!!!!!!!!!!!!
    //        $token = explode(" ", Yii::$app->request->headers['authorization'])[1];
    //------------!!!!!!!!!!!!!!!!!!!
        return $this->asJson($news);
    }

    public function actionCreateComment(){
        $news = News::find()->joinWith('comments')->andWhere(['comments.status'=>1])->where(['news.status'=>1])->asArray()->all();
        //------------!!!!!!!!!!!!!!!!!!!
        //        $token = explode(" ", Yii::$app->request->headers['authorization'])[1];
        //------------!!!!!!!!!!!!!!!!!!!
        return $this->asJson($news);
    }

    public function actionLogin(){
        if (Yii::$app->request->isPost) {
            $login = Yii::$app->request->getBodyParam('login');
            $password = Yii::$app->request->getBodyParam('password');
            if($login and $password){
                if(User::login($login, $password)){
                    return $this->asJson(['token' => User::login($login, $password)]);
                }else{
                    return $this->asJson(['error-info'=>'Неправильный логин или пароль']);
                }
            }else{
                return $this->asJson(['error-info'=>'Обязательные поля: login и password']);
            }
        }
        return 'No';
    }

    protected function verbs()
    {
        return [
            'comments' => ['GET', 'HEAD'],
            'login' => ['POST'],
        ];
    }
}

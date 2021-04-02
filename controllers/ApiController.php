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

class ApiController extends Controller
{

    public function actionComment()
    {
        if (Yii::$app->request->isGet) {
            $news = News::find()->joinWith('comments')->andWhere(['comments.status' => 1])->where(['news.status' => 1])->asArray()->all();
        } elseif (Yii::$app->request->isPost) {
            $token = explode(" ", Yii::$app->request->headers['authorization'])[1];
            if (!$token) {
                return $this->asJson(['error-info' => 'Вы не авторизированы']);
            } else {
                $news_id = Yii::$app->request->getBodyParam('news_id');
                $text = Yii::$app->request->getBodyParam('text');
                if ($news_id and $text) {
                    $new = News::findOne($news_id);
                    if (!$new) {
                        return $this->asJson(['error-info' => 'Новости с таким id не существует']);
                    } else {
                        $create_comment = new Comments;
                        $create_comment->news_id = +$news_id;
                        $create_comment->text = $text;
                        $create_comment->creator = User::findUsernameByToken($token);
                        $create_comment->save();
                        return $this->asJson(['id' => +$news_id, 'text' => $text, 'username' => User::findUsernameByToken($token)]);
                    }
                } else {
                    return $this->asJson(['error-info' => 'Обязательные поля: news_id и text']);
                }
            }
        } else {
            return $this->asJson(['error-info' => 'Метод недоступен']);
        }
        return $this->asJson($news);
    }


    public function actionLogin()
    {
        if (Yii::$app->request->isPost) {
            $login = Yii::$app->request->getBodyParam('login');
            $password = Yii::$app->request->getBodyParam('password');
            if ($login and $password) {
                if (User::login($login, $password)) {
                    return $this->asJson(['token' => User::login($login, $password)]);
                } else {
                    return $this->asJson(['error-info' => 'Неправильный логин или пароль']);
                }
            } else {
                return $this->asJson(['error-info' => 'Обязательные поля: login и password']);
            }
        }
        return 'No';
    }

    public function actionAdmin()
    {
        $token = explode(" ", Yii::$app->request->headers['authorization'])[1];
        if (Yii::$app->request->isDelete) {
            if(User::getUserByToken($token)->role == 1){
                $id = Yii::$app->request->getBodyParam('id');
                if (!$id) {
                    return $this->asJson(['error-info' => Yii::$app->request->getBodyParam('id')]);
                } else {
                    $comment = Comments::find()->where(['id' => $id])->one();
                    if($comment){
                        $comment->delete();
                        return $this->asJson(['info' => 'Комментарий удален']);
                    }else{
                        return $this->asJson(['error-info' => 'Комментарий с таким id отсуствует']);
                    }
                }
            }else{
                return $this->asJson(['info' => 'Доступ отсутсвует']);
            }
        }elseif (Yii::$app->request->isPut) {
        if(User::getUserByToken($token)->role == 1){
            $id = Yii::$app->request->getBodyParam('id');
            $text = Yii::$app->request->getBodyParam('text');
            $status = Yii::$app->request->getBodyParam('status');
            if (!$id) {
                return $this->asJson(['error-info' => 'Обязательные поля: id']);
            } else {
                $comment = Comments::find()->where(['id' => $id])->one();
                if($text !== null or $status !== null){
                    if($text !== null){
                        $comment->text = $text;
                    }elseif ($status !== null){
                        $comment->status = $status;
                    }
                    $comment->save();
                    return $this->asJson(['Обновлено' => $comment]);
                }
                else{
                    return $this->asJson(['error-info' => 'Обязательное поле text или status']);
                }
            }
        }else{
            return $this->asJson(['info' => 'Доступ отсутсвует']);
        }
    } else {
            return $this->asJson(['error-info' => 'Метод недоступен']);
        }
    }
}

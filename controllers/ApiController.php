<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\News;
use app\models\User;
use app\models\Comments;

class ApiController extends Controller
{
    private function getToken()
    {
        return explode(" ", Yii::$app->request->headers['authorization'])[1];
    }

    private function checkRole()
    {
        if ($this->getToken()) {
            return User::getUserByToken($this->getToken())->role;
        } else {
            return false;
        }
    }

    private function checkNew($id)
    {
        $new = News::findOne($id)->id;
        if ($new) {
            return true;
        } else {
            return $this->asJson(['Info' => 'Новости с таким ID не существует']);
        }
    }

    private function checkComment($id)
    {
        $comment = Comments::findOne($id)->id;
        if ($comment) {
            return true;
        } else {
            return $this->asJson(['Info' => 'Комментария с таким ID не существует']);
        }
    }

    private function checkCreateComment($create_comment)
    {
        if ($create_comment->validate()) {
            $create_comment->save();
            unset($create_comment->id);
            return $this->asJson($create_comment);
        } else {
            return $this->asJson(['Info' => $create_comment->errors]);
        }
    }


    private function createComment()
    {
        $news_id = Yii::$app->request->getBodyParam('news_id');
        $comment_text = Yii::$app->request->getBodyParam('text');
        if ($news_id and $comment_text) {
            if ($this->checkNew($news_id) === true) {
                $create_comment = new Comments;
                $create_comment->news_id = +$news_id;
                $create_comment->text = $comment_text;
                $create_comment->creator = User::findUsernameByToken($this->getToken());
                $this->checkCreateComment($create_comment);
            }
        } else {
            $this->asJson(['Info' => 'Обязательные поля: news_id и text']);
        }
    }

    private function updateComment()
    {
        $id = Yii::$app->request->getBodyParam('id');
        $text = Yii::$app->request->getBodyParam('text');
        $status = Yii::$app->request->getBodyParam('status');
        if (!$id) {
            return $this->asJson(['error-info' => 'Обязательные поля: id, text или status']);
        } else {
            if ($this->checkComment($id) === true) {
                $comment = Comments::find()->where(['id' => $id])->one();
                if ($text !== null) {
                    $comment->text = $text;
                } elseif ($status !== null) {
                    $comment->status = +$status;
                } else {
                    return $this->asJson(['error-info' => 'Обязательное поле text или status']);
                }
                if($comment->save()){
                    return $this->asJson(['Обновлено' => $comment]);
                }else{
                    return $this->asJson(['Ошибки' => $comment->errors]);
                }
            }
        }
    }

    private function deleteComment()
    {
        $comment_id = Yii::$app->request->getBodyParam('id');
        if ($comment_id) {
            if ($this->checkComment($comment_id) === true) {
                Comments::find()->where(['id' => $comment_id])->one()->delete();
                return $this->asJson(['Info' => 'Комментарий под ID ' . $comment_id . ' удален']);
            }
        } else {
            return $this->asJson(['Info' => 'Обязательные поля: id']);
        }
    }


    public function actionComment()
    {
        if (Yii::$app->request->isGet) {
            if ($this->checkRole() === 1) {
                $news = News::find()->joinWith('comments')->asArray()->all();
            } else {
//                TODO: ДЛЯ ПОЛЬЗОВАТЕЛЯ СДЕЛАТЬ ТОЛЬКО СО СТАТСОМ 1
                $news = News::find()->joinWith('comments')->asArray()->all();
            }
            return $this->asJson($news);
        } elseif (Yii::$app->request->isPost) {
            if ($this->checkRole() !== false) {
                $this->createComment();
            } else {
                return $this->asJson(['Info' => 'Вы не авторизированы']);
            }
        } elseif (Yii::$app->request->isDelete) {
            if ($this->checkRole() === 1) {
                $this->deleteComment();
            } else {
                return $this->asJson(['Info' => 'Вы не являетесь администратором']);
            }
        } elseif (Yii::$app->request->isPut) {
            if ($this->checkRole() === 1) {
                $this->updateComment();
            } else {
                return $this->asJson(['Info' => 'Вы не являетесь администратором']);
            }
        } else {
            return $this->asJson(['error-info' => 'Метод недоступен']);
        }
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
        } else {
            return $this->asJson(['error-info' => 'Метод недоступен']);
        }

    }

}

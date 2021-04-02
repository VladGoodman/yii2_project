<?php

namespace app\controllers;
use PhpParser\Comment;
use Psy\Util\Json;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use app\models\Comments;
use yii\web\JsonParser;

class CommentController extends Controller{

    protected function verbs()
    {
        return [
            'index' => ['GET'],
        ];
    }

    public function actionIndex()
    {
        return Json::decode(Comment::find()->all());
    }
}
?>
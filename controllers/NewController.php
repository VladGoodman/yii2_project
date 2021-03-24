<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Request;
use app\models\News;
use app\models\User;
use app\models\Comments;

class NewController extends Controller
{
    public function actionIndex()
    {   
        $request = Yii::$app->request;
        $new = News::find()
            ->where(['id'=>$request->get('id'),'status'=>1])->one();
        $comments = Comments::find()
            ->where(['news_id'=>$request->get('id'), 'status' => 1])
            ->all();
        // ------------------ CREATE COMMENT --------------------
        $model_create_comment = new Comments;
        if($request->post('create_comment')){
            if ($model_create_comment->load($request->post()) && $model_create_comment->validate()) {
                $model_create_comment->creator = Yii::$app->user->identity->username;
                $model_create_comment->news_id = $request->get('id');
                $model_create_comment->save();
                return $this->refresh();
            }
        }
        // ------------------ DELETE COMMENT --------------------
        if($request->post('del_comment')){
            $comment = Comments::findOne($request->post('del_comment'));
            $comment->status = 0;
            $comment->save();
            return $this->refresh();
        }
        // ------------------ CHANGE COMMENT --------------------
        if($request->post('change_comment')){
            $model_change_comment = Comments::findOne($request->post('change_comment'));
        }
        if($request->post('save_comment')){
            $model_change_comment = Comments::findOne($request->post('save_comment'));
            if($model_change_comment->load($request->post()) && $model_change_comment->validate() && $model_change_comment->update()){
                return $this->refresh();
            }   
        }
        
        // ------------------ CANSEL FORM --------------------
        if($request->post('cansel')){
            return $this->refresh();
        }
        // ------------------ DELETE NEW --------------------
        if($request->post('del_new')){
            $model_delele_new = News::findOne($request->post('del_new'));
            $model_delele_new->status = 0;
            $model_delele_new->save();
            return $this->refresh();
        }
        return $this->render('new', compact('new','comments','model','model_create_comment','model_change_comment','model_delele_comment'));
    }
    
}

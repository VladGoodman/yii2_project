<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\Comments;

class News extends ActiveRecord
{
    public static function tableName()
   {
       return 'news';
   }

   public function rules()
   {
       return [
           [['title', 'text'], 'required'],
           [['text'], 'string'],
           [['date_news'], 'safe'],
           [['status'], 'number', 'max'=>1,'min'=>0],
           [['title'], 'string', 'max' => 40],
       ];
   }
   
   public function attributeLabels()
   {
       return [
           'title' => 'Title',
           'text' => 'Text',
       ];
   }

    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['news_id' => 'id']);
    }
}

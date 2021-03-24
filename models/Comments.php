<?php

namespace app\models;

use yii\db\ActiveRecord;

class Comments extends ActiveRecord
{
    public static function tableName()
    {
        return 'comments';
    }

    public function rules()
    {
        return [
            [['text',], 'required'],
            [['text'], 'string', 'max' => 100, 'min'=>10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => 'Your comment',
        ];
    }
}

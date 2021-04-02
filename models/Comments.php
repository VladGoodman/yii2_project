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
            [['text',], 'required', 'message'=>'Поле не заполнено'],
            [['text'], 'string', 'max' => 100, 'min'=>10, 'message'=>'Поле должно содержать не более 100 и не менее 10 символов', 'tooLong'=>'Поле должно содержать не более 100 и не менее 10 символов', 'tooShort'=>'Поле должно содержать не более 100 и не менее 10 символов'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'text' => 'Ваш комментарий',
        ];
    }
}

<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


class User extends ActiveRecord  implements IdentityInterface
{
    public static function tableName(){
        return 'user';
    }



    public static function findIdentity($id){
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return self::findOne(['accessToken' => $token]);
    }

    public static function findByUsername($username){
        return self::findOne(['username'=> $username]);
    }

    public function getId(){
        return $this->id;
    }

    public function getRole(){
        return $this->role;
    }

    public function getAuthKey(){
        return $this->authKey;
    }
    

    public function validateAuthKey($authKey){
        return $this->authKey === $authKey;
    }

    public function validatePassword($password){
        return $this->password === $password;
    }
    
}

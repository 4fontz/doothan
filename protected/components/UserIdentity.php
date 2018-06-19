<?php
//require_once(Yii::getPathOfAlias('vendor').'/password.php');
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $_id;
    public function authenticate()
    {
        $record=Admin::model()->find("username='".$this->username."'");
        
        //echo "<pre>";print_r($record);die;
        if($record===null)
        {
            $this->_id='user Null';
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        }
        
        else if($record->password!==md5($this->password))            // here I compare db password with passwod field
        {
            $this->_id=$this->username;
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        }else{
            $this->_id=$record->id;
            $this->username=$record->username;
            $this->errorCode=self::ERROR_NONE;
        }
        //print_r($this->errorCode);die;
       // return !$this->errorCode;
        return $this->errorCode==self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
        //$this->setState('name', $record->name);
    }
}
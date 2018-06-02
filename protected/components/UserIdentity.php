<?php
require_once(Yii::getPathOfAlias('vendor').'/password.php');
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
    public function authenticate()
    {
        $model = Users::model()->find('username = :username OR email = :username AND status = 2', array(':username' => $this->username));       
        if ($model) {
            if (password_verify($this->password, $model->password)) {
                //If it is a valid password
                $this->_id = $model->id;
                $this->setState('userData', $model);
                return true;
            }
        }

        return false;
    }

    public function getId()
    {
//        return $this->_id;
        return 1;
    }
}
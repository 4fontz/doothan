<?php

require_once(Yii::getPathOfAlias('vendor') . '/facebook/facebook.php');
//require_once(Yii::getPathOfAlias('extensions') . '/facebook/facebook.php');
require_once(Yii::getPathOfAlias('ext') . '/google/Google_Client.php');
require_once(Yii::getPathOfAlias('ext') . '/google/contrib/Google_Oauth2Service.php');
use Rhumsaa\Uuid\Uuid;
//require_once(Yii::getPathOfAlias('ext') . '/smtpmail/PHPMailer.php');
/**  class SiteController */
class OauthController extends Controller {

    /**
     * This is the action to handle external exceptions.
     */

    public $layout = '';
    public function actionToken() {
        //echo "here";die;
        if (!($clientDetails = OauthClients::validateClient($_POST['client_id'], $_POST['client_secret']))) {
            //throw new CHttpException(403, 'invalid_client');
            $result = array('message'=>'invalid_client');
            $this->renderJSON($result);
        }
        $userModel = new Users;
        $tokenModel = new OauthTokens;
        if (isset($_POST['username']) && isset($_POST['password'])) {
            if ($userDetails = $userModel->validateUserCredentials($_POST['username'], $_POST['password'], $memberType)) {
                $tokenExstChk     = OauthTokens::model()->findAllByAttributes(array('user_id' => $userDetails['id']));
                foreach ($tokenExstChk as $tokenExist) {
                    $tknExstChek      = OauthTokens::getTokenInfo($tokenExist['access_token']);
                    if ($tknExstChek) {
                        $tokenExist->expires    = 0;
                        $tokenExist->save();
                    }
                }
                
                if ($tokenDetails = $tokenModel->createTokenForUser(
                    array(
                        'userId' => $userDetails['id'],
                        'clientId' => $_POST['client_id'],
                        'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                        'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                        'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                        //'first_flag'=>1
                    )
                    )) {
                    $message = $userDetails['first_name'] . " " . $userDetails['last_name'] . " Logged In ";
                    Common::activityLog($userDetails['id'], 'LOG IN', $message, date('Y-m-d H:i:s'));
                    $user = Users::model()->findByPk($userDetails['id']);
                    $user->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                    $user->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                    $user->save();
                    $this->renderJSON($tokenDetails);
                } else {
                    ///throw new CHttpException(400, 'token_error');
                    $result = array('message'=>'token_error');
                    $this->renderJSON($result);
                }
            } else {
                //throw new CHttpException(403, 'please provide valid credentials for login ');
                $model = Users::model()->findByAttributes(array('email'=>$_POST['username']));
                if($model){
                    //echo $model['id'];die;
                    if($model['member_type']=="requester"){
                        if($model['status']==1 && $model['login_state']==0 && $model['account_status']=="APPROVED" && $model['aadhar']==NULL && $model['photo_id']==NULL) {
                            $Address_model = UserAddress::model()->findByAttributes(array('user_id'=>$model['id']));
                            if($Address_model){
                                $Address_model->delete();
                            }
                            $model->delete();
                            $this->renderJSON(array('login_status'=>'error','message'=>'User does not exist'));
                        }else{
                            $this->renderJSON(array('login_status'=>'error','message'=>'Incorrect username or password'));
                        }
                    }else{
                        if($model['status']==1 && $model['login_state']==0 && $model['account_status']=="DOCUMENTS_PENDING" && $model['aadhar']==NULL && $model['photo_id']==NULL) {
                            $Address_model = UserAddress::model()->findByAttributes(array('user_id'=>$model['id']));
                            $Address_model->delete();
                            $model->delete();
                            $this->renderJSON(array('login_status'=>'error','message'=>'User does not exist'));
                        }else{
                            $this->renderJSON(array('login_status'=>'error','message'=>'Incorrect username or password'));
                        }
                    }
                }else{
                    $this->renderJSON(array('login_status'=>'error','message'=>'User does not exist'));
                }
            }
        } elseif (isset($_POST['facebook_token'])) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
            //print_r($_POST);die;
            try {
                $facebook = new Facebook(
                        array(
                    'appId' => Yii::app()->params['facebookAppId'],
                    'secret' => Yii::app()->params['facebookAppSecret']
                        )
                );
                $facebook->setAccessToken($_POST['facebook_token']);

                $userProfile = $facebook->api('/me?fields=email,name,first_name,last_name,gender');
            } catch (Exception $e) {
                //throw new CHttpException(400, 'facebook login failed');
                $result = array('message'=>'facebook login failed');
                $this->renderJSON($result);
            }
            //echo "<pre>";
            //print_r($userProfile);die;
            if (isset($userProfile['email']) && $userProfile['email'] != '') {
                //Check whther a user exists with the same email
                $userModel = Users::model()->findByAttributes(array('email' => $userProfile['email']));
                if (!$userModel) {
                    $userModel = new Users;
                    $userModel->scenario = 'socialLogin';
                    $first_name = isset($userProfile['first_name']) ? $userProfile['first_name'] : '';
                    $last_name = isset($userProfile['last_name']) ? $userProfile['last_name'] : '';
                    $userModel->first_name = $first_name;
                    $userModel->last_name = $last_name;
                    $userModel->email = $userProfile['email'];
                    $userModel->username = $userProfile['email'];
                    $userModel->facebook_image = 'https://graph.facebook.com/' . $userProfile['id'] . '/picture';
                    $userModel->status = 1;
                    $userModel->member_type = 'requester';
                    $userModel->account_status = 'APPROVED';
                    $userModel->login_state = '0';
                    $userModel->gender = $userProfile['gender'];
                    $userModel->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                    $userModel->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                    //if($gettingresponse['role']=="requester"){
                    $userModel->role_change_to = "0";
                    //}else if($gettingresponse['role']=="doothan"){
                      //  $model->role_change_to = "1";
                    //}else{
                      //  $model->role_change_to = "2";
                    //}
                    if ($userModel->save()) {
                        
                        if ($tokenDetails = $tokenModel->createTokenForUser(
                                array(
                                    'userId' => $userModel->id,
                                    'clientId' => $_POST['client_id'],
                                    'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                                    'facebookToken' => $_POST['facebook_token'],
                                    'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                                    'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                                    //'first_flag'=>0
                                )
                                )) {
                            
                            /*$mail = new SesMailer();
                            $mail->setView('forgotpassword');
                            $mail->setData(
                                array(
                                    'email' => $userModel->email,
                                    'name' => $model->first_name . ' ' . $model->last_name
                                )
                                );
                            
                            $mail->setFrom(Yii::app()->params['fromMail'], Yii::app()->params['fromName']);
                            $mail->setTo($model->email, $model->first_name);
                            $mail->setSubject('[DOOTHAN] : Forgot Password');
                            if ($mail->Send()) {
                                $emailStatus = true;
                            }else{
                                $emailStatus = false;
                            }*/
                            $message = $first_name . " " . $last_name . " Registred via Facebook";
                            Common::activityLog($userModel->id, 'REGISTER', $message, date('Y-m-d H:i:s'));
                            $this->renderJSON($tokenDetails);
                        } else {

                            //throw new CHttpException(400, 'token_error');
                            $result = array('message'=>'token_error');
                            $this->renderJSON($result);
                        }
                    } else {
                        $userModel = array();
                        $validationError['validation_error'] = true;
                        foreach ($userModel->errors as $attribute => $attr_errors) {
                            foreach ($attr_errors as $attr_error) {
                                $validationError[$attribute] = $attr_error;
                            }
                        }
                        $this->renderJSON($validationError);
                        $this->refresh(true);
                    }
                } else {
                    $userModel->facebook_image = 'https://graph.facebook.com/' . $userProfile['id'] . '/picture';
                    $userModel->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                    $userModel->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                    $userModel->save();
                    $tokenExstChk     = OauthTokens::model()->findAllByAttributes(array('user_id' => $userModel->id));
                    foreach ($tokenExstChk as $tokenExist) {
                        $tknExstChek      = OauthTokens::getTokenInfo($tokenExist['access_token']);
                        if ($tknExstChek) {
                            $tokenExist->expires    = 0;
                            $tokenExist->save();
                        }
                    }
                    if($userModel['member_type']=="requester"){
                        $author_condition = "APPROVED";
                    }else{
                        $author_condition = "DOCUMENTS_PENDING";
                    }
                    if($userModel['status']==1 && $userModel['login_state']==0 && $userModel['account_status']==$author_condition && $userModel['aadhar']==NULL && $userModel['photo_id']==NULL) {
                        $Address_model = UserAddress::model()->findByAttributes(array('user_id'=>$userModel['id']));
                        if($Address_model){
                            $Address_model->delete();
                        }
                        $userModel->delete();
                        $userModel = new Users;
                        $userModel->scenario = 'socialLogin';
                        $first_name = isset($userProfile['first_name']) ? $userProfile['first_name'] : '';
                        $last_name = isset($userProfile['last_name']) ? $userProfile['last_name'] : '';
                        $userModel->first_name = $first_name;
                        $userModel->last_name = $last_name;
                        $userModel->email = $userProfile['email'];
                        $userModel->username = $userProfile['email'];
                        $userModel->facebook_image = 'https://graph.facebook.com/' . $userProfile['id'] . '/picture';
                        $userModel->status = 1;
                        $userModel->member_type = 'requester';
                        $userModel->account_status = 'APPROVED';
                        $userModel->login_state = '0';
                        $userModel->gender = $userProfile['gender'];
                        $userModel->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                        $userModel->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                        $userModel->role_change_to = "0";
                        if ($userModel->save()) {
                            if ($tokenDetails = $tokenModel->createTokenForUser(
                                array(
                                    'userId' => $userModel->id,
                                    'clientId' => $_POST['client_id'],
                                    'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                                    'facebookToken' => $_POST['facebook_token'],
                                    'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                                    'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                                )
                                )) {
                                    
                                    /*$mail = new SesMailer();
                                     $mail->setView('forgotpassword');
                                     $mail->setData(
                                     array(
                                     'email' => $userModel->email,
                                     'name' => $model->first_name . ' ' . $model->last_name
                                     )
                                     );
                                     
                                     $mail->setFrom(Yii::app()->params['fromMail'], Yii::app()->params['fromName']);
                                     $mail->setTo($model->email, $model->first_name);
                                     $mail->setSubject('[DOOTHAN] : Forgot Password');
                                     if ($mail->Send()) {
                                     $emailStatus = true;
                                     }else{
                                     $emailStatus = false;
                                     }*/
                                    $message = $first_name . " " . $last_name . " Registred via Facebook";
                                    Common::activityLog($userModel->id, 'REGISTER', $message, date('Y-m-d H:i:s'));
                                    $this->renderJSON($tokenDetails);
                                } else {
                                    
                                    //throw new CHttpException(400, 'token_error');
                                    $result = array('message'=>'token_error');
                                    $this->renderJSON($result);
                                }
                        } else {
                            $userModel = array();
                            $validationError['validation_error'] = true;
                            foreach ($userModel->errors as $attribute => $attr_errors) {
                                foreach ($attr_errors as $attr_error) {
                                    $validationError[$attribute] = $attr_error;
                                }
                            }
                            $this->renderJSON($validationError);
                            $this->refresh(true);
                        }
                    }else{
                        if ($tokenDetails = $tokenModel->createTokenForUser(
                                array(
                                    'userId' => $userModel->id,
                                    'clientId' => $_POST['client_id'],
                                    'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                                    'facebookToken' => $_POST['facebook_token'],
                                    'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                                    'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                                    //'first_flag'=>1
                                )
                                )) {
                            $message = $userModel->first_name . " " . $userModel->last_name . " Logged In via Facebook";
                            Common::activityLog($userModel->id, 'LOG IN', $message, date('Y-m-d H:i:s'));
                            $this->renderJSON($tokenDetails);
                        } else {
                            //throw new CHttpException(400, 'token_error');
                            $result = array('message'=>'token_error');
                            $this->renderJSON($result);
                        }
                    }
                }
            } else {
                //throw new CHttpException(403, 'email not found try register with email');
                $result = array('message'=>'email not found try register with email');
                $this->renderJSON($result);
            }
        } elseif (isset($_POST['google_token'])) {
            try {
                $ch = curl_init('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $_POST['google_token']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $userData = curl_exec($ch);
//                
//                  stop('https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $_POST['google_token']);
                if ($userData === FALSE) {
                    //throw new CHttpException(400, 'Authentication failed');
                    $result = array('message'=>'Authentication failed');
                    $this->renderJSON($result);
                }
                $gpUserProfile = json_decode($userData);
                //echo "<pre>";print_r($gpUserProfile);die;
                if (isset($gpUserProfile->email) && $gpUserProfile->email != '' && isset($_POST['email']) && $_POST['email'] != '') {
                    if ($gpUserProfile->email == $_POST['email']) {
                        //Check whther a user exists with the same email
                        $userModel = Users::model()->findByAttributes(array('email' => $gpUserProfile->email));
                        if (!$userModel) {
                            $userModel = new Users;
                            $userModel->scenario = 'socialLogin';
                            $first_name = isset($_POST['firstname']) ? $_POST['firstname'] : '';
                            $last_name = isset($_POST['lastname']) ? $_POST['lastname'] : '';
                            $userModel->first_name = $first_name;
                            $userModel->last_name = $last_name;
                            $userModel->email = $_POST['email'];
                            $userModel->username = $_POST['email'];
                            $userModel->facebook_image = '';
                            $userModel->status = 1;
                            $userModel->member_type = 'requester';
                            $userModel->account_status = 'APPROVED';
                            $userModel->login_state = '0';
//                    $userModel->gender = $gpUserProfile['gender'];
                            $userModel->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                            $userModel->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                            $userModel->role_change_to = "0";
                            if ($userModel->save()) {
                                if ($tokenDetails = $tokenModel->createTokenForUser(
                                        array(
                                            'userId' => $userModel->id,
                                            'clientId' => $_POST['client_id'],
                                            'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                                            'googleToken' => $_POST['google_token'],
                                            'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                                            'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                                           // 'first_flag'=>0
                                        )
                                        )) {
                                    $message = $userModel->first_name . " " . $userModel->last_name . " Registred via Google";
                                    Common::activityLog($userModel->id, 'REGISTER', $message, date('Y-m-d H:i:s'));
                                    $this->renderJSON($tokenDetails);
                                } else {

                                    //throw new CHttpException(400, 'token_error');
                                    $result = array('message'=>'token_error');
                                    $this->renderJSON($result);
                                }
                            } else {
                                $userModel = array();
                                $validationError['validation_error'] = true;
                                foreach ($userModel->errors as $attribute => $attr_errors) {
                                    foreach ($attr_errors as $attr_error) {
                                        $validationError[$attribute] = $attr_error;
                                    }
                                }
                                $this->renderJSON($validationError);
                                $this->refresh(true);
                            }
                        } else {
                            $userModel->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                            $userModel->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                            $userModel->save();
                            $tokenExstChk     = OauthTokens::model()->findAllByAttributes(array('user_id' => $userModel->id));
                            foreach ($tokenExstChk as $tokenExist) {
                                $tknExstChek      = OauthTokens::getTokenInfo($tokenExist['access_token']);
                                if ($tknExstChek) {
                                    $tokenExist->expires    = 0;
                                    $tokenExist->save();
                                }
                            }
                            if($userModel['member_type']=="requester"){
                                $author_condition = "APPROVED";
                            }else{
                                $author_condition = "DOCUMENTS_PENDING";
                            }
                            if($userModel['status']==1 && $userModel['login_state']==0 && $userModel['account_status']==$author_condition && $userModel['aadhar']==NULL && $userModel['photo_id']==NULL) {
                                $Address_model = UserAddress::model()->findByAttributes(array('user_id'=>$userModel['id']));
                                if($Address_model){
                                    $Address_model->delete();
                                }
                                $userModel->delete();
                                $userModel = new Users;
                                $userModel->scenario = 'socialLogin';
                                $first_name = isset($_POST['firstname']) ? $_POST['firstname'] : '';
                                $last_name = isset($_POST['lastname']) ? $_POST['lastname'] : '';
                                $userModel->first_name = $first_name;
                                $userModel->last_name = $last_name;
                                $userModel->email = $_POST['email'];
                                $userModel->username = $_POST['email'];
                                $userModel->facebook_image = '';
                                $userModel->status = 2;
                                $userModel->member_type = 'requester';
                                $userModel->account_status = 'APPROVED';
                                $userModel->login_state = 0;
                                //                    $userModel->gender = $gpUserProfile['gender'];
                                $userModel->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                                $userModel->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                                $userModel->role_change_to = "0";
                                if ($userModel->save()) {
                                    if ($tokenDetails = $tokenModel->createTokenForUser(
                                        array(
                                            'userId' => $userModel->id,
                                            'clientId' => $_POST['client_id'],
                                            'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                                            'googleToken' => $_POST['google_token'],
                                            'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                                            'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                                            // 'first_flag'=>0
                                        )
                                        )) {
                                            $message = $userModel->first_name . " " . $userModel->last_name . " Registred via Google";
                                            Common::activityLog($userModel->id, 'REGISTER', $message, date('Y-m-d H:i:s'));
                                            $this->renderJSON($tokenDetails);
                                        } else {
                                            
                                            //throw new CHttpException(400, 'token_error');
                                            $result = array('message'=>'token_error');
                                            $this->renderJSON($result);
                                        }
                                } else {
                                    $userModel = array();
                                    $validationError['validation_error'] = true;
                                    foreach ($userModel->errors as $attribute => $attr_errors) {
                                        foreach ($attr_errors as $attr_error) {
                                            $validationError[$attribute] = $attr_error;
                                        }
                                    }
                                    $this->renderJSON($validationError);
                                    $this->refresh(true);
                                }
                            }else{ 
                                if ($tokenDetails = $tokenModel->createTokenForUser(
                                    array(
                                        'userId' => $userModel->id,
                                        'clientId' => $_POST['client_id'],
                                        'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                                        'googleToken' => $_POST['google_token'],
                                        'device_id' => isset($_POST['device_token']) ? $_POST['device_token'] : '',
                                        'device_type' => isset($_POST['device_type']) ? $_POST['device_type'] : '',
                                        //'first_flag'=>1
                                    )
                                    )) {
                                        $message = $userModel->first_name . " " . $userModel->last_name . " Logged In via Google";
                                        Common::activityLog($userModel->id, 'LOG IN', $message, date('Y-m-d H:i:s'));
                                        $this->renderJSON($tokenDetails);
                                    } else {
                                        //throw new CHttpException(400, 'token_error');
                                        $result = array('message'=>'token_error');
                                        $this->renderJSON($result);
                                    }
                            }
                        }
                    } else {
                        //throw new CHttpException(400, 'User authentication failed');
                        $result = array('message'=>'User authentication failed');
                        $this->renderJSON($result);
                    }
                } else {
                    //throw new CHttpException(403, 'email not found try register with email');
                    $result = array('message'=>'Email not found try register with email');
                    $this->renderJSON($result);
                }
            } catch (Exception $e) {
                //throw new CHttpException(400, 'invalid id token');
                $result = array('message'=>'Invalid id token');
                $this->renderJSON($result);
            }
        } else {
            //throw new CHttpException(403, 'please provide valid credentials');
            $result = array('message'=>'Please provide valid credentials');
            $this->renderJSON($result);
        }
    }

    /*public function actionRegister() { 
        $this->layout   = false;
        //error_reporting(E_ALL);
        //ini_set('display_errors', 'On');
        if (!($clientDetails = OauthClients::validateClient($_POST['client_id'], $_POST['client_secret']))) {
            //throw new CHttpException(403, 'invalid_client');
            $result = array('message'=>'Invalid client');
            $this->renderJSON($result);
        }
        $model = Users::model()->findByAttributes(array('email' => $_POST['email']));
        if ($model) {
                $this->renderJSON(array('status' => false, 'message' => 'Email Id Already Exist'));
                $this->refresh(true);
        } else {
            $model = Users::model()->findByAttributes(array('phone' => $_POST['mobile']));
            if ($model) {
                $this->renderJSON(array('status' => false, 'message' => 'Mobile Already Exist'));
                $this->refresh(true);
            }else{
                $transaction=Yii::app()->db->beginTransaction();
                $model = new Users;
                $model->scenario = 'registration';
                $model->first_name = $_POST['full_name'];
                $model->email = $_POST['email'];
                $model->username = $_POST['email'];
                $model->country_code = (isset($_POST['country_code'])) ? $_POST['country_code'] : '+91';
                $model->phone = $_POST['mobile'];
                $model->password = $_POST['password'];
                $model->profession = $_POST['profession'];
                $model->gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';
                $model->dob = (isset($_POST['dob'])) ? $_POST['dob'] : '';
                $model->member_type = $_POST['role'];
                if($_POST['role']=="doothan" || $_POST['role']=="dropbox"){
                    $model->account_status = 'DOCUMENTS_PENDING';
                }else{
                    $model->account_status = 'APPROVED';
                }
                if($_POST['role']=="requester"){
                    $model->role_change_to = "0";
                }else if($_POST['role']=="doothan"){
                    $model->role_change_to = "1";
                }else{
                    $model->role_change_to = "2";
                }
                $model->login_state = 0;
                $model->status = 1;
                $model->device_token = (isset($_POST['device_token'])) ? $_POST['device_token'] : '';
                $model->device_type = (isset($_POST['device_type'])) ? $_POST['device_type'] : '';
                $model->verification_code = substr(md5($model->email . mt_rand() . microtime()), 0, 99);
                $model->invite_code = substr(md5($model->email . mt_rand() . microtime()), 0, 7);
                $address = new UserAddress();
                $address->address = (isset($_POST['address'])) ? $_POST['address'] : '';
                $address->city = (isset($_POST['city'])) ? $_POST['city'] : '';
                $address->postal_code = (isset($_POST['pincode'])) ? $_POST['pincode'] : '';
                $address->state = (isset($_POST['state'])) ? $_POST['state'] : '';
                $address->country = (isset($_POST['country'])) ? $_POST['country'] : '';
                if ($address->validate() && $model->validate()) {
                    $model->save();
                    $address->user_id = $model->id;
                    $address->save();

                    $location = $address->city . ' ' . $address->state . ' ' . $address->postal_code;
                    $geoLocation = Helper::getCoordinates($location);
                    
                    if ($geoLocation['result'] == null) {
                        $location = $address->city;
                        $geoLocation = Helper::getCoordinates($location);
                    }

                    $address->geo_location = ($geoLocation['lat'] != '' && $geoLocation['long'] != '') ? new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $geoLocation['lat'] . ' ' . $geoLocation['long'] . ')')) : '';
                    $address->place_id = isset($geoLocation['place_id']) ? $geoLocation['place_id'] : '';
                    $address->created = date('Y-m-d H:i:s');
                    $address->is_default = 1;
                    $address->status = 1;
                    $address->save();
                    $verifyNumber = PhoneVerifierProcessor::verifyRequest($_POST['country_code'] . $_POST['mobile']);
                    if (!$verifyNumber['status']) {
                        $transaction->rollback();
                        $this->renderJSON($verifyNumber);
                        $this->refresh(true);
                    } else {
                        $settings = Settings::model()->find();
                        $mail = Yii::app()->Smtpmail;
                        $mail->SetFrom($settings->from_mail,$settings->from_name);
                        $mail->Subject = 'DOOTHAN : User Registration';
                        $name = $model->first_name . ' ' . $model->last_name;
                        $mail->MsgHTML($this->render('/mail/user_registration',array('name' => $name ),true));
                        $mail->AddAddress($model->email);
                        $mail->AddReplyTo($settings->from_mail,$settings->from_name);
                        //$mail->AddCC($addcc);
                        $mail->SMTPDebug = 0;
                        $mail->SMTPSecure = 'tls';
                        $response_status='Success';
                        //$mail->Debugoutput = 'html';
                        if (!$mail->Send()) {
                            $response_status=$mail->ErrorInfo;
                        }
                        $transaction->commit();
                        $result = array('status' => $verifyNumber['status'], 'request_id' => $verifyNumber['request_id'], 'user_id' => $model->id, 'message' => 'success','email_response_status'=>$response_status);
                        $this->renderJSON($result);
                        $this->refresh(true);
                    }



                } else {
                    $validationError = array();
                    $validationError['validation_error'] = true;
                    foreach ($model->errors as $attribute => $attr_errors) {
                        foreach ($attr_errors as $attr_error) {
                            $validationError[$attribute] = $attr_error;
                        }
                    }
                    foreach ($address->errors as $attribute => $attr_errors) {
                        foreach ($attr_errors as $attr_error) {
                            $validationError[$attribute] = $attr_error;
                        }
                    }
                    $this->renderJSON($validationError);
                    $this->refresh(true);
                }
            }
        }
    }*/
    
    public function actionRegister() {
        $this->layout   = false;
        //error_reporting(E_ALL);
        //ini_set('display_errors', 'On');
        if (!($clientDetails = OauthClients::validateClient($_POST['client_id'], $_POST['client_secret']))) {
            $result = array('message'=>'Invalid client');
            $this->renderJSON($result);
        }
        $model = Users::model()->findByAttributes(array('email' => $_POST['email']));
        $gettingresponse=array(
            'full_name'=>$_POST['full_name'],
            'email'=>$_POST['email'],
            'country_code'=>$_POST['country_code'],
            'mobile'=>$_POST['mobile'],
            'password'=>$_POST['password'],
            'profession'=>$_POST['profession'],
            'gender'=>$_POST['gender'],
            'dob'=>$_POST['dob'],
            'role'=>$_POST['role'],
            'device_token'=>$_POST['device_token'],
            'device_type'=>$_POST['device_type'],
            'address'=>$_POST['address'],
            'city'=>$_POST['city'],
            'pincode'=>$_POST['pincode'],
            'state'=>$_POST['state'],
            'country'=>$_POST['country'],
        );
        if ($model) {
            if($model['member_type']=="requester"){
                $author_condition = "APPROVED";
            }else{
                $author_condition = "DOCUMENTS_PENDING";
            }
            if($model['status']==1 && $model['login_state']==0 && $model['account_status']==$author_condition && $model['aadhar']==NULL && $model['photo_id']==NULL) {
                $Address_model = UserAddress::model()->findByAttributes(array('user_id'=>$model['id']));
                $Address_model->delete();
                $model->delete();
                $this->actionRegistrationByPass($gettingresponse);
            }else{
                $this->renderJSON(array('login_status'=>'error','message'=>'Email Id Already Exist'));
                $this->refresh(true);
            }
        } else {
            $model = Users::model()->findByAttributes(array('phone' => $_POST['mobile']));
            if ($model) {
                if($model['member_type']=="requester"){
                    $author_condition = "APPROVED";
                }else{
                    $author_condition = "DOCUMENTS_PENDING";
                }
                if($model['status']==1 && $model['login_state']==0 && $model['account_status']==$author_condition && $model['aadhar']==NULL && $model['photo_id']==NULL) {
                    $Address_model = UserAddress::model()->findByAttributes(array('user_id'=>$model['id']));
                    $Address_model->delete();
                    $model->delete();
                    $this->actionRegistrationByPass($gettingresponse);
                }else{
                    $this->renderJSON(array('login_status'=>'error','message'=>'Mobile Already Exist'));
                    $this->refresh(true);
                }
            }else{
                $this->actionRegistrationByPass($gettingresponse);
            }
        }
    }
    
    public function actionRegistrationByPass($gettingresponse){
        $transaction=Yii::app()->db->beginTransaction();
        $model = new Users;
        $model->scenario = 'registration';
        $model->first_name = $gettingresponse['full_name'];
        $model->email = $gettingresponse['email'];
        $model->username = $gettingresponse['email'];
        $model->country_code = (isset($gettingresponse['country_code'])) ? $gettingresponse['country_code'] : '+91';
        $model->phone = $gettingresponse['mobile'];
        $model->password = $gettingresponse['password'];
        $model->profession = $gettingresponse['profession'];
        $model->gender = (isset($gettingresponse['gender'])) ? $gettingresponse['gender'] : '';
        $model->dob = (isset($gettingresponse['dob'])) ? $gettingresponse['dob'] : '';
        $model->member_type = $gettingresponse['role'];
        if($gettingresponse['role']=="doothan" || $gettingresponse['role']=="dropbox"){
            $model->account_status = 'DOCUMENTS_PENDING';
        }else{
            $model->account_status = 'APPROVED';
        }
        if($gettingresponse['role']=="requester"){
            $model->role_change_to = "0";
        }else if($gettingresponse['role']=="doothan"){
            $model->role_change_to = "1";
        }else{
            $model->role_change_to = "2";
        }
        $model->login_state = 0;
        $model->status = 1;
        $model->device_token = (isset($gettingresponse['device_token'])) ? $gettingresponse['device_token'] : '';
        $model->device_type = (isset($gettingresponse['device_type'])) ? $gettingresponse['device_type'] : '';
        $model->verification_code = substr(md5($model->email . mt_rand() . microtime()), 0, 99);
        $model->invite_code = substr(md5($model->email . mt_rand() . microtime()), 0, 7);
        $address = new UserAddress();
        $address->address = (isset($gettingresponse['address'])) ? $gettingresponse['address'] : '';
        $address->city = (isset($gettingresponse['city'])) ? $gettingresponse['city'] : '';
        $address->postal_code = (isset($gettingresponse['pincode'])) ? $gettingresponse['pincode'] : '';
        $address->state = (isset($gettingresponse['state'])) ? $gettingresponse['state'] : '';
        $address->country = (isset($gettingresponse['country'])) ? $gettingresponse['country'] : '';
        if ($address->validate() && $model->validate()) {
            $model->save();
            $oathToken = new OauthTokens;
            $oathToken->user_id = $model->id;
            $oathToken->client_id = "android";
            $oathToken->access_token = Uuid::uuid5(Uuid::uuid4(), $model->id)->toString();;
            $oathToken->created = date('Y-m-d H:i:s');
            $oathToken->expires = time() + Yii::app()->params['accessTokenExpiry'];
            $oathToken->device_id = (isset($gettingresponse['device_token'])) ? $gettingresponse['device_token'] : '';
            $oathToken->device_type = isset($_POST['device_type']) ? $_POST['device_type'] : '';
            $oathToken->save(false);
            $address->user_id = $model->id;
            $address->save();
            
            $location = $address->city . ' ' . $address->state . ' ' . $address->postal_code;
            $geoLocation = Helper::getCoordinates($location);
            
            if ($geoLocation['result'] == null) {
                $location = $address->city;
                $geoLocation = Helper::getCoordinates($location);
            }
            
            $address->geo_location = ($geoLocation['lat'] != '' && $geoLocation['long'] != '') ? new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $geoLocation['lat'] . ' ' . $geoLocation['long'] . ')')) : '';
            $address->place_id = isset($geoLocation['place_id']) ? $geoLocation['place_id'] : '';
            $address->created = date('Y-m-d H:i:s');
            $address->is_default = 1;
            $address->status = 1;
            $address->save();
            $verifyNumber = PhoneVerifierProcessor::verifyRequest($gettingresponse['country_code'] . $gettingresponse['mobile']);
            //echo $verifyNumber['status'];die;
            if (!$verifyNumber['status']) {
                $transaction->rollback();
                $this->renderJSON($verifyNumber);
                $this->refresh(true);
            } else {
                $settings = Settings::model()->find();
                /*$mail = Yii::app()->Smtpmail;
                $mail->SetFrom($settings->from_mail,$settings->from_name);
                $mail->Subject = 'DOOTHAN : User Registration';
                $mail->MsgHTML($this->render('/mail/user_registration',array('name' => $name,'type'=> $model->member_type),true));
                $mail->AddAddress($model->email);
                $mail->AddReplyTo($settings->from_mail,$settings->from_name);
                //$mail->AddCC($addcc);
                $mail->SMTPDebug = 0;
                $mail->SMTPSecure = 'tls';*/
                $mails = new SesMailer();
                $name = $model->first_name . ' ' . $model->last_name;
                $mails->setView('user_registration');
                $mails->setData(
                    array(
                        'name' => $name,'type'=> $model->member_type
                    )
                );
                $mails->setFrom('info@doothan.in',$settings->from_name);
                $mails->setTo($model->email, $model->first_name);
                $mails->setSubject('DOOTHAN : User Registration');
                if (!$mails->Send()) {
                    $response_status=$mail->ErrorInfo;
                }
                $response_status='Success';
                $transaction->commit();
                $result = array('status' => $verifyNumber['status'], 'request_id' => $verifyNumber['request_id'], 'user_id' => $model->id, 'message' => 'success','email_response_status'=>$response_status);
                $this->renderJSON($result);
                $this->refresh(true);
            }
        } else {
            $validationError = array();
            $validationError['validation_error'] = true;
            foreach ($model->errors as $attribute => $attr_errors) {
                foreach ($attr_errors as $attr_error) {
                    $validationError[$attribute] = $attr_error;
                }
            }
            foreach ($address->errors as $attribute => $attr_errors) {
                foreach ($attr_errors as $attr_error) {
                    $validationError[$attribute] = $attr_error;
                }
            }
            $this->renderJSON($validationError);
            $this->refresh(true);
        }
    }

    public function actionLogOut() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user_Details = Users::model()->findByPk($_POST['user_id']);
            $message = $user_Details->first_name . " " . $user_Details->last_name . " Logged Out";
            Common::activityLog($user_Details->id, 'LOG OUT', $message, date('Y-m-d H:i:s'));
            $tokenDetails->expires = time();
            $tokenDetails->save();
            $this->renderJSON(array(
                'message' => 'success'
            ));
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }

    public function actionPaymentsuccess() {
        $this->layout = '//layouts/column2';
        $this->render('success');
    }

    public function actionPaymentfail() {
        $this->layout = '//layouts/column2';
        $this->render('fail');
    }
    public function RegisterMail($model) {
        $this->layout = false;
        return $this->renderPartial('/email/user_registration',array('name' => $model->first_name . ' ' . $model->last_name));
    }

}

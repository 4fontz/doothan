<?php

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
Yii::import('ext.Nexmo.src.NexmoMessage');

class UserController extends Controller {

    public function actionProfile($id) {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($id);

            if ($user) {
                $tradeCount = Trades::model()->count('(user1_id = :userId OR user2_id = :userId) AND (user1_status = 2 AND user2_status = 2)', array(':userId' => $user->id));
                $isoCount = FtIso::model()->countByAttributes(array('user_id' => $user->id, 'type' => 'iso'));
                $ftCount = FtIso::model()->count('user_id = :user_id AND type = :type AND quantity > 0', array(':user_id' => $user->id, ':type' => 'ft'));

                $this->renderJSON(array(
                    'id' => $user->id,
                    'username' => $user->username,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'aboutme' => $user->aboutme,
                    'city' => $user->city,
                    'state' => $user->state,
                    'country' => $user->country,
                    'zip' => $user->zip,
                    'image' => ($user->image) ? Yii::app()->params['profileImageBucketUrl'] . $user->image : '',
                    'cover_image' => ($user->cover_image) ? Yii::app()->params['profileImageBucketUrl'] . $user->cover_image : '',
                    'facebook_image' => $user->facebook_image,
                    'reputation' => $user->reputation,
                    'member_type' => $user->member_type,
                    'trade_count' => $tradeCount,
                    'iso_count' => $isoCount,
                    'ft_count' => $ftCount,
                    'status' => $user->status,
                    'created' => $user->created,
                    'updated' => $user->updated
                ));
            } else {
                //throw new CHttpException(404, 'invalid_user');
                $result = array('message'=>'invalid_user');
                $this->renderJSON($result);
            }
        } else {
           // throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }

    public function actionChangepassword() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($tokenDetails['user_id']);

            $masterreset = isset($_POST['master_reset']) ? $_POST['master_reset'] : false;
            $user->setScenario('changepassword');
            if ($user->password) {
                
                if ($masterreset=='false') {
                    if (!password_verify($_POST['oldpassword'], $user->password)) {
                        //throw new CHttpException(400, 'your old password entered is incorrect. please ensure you enter the correct password');
                        $result = array('message'=>'Your old password entered is incorrect. please ensure you enter the correct password');
                        $this->renderJSON($result);
                    }
                }
            }

            $user->password = $_POST['password'];
            if ($user->save()) {                
                $settings = Settings::model()->find();
                /*$mail = Yii::app()->Smtpmail;
                $mail->SetFrom("support@doothan.in",$settings->from_name);
                $mail->Subject = 'DOOTHAN : Your password has been successfully reset';
                $name = $user->first_name . ' ' . $user->last_name;
                $mail->MsgHTML($this->render('/mail/password_changed',array(
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'username' => $user->username
                ),true));
                $mail->AddAddress($user->email);
                $mail->AddReplyTo("support@doothan.in",$settings->from_name);
                $mail->SMTPDebug = 0;
                $mail->SMTPSecure = 'tls';
                $response_status='Success';
                if (!$mail->Send()) {*/
                $mail = new SesMailer();
                $mail->setView('password_changed');
                $name = $user->first_name . ' ' . $user->last_name;
                $mail->setData(array('name' => $user->first_name . ' ' . $user->last_name,'username' => $user->username));
                $mail->setFrom('support@doothan.in',$settings->from_name);
                $mail->setTo($user->email, $user->first_name);
                $mail->setSubject('DOOTHAN : Your password has been successfully reset');
                if (!$mail->Send()) {
                    $response_status=$mail->ErrorInfo;
                }
                if($user->account_status=='APPROVED'){
                    $login_status = "true";
                    $message = "success";
                }else{
                    $login_status = "false";
                    $message = $userdetails->account_status;
                }
                $this->renderJSON(array(
                    'status' => true,
                    'message' => 'Success',
                    'login_status'=>$login_status,
                    'login_message'=>$message,
                ));
            } else {
                $validationError = array();
                $validationError['status'] = false;
                foreach ($user->errors as $attribute => $attr_errors) {
                    foreach ($attr_errors as $attr_error) {
                        $validationError[$attribute] = $attr_error;
                    }
                }
                $this->renderJSON($validationError);
                $this->refresh(true);
//                throw new CHttpException(400, 'validation_errors');
            }
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }

    public function actionEdit() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($tokenDetails['user_id']);

            if ($_POST['username']) {
                $user->username = $_POST['username'];
            }

            if ($_POST['first_name']) {
                $user->first_name = $_POST['first_name'];
            }

            if ($_POST['last_name']) {
                $user->last_name = $_POST['last_name'];
            }

            if ($_POST['aboutme']) {
                $user->aboutme = $_POST['aboutme'];
            }

            if ($_POST['city']) {
                $user->city = $_POST['city'];
            }

            if ($_POST['state']) {
                $user->state = $_POST['state'];
            }

            if ($_POST['zip']) {
                $user->zip = $_POST['zip'];
            }

            if ($user->save()) {
                $this->renderJSON(array(
                    'message' => 'success'
                ));
            } else {
                //throw new CHttpException(400, 'validation_errors');
                $result = array('message'=>'validation_errors');
                $this->renderJSON($result);
            }
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }

    public function actionUploadProfileImage() {
//         error_reporting(E_ALL);
//         ini_set('display_errors', 'On');
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if ($_POST['image']) {
                $imageName = Uuid::uuid5(Uuid::uuid4(), $tokenDetails['user_id'] . 'profile')->toString();
                file_put_contents(Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName, base64_decode($_POST['image']));
                if ($extension = Common::validateImage(Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName)) {
                    $big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName);
                    $big->adaptiveResize(200, 200);
                    $big->save(Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName);

                    $thumb = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName);
                    $thumb->adaptiveResize(80, 80);
                    $thumb->save(Yii::app()->params['uploadPath'] . 'profile-pic/' . 'thumb_' . $imageName);

                    $user = Users::model()->findByPk($tokenDetails['user_id']);
                   /*  if ($user->image) {
                        //delete old image
                       $client->deleteObject(array(
                            'Bucket' => Yii::app()->params['profileImageBucket'],
                            'Key' => $user->image
                        ));
                        $client->deleteObject(array(
                            'Bucket' => Yii::app()->params['profileImageBucket'],
                            'Key' => 'thumb_' . $user->image
                        ));
                    }*/
                    $user->image = $imageName . '.' . $extension;
                    $user->save();
                    $client = S3Client::factory(array(
                        'key' => Yii::app()->params['awsKey'],
                        'secret' => Yii::app()->params['awsSecret'],
                        'region' => Yii::app()->params['awsRegion'],
                    ));
                    
                    $client->putObject(array(
                        'Bucket' => Yii::app()->params['profileImageBucket'],
                        'Key' => $imageName . '.' . $extension,
                        'SourceFile' => Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName,
                        'ACL' => 'public-read',
                        "Cache-Control" => "max-age=94608000",
                        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                    ));
                    $client->putObject(array(
                        'Bucket' => Yii::app()->params['profileImageBucket'],
                        'Key' => 'thumb_' . $imageName . '.' . $extension,
                        'SourceFile' => Yii::app()->params['uploadPath'] . 'profile-pic/' . 'thumb_' . $imageName,
                        'ACL' => 'public-read',
                        "Cache-Control" => "max-age=94608000",
                        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                    ));
                    
                    @unlink(Yii::app()->params['uploadPath'] . 'profile-pic/' . $imageName);
                    @unlink(Yii::app()->params['uploadPath'] . 'profile-pic/' . 'thumb_' . $imageName);
                    $this->renderJSON(array(
                        'status' => true,
                        'image' => Yii::app()->params['profileImageBucketUrl'] . $imageName . '.' . $extension));
                } else {
                    //throw new CHttpException(400, 'invalid_image');
                    $result = array('message'=>'Invalid Image');
                    $this->renderJSON($result);
                }
            } else {
                //throw new CHttpException(400, 'no_image');
                $result = array('message'=>'No Image');
                $this->renderJSON($result);
            }
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'Invalid Token');
            $this->renderJSON($result);
        }
    }

    public function actionForgotPassword() {
        if (isset($_POST['email'])) {
            $model = Users::model()->findByAttributes(array('email' => $_POST['email']));
            if ($model) {
                $rand = mt_rand(1000, 9999);
                $model->passwordreset_code = substr($model->id.$rand, 0, 4);
                $emailStatus = false;
                if ($model->save()) {
                    $settings = Settings::model()->find();
                    /*$mail = Yii::app()->Smtpmail;
                    $mail->SetFrom("support@doothan.in",$settings->from_name);
                    $mail->Subject = 'DOOTHAN : Forgot Password';
                    $name = $model->first_name . ' ' . $model->last_name;
                    $mail->MsgHTML($this->render('/mail/forgotpassword',array('email' => $model->email,
                            'code' => $model->passwordreset_code,
                            'name' => $model->first_name . ' ' . $model->last_name),true));
                    $mail->AddAddress($model->email);
                    $mail->AddReplyTo("support@doothan.in",$settings->from_name);
                    $mail->SMTPDebug = 0;
                    $mail->SMTPSecure = 'tls';
                    $response_status='Success';
                    if (!$mail->Send()) {*/
                    $mail = new SesMailer();
                    $mail->setView('forgotpassword');
                    $name = $model->first_name . ' ' . $model->last_name;
                    $mail->setData(array('email' => $model->email,'code' => $model->passwordreset_code,'name' => $model->first_name . ' ' . $model->last_name));
                    $mail->setFrom('support@doothan.in',$settings->from_name);
                    $mail->setTo($model->email, $model->first_name);
                    $mail->setSubject('DOOTHAN : Forgot Password');
                    if (!$mail->Send()) {
                        $response_status=$mail->ErrorInfo;
                    }else{
                         $emailStatus = true;
                    }
                    $verifyNumber = PhoneVerifierProcessor::forgotPassword($model->country_code . $model->phone, $model->passwordreset_code);
                    if (!empty($verifyNumber['status']) || $emailStatus) {
                        $this->renderJSON(array('status' => true, 'message' => 'Password reset code sent successfully','email_response_status'=>$response_status));
                    } else {
                        $this->renderJSON(array('status' => false, 'message' => 'Unable to send password reset code','email_response_status'=>$response_status));
                    }
                } else {
                    $this->renderJSON(array('status' => false, 'message' => 'Unable to send password reset code'));
                }
            } else {
                $this->renderJSON(array('status' => false, 'message' => 'User with this email not found'));
            }
        } else {
            $this->renderJSON(array('status' => false, 'message' => 'Invalid email'));
        }
    }

    public function actionVerifyPasswordResetCode() {
        if (!($clientDetails = OauthClients::validateClient($_POST['client_id'], $_POST['client_secret']))) {
            //throw new CHttpException(403, 'invalid_client');
            $result = array('message'=>'invalid client');
            $this->renderJSON($result);
        }
        if (isset($_POST['reset_code'])) {
            if($_POST['type']=="1"){
                $model = Users::model()->findByAttributes(array('verification_code' => $_POST['reset_code']));
            }else{
                $model = Users::model()->findByAttributes(array('passwordreset_code' => $_POST['reset_code']));
            }
            
            if ($model) {
                $tokenModel = new OauthTokens;
                if ($tokenDetails = $tokenModel->createTokenForUser(
                        array(
                            'userId' => $model->id,
                            'clientId' => $_POST['client_id'],
                            'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                        )
                        )) {
                            if($_POST['type']=="1"){
                                $model->verification_code = '';
                            }else{
                                $model->passwordreset_code = '';
                            }
                    $model->save();
                    $this->renderJSON($tokenDetails);
                }
            } else {
                $result = array('message'=>'invalid code');
                $this->renderJSON($result);
            }
        } else {
            $result = array('message'=>'invalid request');
            $this->renderJSON($result);
        }
    }

}

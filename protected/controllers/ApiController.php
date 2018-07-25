<?php

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
Yii::import('ext.Nexmo.src.NexmoMessage');

class ApiController extends Controller {

    public function actionVerifyCode() {
        if (isset($_POST['request_id']) && isset($_POST['code']) && isset($_POST['user_id'])) {
            $data = array('request_id' => $_POST['request_id'], 'code' => $_POST['code']);
            $verifyResult = PhoneVerifierProcessor::verifyCode($data);
            if (!$verifyResult['status']) {
                $this->renderJSON($verifyResult);
                $this->refresh(true);
            } else {
                $user = Users::model()->findByPk($_POST['user_id']);
                if ($user) {
                    $settings = Settings::model()->find();
                    $user->status = 2;
                    $user->login_state = 1;
                    $user->save();
//                    mail start
                    $mail = new SesMailer();
                    $mail->setView('welcome');
                    $mail->setData(
                       array('name' => $user->first_name . ' ' . $user->last_name)
                    );
                    $mail->setFrom('support@doothan.in',$settings->from_name);
                    $mail->setTo($user->email, $user->first_name);
                    $mail->setSubject('DOOTHAN : Welcome to Doodhan');

                    if (!$mail->Send()) {
                        //mail error
                    }
                    //mail end
                    $tokenModel = new OauthTokens;
                    if ($tokenDetails = $tokenModel->createTokenForUser(
                            array(
                                'userId' => $user->id,
                                'clientId' => $_POST['client_id'],
                                'expires' => time() + Yii::app()->params['accessTokenExpiry'],
                            )
                            )) {
                        $message = $user->first_name . " " . $user->last_name . " Registered ";
                        Common::activityLog($user->id, 'REGISTER', $message, date('Y-m-d H:i:s'));
                        $this->renderJSON($tokenDetails);
                    }
                } else {
                    $result = array('message'=>'Invalid user');
                    $this->renderJSON($result);
                }
            }
        } else {
            $result = array('message'=>'Invalid Request Id');
            $this->renderJSON($result);
        }
    }

    // User Profile
    public function actionProfile() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
           $user = Users::model()->findByPk($tokenDetails['user_id']);
            if ($user) {
                $change_request_status = ($user->role_change_to_flag!="0")?"1":"0";
                $address = UserAddress::model()->findByAttributes(array("user_id" => $user->id, 'is_default' => 1));
                /*if($user->member_type!="requester"){
                    if($user->member_type=="dropbox" && $user->role_change_to=='1,2'){
                        $criteria=new CDbCriteria;
                        $criteria->compare('doothan_id',$user->id);
                        $criteria->compare('dropbox_id',$user->id,'','OR');
                        $doothan_earningDetails = Request::model()->findAll($criteria);
                        $doothan_earning_data = array();
                        $dropbox_earning_data = array();
                        foreach($doothan_earningDetails as $earning){
                            if($earning->doothan_id==$user->id){
                                $doothan_earning_data[]=$earning->doodhan_fee;
                            }
                            if($earning->dropbox_id==$user->id){
                                $dropbox_earning_data[]=$earning->dropbox_fee;
                            }
                        }
                        $doothan_earn = array_sum($doothan_earning_data);
                        $dropbox_earn = array_sum($dropbox_earning_data);
                        $earn = $doothan_earn+$dropbox_earn;
                    }else{
                        if($user->member_type=="doothan"){
                            $member = "doothan_id";
                            $fee_text = "doodhan_fee";
                        }else{
                            $member = "dropbox_id";
                            $fee_text = "dropbox_fee";
                        }
                        $earning_data = array();
                        $earningDetails = Request::model()->findAllByAttributes(array($member=>$user->id));
                        foreach($earningDetails as $earning){
                            $earning_data[]= $earning->$fee_text;
                        }
                        if(count($earning_data)>0){
                            $earn = array_sum($earning_data);
                        }else{
                            $earn = 0;
                        }
                    }
                }else{
                    $earn = "";
                }*/
                
                $earnArray = array();
                $earningData = Fee::model()->findAllByAttributes(array('user_id'=>$user->id));
                if($earningData){
                    foreach($earningData as $earning){
                        $earnArray[] = $earning->amount;
                    }
                }
                if($user->image!=NULL){
                    $profile_image = Yii::app()->params['profileImageBucketUrl'].$user->image;
                }else{
                    $profile_image = '';
                }

                if($user->aadhar!=NULL){
                    $aadhar_image = Yii::app()->params['adharImageBucketUrl'].$user->aadhar;
                }else{
                    $aadhar_image = '';
                }

                if($user->photo_id!=NULL){
                    $photo_image = Yii::app()->params['photoImageBucketUrl'].$user->photo_id;
                }else{
                    $photo_image = '';
                }

                $requestCount = Request::model()->countByAttributes(array('user_id'=>$user->id));
                $deliveryCounts = Request::model()->countByAttributes(array('dropbox_id'=>$user->id));
                $pickupCounts = Request::model()->countByAttributes(array('doothan_id'=>$user->id));
                $this->renderJSON(array(
                    'id' => $user->id,
                    'full_name' => $user->first_name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'gender' => $user->gender,
                    'dob' => $user->dob,
                    'profession' => $user->profession,
                    'country_code' => trim($user->country_code),
                    'phone' => $user->phone,
                    'address' => $address->address,
                    'city' => $address->city,
                    'profile_photo'=> $profile_image,
                    'postal_code' => $address->postal_code,
                    'state' => $address->state,
                    'country' => $address->country,
                    'role' => $user->member_type,
                    //'role' => "dropbox",
                    'current_account_status' => $user->account_status,
                    'login_state'=>$user->login_state,
                    'message'=>'success',
                    'request_count'=>($requestCount)?$requestCount:'0',
                    'delivery_count'=>($deliveryCounts)?$deliveryCounts:'0',
                    'pickup_count'=>($pickupCounts)?$pickupCounts:'0',
                    'change_request_status'=>$change_request_status,
                    'earnings'=>array_sum($earnArray),
                    'role_upgrade'=>$user->role_change_to,
                    'aadhar'=>$aadhar_image,
                    'aadhar_number'=>empty($user->aadhar_number)?'':$user->aadhar_number,
                    'photo_id'=>$photo_image,
                    'photo_number'=>empty($user->photo_number)?'':$user->photo_number
                    //'role_upgrade'=>'1,2'
                ));
            } else {
               // throw new CHttpException(404, 'invalid user');
                $result = array('message'=>'invalid user');
                $this->renderJSON($result);
            }
        } else {
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
            //throw new CHttpException(403, 'invalid_token');
        }
    }

    public function actionUpdateUserRole() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($tokenDetails['user_id']);
            if ($user) {
                if (isset($_POST['role']) && $_POST['role'] != '') {
                    //$user->member_type = $_POST['role'];
                    //if($user->member_type=="doothan" && $_POST['role']=="dropbox"){
                        $role = ($_POST['role']=="doothan")?"1":"2";
                        /*if($user->role_change_to==0){
                            $user->role_change_to = $role;
                        }else{*/
                        
                        $user->role_change_to = $user->role_change_to.','.$role;
                        /*}*/
                        $user->role_change_to_flag = $role;
                        $user->account_status = 'CALL_VERIFICATION_PENDING';
                        $user->login_state = 5;
                    //}
                        $user->save();
                        Common::activityLog($user->id, $user->first_name.' '.$user->last_name. ' updated user role to '.$_POST['role'], $message, date('Y-m-d H:i:s'));
                    
                        /* $settings = Settings::model()->find();
                       $mail = Yii::app()->Smtpmail;
                        $mail->SetFrom($settings->from_mail,$settings->from_name);
                        $mail->Subject = 'DOOTHAN : Account Role Changed';
                        $name = $model->first_name . ' ' . $model->last_name;
                        $mail->MsgHTML($this->render('/mail/change_user_role',array('name' => $user->first_name . ' ' . $user->last_name,
                                'status_text'=>$user->member_type, ),true));
                        $mail->AddAddress($user->email);
                        $mail->AddReplyTo($settings->from_mail,$settings->from_name);
                        //$mail->AddCC($addcc);
                        $mail->SMTPDebug = 0;
                        $mail->SMTPSecure = 'tls';
                        $response_status='Success';
                        //$mail->Debugoutput = 'html';
                        if (!$mail->Send()) {
                            $response_status=$mail->ErrorInfo;
                        }*/

                    $this->renderJSON(array(
                        'status' => true,
                        'id' => $user->id,
                        'full_name' => $user->first_name,
                        'email' => $user->email,
                        'username' => $user->username,
                        'gender' => $user->gender,
                        'dob' => $user->dob,
                        'profession' => $user->profession,
                        'country_code' => trim($user->country_code),
                        'phone' => $user->phone,
                        'role' => $user->member_type,
                        'current_account_status' => $user->account_status,
                        'message'=>'Success',
                        'email_response_status'=>$response_status,
                    ));
                } else {
                    $result = array('message'=>'invalid role');
                    $this->renderJSON($result);
                }
            } else {
                $result = array('message'=>'invalid user');
                $this->renderJSON($result);
            }
        } else {
            $result = array('message'=>'invalid token');
            $this->renderJSON($result);
        }
    }

    public function actionUpdateRoleDocuments(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($tokenDetails['user_id']);
            $user->office_address = $_POST['office_address'];
            $user->travel_from_to = $_POST['travel_from_to'];
            $user->mode_of_commute = $_POST['mode_of_commute'];
            $user->shop_location = $_POST['shop_location'];
            $user->shop_phone = $_POST['shop_phone'];
            $user->working_hours = $_POST['time_in']."-".$_POST['time_out'];
            //if($user->member_type=="doothan" && $_POST['role']=="dropbox"){
                $user->account_status = 'CALL_VERIFICATION_PENDING';
                $user->login_state = 2;
            //}
            if($user->save(false)){
                $result = array('message'=>'success');
                $this->renderJSON($result);
            }else{
                $result = array('message'=>'failed');
                $this->renderJSON($result);
            }
        }else{
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result); 
        }
    }
    public function actionUploadDocuments() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if ($_POST['aadhar_number']) {
                if ($_POST['aadhar']) {
                    if ($_POST['photo_id']) {
                        $aadharName = Uuid::uuid5(Uuid::uuid4(), $tokenDetails['user_id'] . 'profile')->toString();
                        file_put_contents(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName, base64_decode($_POST['aadhar']));
                        $photo_idName = Uuid::uuid5(Uuid::uuid4(), $tokenDetails['user_id'])->toString();
                        file_put_contents(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName, base64_decode($_POST['photo_id']));
                        if ($extension = Common::validateImage(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName)) {
                            if ($photo_extension = Common::validateImage(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName)) {
                                $big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName);
//                            $big->adaptiveResize(500, 500);
                                $big->save(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName . '.' . $extension);

                                $big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName);
//                            $big->adaptiveResize(500, 500);

                                $big->save(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName . '.' . $photo_extension);
                                 $client = S3Client::factory(array(
                                    'key' => Yii::app()->params['awsKey'],
                                    'secret' => Yii::app()->params['awsSecret'],
                                    'region' => Yii::app()->params['awsRegion'],
                                ));
                                
                               $client->putObject(array(
                                    'Bucket' => Yii::app()->params['photoImageBucket'],
                                    'Key' => $photo_idName . '.' . $photo_extension,
                                    'SourceFile' => Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName,
                                    'ACL' => 'public-read-write',
                                    "Cache-Control" => "max-age=94608000",
                                    "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                                ));
                                
                                $client->putObject(array(
                                    'Bucket' => Yii::app()->params['adharImageBucket'],
                                    'Key' =>  $aadharName . '.' . $extension,
                                    'SourceFile' => Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName,
                                    'ACL' => 'public-read-write',
                                    "Cache-Control" => "max-age=94608000",
                                    "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                                ));
                                $user = Users::model()->findByPk($tokenDetails['user_id']);
                                $user->aadhar = $aadharName . '.' . $extension;
                                $user->photo_id = $photo_idName . '.' . $photo_extension;
                                $user->photo_number = $_POST['photo_number'];
                                $user->aadhar_number = $_POST['aadhar_number'];
                                $user->office_address = $_POST['office_address'];
                                $user->travel_from_to = $_POST['travel_from_to'];
                                $user->mode_of_commute = $_POST['mode_of_commute'];
                                $user->shop_location = ($_POST['shop_location'])?$_POST['shop_location']:'';
                                $user->shop_phone = ($_POST['shop_phone'])?$_POST['shop_phone']:'';
                                $user->working_hours = ($_POST['time_in']."/".$_POST['time_out'])?$_POST['time_in']."-".$_POST['time_out']:'';
                                $user->account_status = 'CALL_VERIFICATION_PENDING';
                                $user->login_state = 2;
                                $user->save();
//                                 @unlink(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName);
//                                 @unlink(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName);
                                Common::activityLog($user->id, $user->first_name.' '.$user->last_name. ' uploaded their documents', $message, date('Y-m-d H:i:s'));
                                $this->renderJSON(array(
                                    'id' => $user->id,
                                    'full_name' => $user->first_name,
                                    'aadhar_number' => $user->aadhar_number,
                                    'office_address' => $user->office_address,
                                    'travel_from_to' => $user->travel_from_to,
                                    'mode_of_commute' => $user->mode_of_commute,
                                    'profession' => $user->profession,
                                    'role' => $user->member_type,
                                    'current_account_status' => $user->account_status,
                                    'aadhar' => Yii::app()->getBaseUrl(true) . '/uploads/aadhar/' . $user->aadhar,
                                    'photo_id' => Yii::app()->getBaseUrl(true) . '/uploads/photo_id/' . $user->photo_id,
                                    'message'=>'Documents uploaded successfully'
                                ));
                            } else {
                                //throw new CHttpException(400, 'invalid_photo_id');
                                $result = array('message'=>'invalid_photo_id');
                                $this->renderJSON($result);
                            }
                        } else {
                           // throw new CHttpException(400, 'invalid_aadhar_image');
                            $result = array('message'=>'invalid_aadhar_image');
                            $this->renderJSON($result);
                        }
                        @unlink(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName);
                        @unlink(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName);
                    } else {
                        //throw new CHttpException(400, 'photo_id_required');
                        $result = array('message'=>'photo_id_required');
                        $this->renderJSON($result);
                    }
                } else {
                    //throw new CHttpException(400, 'aadhar_required');
                    $result = array('message'=>'aadhar_required');
                    $this->renderJSON($result);
                }
            } else {
                //throw new CHttpException(403, 'invalid_aadhar_number');
                $result = array('message'=>'invalid_aadhar_number');
                $this->renderJSON($result);
            }
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }

    public function actionUpdateProfile() {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
       
            $model = Users::model()->findByPk($tokenDetails['user_id']);
            if (isset($_POST['email']) && $_POST['email'] != $model->email) {
                $email = Users::model()->findByAttributes(array('email' => $_POST['email']));
                if ($email) {
                    //throw new CHttpException(403, 'email already in use');
                    $result = array('status'=>'false','message'=>'email already in use');
                    $this->renderJSON($result);
                } else {
                    $model->email = $_POST['email'];
                }
            }
			if(isset($_POST['photo_number']) || isset($_POST['aadhar_number'])){ 
					if (isset($_POST['photo_id'])) {
								
								$photo_idName = Uuid::uuid5(Uuid::uuid4(), $tokenDetails['user_id'])->toString();
								file_put_contents(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName, base64_decode($_POST['photo_id']));
									if ($photo_extension = Common::validateImage(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName)) {
									  

										$big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName);
		//                            $big->adaptiveResize(500, 500);

										$big->save(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName . '.' . $photo_extension);
										 $client = S3Client::factory(array(
											'key' => Yii::app()->params['awsKey'],
											'secret' => Yii::app()->params['awsSecret'],
											'region' => Yii::app()->params['awsRegion'],
										));
										
									   $client->putObject(array(
											'Bucket' => Yii::app()->params['photoImageBucket'],
											'Key' => $photo_idName . '.' . $photo_extension,
											'SourceFile' => Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName,
											'ACL' => 'public-read-write',
											"Cache-Control" => "max-age=94608000",
											"Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
										));
										
										
										
										$model->photo_id = $photo_idName . '.' . $photo_extension;
										$model->photo_number = $_POST['photo_number'];
                                        $message=$model->first_name.' '.$model->last_name. ' uploaded Photo';
									   Common::activityLog($model->id, $model->first_name.' '.$model->last_name. ' uploaded their documents', $message, date('Y-m-d H:i:s'));
									   
									} else {
										//throw new CHttpException(400, 'invalid_photo_id');
										$result = array('message'=>'invalid_photo_id');
										$this->renderJSON($result);
                                        $this->refresh(true);
									}
								
								//@unlink(Yii::app()->params['uploadPath'] . 'photo_id/' . $photo_idName);
							}
							
							if (isset($_POST['aadhar'])) {
								$aadharName = Uuid::uuid5(Uuid::uuid4(), $tokenDetails['user_id'] . 'profile')->toString();
								file_put_contents(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName, base64_decode($_POST['aadhar']));
								if ($extension = Common::validateImage(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName)) {
										$big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName);
		//                            $big->adaptiveResize(500, 500);
										$big->save(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName . '.' . $extension);
										
										$client = S3Client::factory(array(
											'key' => Yii::app()->params['awsKey'],
											'secret' => Yii::app()->params['awsSecret'],
											'region' => Yii::app()->params['awsRegion'],
										));
										
										$client->putObject(array(
											'Bucket' => Yii::app()->params['adharImageBucket'],
											'Key' =>  $aadharName . '.' . $extension,
											'SourceFile' => Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName,
											'ACL' => 'public-read-write',
											"Cache-Control" => "max-age=94608000",
											"Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
										));
										
										$model->aadhar = $aadharName . '.' . $extension;
										$model->aadhar_number = $_POST['aadhar_number'];
										$message=$model->first_name.' '.$model->last_name. ' uploaded Aadhar';
										Common::activityLog($model->id, $model->first_name.' '.$model->last_name. ' uploaded their documents', $message, date('Y-m-d H:i:s'));
										
									
								} else {
								   // throw new CHttpException(400, 'invalid_aadhar_image');
									$result = array('message'=>'invalid_aadhar_image');
									$this->renderJSON($result);
                                    $this->refresh(true);
								}
								//@unlink(Yii::app()->params['uploadPath'] . 'aadhar/' . $aadharName);
							}
                }
					
            $model->scenario = 'socialLogin';
            $model->first_name = $_POST['full_name'];
            $model->country_code = (isset($_POST['country_code'])) ? $_POST['country_code'] : '+91';
            $model->phone = $_POST['mobile'];
            $model->profession = $_POST['profession'];
            $model->gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';
            $model->dob = (isset($_POST['dob'])) ? $_POST['dob'] : '';
            $model->member_type = isset($_POST['role'])?$_POST['role']:$model->member_type;
            if($_POST['role']=="requester"){
                $model->role_change_to = "0";
            }else if($_POST['role']=="doothan"){
                $model->role_change_to = "1";
            }else{
                $model->role_change_to = "2";
            }
            /*if($_POST['role']=="requester"){
                $status = "APPROVED";
            }else{
                $status = "CALL_VERIFICATION_PENDING";
            }
            $model->account_status=$status;*/
            if($_POST['first_param']=="true"){
                $model->login_state = 0;
            }
            $address = UserAddress::model()->findByAttributes(array("user_id" => $model->id, 'is_default' => 1));
            if (!$address) {
                $address = new UserAddress();
            }
            $address->address = (isset($_POST['address'])) ? $_POST['address'] : '';

            $address->city = (isset($_POST['city'])) ? $_POST['city'] : '';
            $address->postal_code = (isset($_POST['pincode'])) ? $_POST['pincode'] : '';
            $address->state = (isset($_POST['state'])) ? $_POST['state'] : '';
            $address->user_id = $model->id;
            $address->country = (isset($_POST['country'])) ? $_POST['country'] : '';

            // validate user input and redirect to the previous page if valid
            if ($address->validate() && $model->validate()) {

                $model->save();

                $location = $address->city . ' ' . $address->state . ' ' . $address->postal_code;
                if ($address->city != '' || $address->state != '' || $address->postal_code != '') {
                    $geoLocation = Helper::getCoordinates($location);
                    /*if ($geoLocation['result'] == null) {
                        $location = $address->city;
                        $geoLocation = Helper::getCoordinates($location);
                    }*/
                }

                $address->geo_location = ($geoLocation['lat'] != '' && $geoLocation['long'] != '') ? new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $geoLocation['lat'] . ' ' . $geoLocation['long'] . ')')) : '';
                $address->place_id = isset($geoLocation['place_id']) ? $geoLocation['place_id'] : '';
                $address->created = date('Y-m-d H:i:s');
                $address->is_default = 1;
                $address->status = 1;
                $address->save();
                //Common::activityLog($model->id, $model->first_name.' '.$model->last_name. ' updated their profile', $message, date('Y-m-d H:i:s'));
                $settings = Settings::model()->find();
                //$mail = Yii::app()->Smtpmail;
                $mail = new SesMailer();
                //$mail->SetFrom("info@doothan.in",$settings->from_name);
                $mail->setFrom('info@doothan.in',$settings->from_name);
                if($_POST['first_param']=="true"){
                    //$mail->Subject='DOOTHAN : User Registration';
                    $mail->setSubject('DOOTHAN : User Registration');
                }else{
                    //$mail->Subject='DOOTHAN : User Edit Profile';
                    $mail->setSubject('DOOTHAN : User Edit Profile');
                }
                $name = $model->first_name . ' ' . $model->last_name;
                if($_POST['first_param']=="true"){
                   // $mail->MsgHTML($this->render('/mail/user_registration',array('name' => $model->first_name . ' ' . $model->last_name ),true));
                    $mail->setView('user_registration');
                    $mail->setData(array('name' => $model->first_name . ' ' . $model->last_name));
                }else{
                    //$mail->MsgHTML($this->render('/mail/edit_user_profile',array('name' => $model->first_name . ' ' . $model->last_name ),true));
                    $mail->setView('edit_user_profile');
                    $mail->setData(array('name' => $model->first_name . ' ' . $model->last_name));
                }
                //$mail->AddAddress($model->email);
                $mail->setTo($model->email, $model->first_name);
                //$mail->AddReplyTo("info@doothan.in",$settings->from_name);
                //$mail->SMTPDebug = 0;
                //$mail->SMTPSecure = 'tls';
                $response_status='Success';
                if (!$mail->Send()) {
                    $response_status=$mail->ErrorInfo;
                }
                if($model->aadhar!=NULL){
                    $aadhar_image = Yii::app()->params['adharImageBucketUrl'].$model->aadhar;
                }else{
                    $aadhar_image = '';
                }

                if($model->photo_id!=NULL){
                    $photo_image = Yii::app()->params['photoImageBucketUrl'].$model->photo_id;
                }else{
                    $photo_image = '';
                }

                if($_POST['first_param']=="true"){
                    $verifyNumber = PhoneVerifierProcessor::verifyRequest($_POST['country_code'] . $_POST['mobile']);
                    if (!$verifyNumber['status']) {
                        //$transaction->rollback();
                        $this->renderJSON($verifyNumber);
                        $this->refresh(true);
                    } else {
                        $result = array('status' => $verifyNumber['status'], 'request_id' => $verifyNumber['request_id'], 'user_id' => $model->id, 'message' => 'success','email_response_status'=>$response_status);
                        $this->renderJSON($result);
                        $this->refresh(true);
                    }
                }else{
                    $this->renderJSON(array(
                        'id' => $model->id,
                        'full_name' => $model->first_name,
                        'email' => $model->email,
                        'username' => $model->username,
                        'gender' => $model->gender,
                        'dob' => $model->dob,
                        'profession' => $model->profession,
                        'country_code' => trim($model->country_code),
                        'phone' => $model->phone,
                        'address' => $address->address,
                        'city' => $address->city,
                        'postal_code' => $address->postal_code,
                        'state' => $address->state,
                        'country' => $address->country,
                        'role' => $model->member_type,
                        'current_account_status' => $model->account_status,
                        'status' => true,
                        'email_response_status'=>$response_status,
                        'request_id'=>"",
                        'user_id'=>"",
                        'message'=>"",
                        'aadhar'=>$aadhar_image,
                        'aadhar_number'=>empty($model->aadhar_number)?'':$model->aadhar_number,
                        'photo_id'=>$photo_image,
                        'photo_number'=>empty($model->photo_number)?'':$model->photo_number
                        
                    ));
                }
            } else {
                $validationError = array();
                $validationError['status'] = false;
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
                $validationError['status']=false;
                $this->renderJSON($validationError);
                $this->refresh(true);
            }
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    

    
    //  get City List
    public function actionNotification() {
        //if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $device_id = "cqfbn1olgM4:APA91bGog0Pg1kHRmBCy7ow8Ew6v0PJY8GrChjGSYZc66p665VipSsfmBvE28AtS9MhXGBNRCX2Frh17solfkXzmbn5dPVht1EfHzQGoxx0vLpnbXITHimoBqRlipkt-KMKpS7Tm9-oq";
            $result=Common::sendPushNotification($device_id, 'test');
            $this->renderJSON(json_decode($result));
        //} else {
           // $result = array('message'=>'invalid_token');
            //$this->renderJSON($result);
        //}
    }
    
    public function actionResendVerificationCode() {
        $model = Users::model()->findByAttributes(array('email'=>$_POST['email_id']));
        $userId=$model->id;
        if ($model) {
            $mobile = '';
            $rand=mt_rand(1000, 9999);
            $model->passwordreset_code = $rand;
            $model->verification_code = $rand;
            $mobile = isset($model->phone)?$model->country_code.$model->phone:'';
            $username = $model->username;
            $model->verification_code=$rand;
            if($model->save()){
                /*$verifyNumber = PhoneVerifierProcessor::verifyRequest($model->country_code . $model->phone);
                if (!$verifyNumber['status']) {
                    //$transaction->rollback();
                    $result = array('status' => "Error while sent verification code", 'user_id' => $userId, 'message' => "error",'mobile'=>$mobile,'email'=>$email_id);
                    $this->renderJSON($verifyNumber);
                    $this->refresh(true);
                }else{*/
                    
                    /*else {
                     $result = array('status' => $verifyNumber['status'], 'request_id' => $verifyNumber['request_id'], 'user_id' => $model->id, 'message' => 'success','email_response_status'=>$response_status);
                     $this->renderJSON($result);
                     $this->refresh(true);
                     }*/
                    $key=Yii::app()->params['nexmoKey'];
                    $secret=Yii::app()->params['nexmoSecret'];
                    $senderid=Yii::app()->params['nexmoSenderId'];
                    $msg = "Your Doothan password reset code is $rand";
                    $nexmo_sms = new NexmoMessage($key, $secret);
                    $info = $nexmo_sms->sendText($mobile,$senderid,$msg);
                    $nexmo_sms->displayOverview($info);
                    $settings = Settings::model()->find();
                    $emailStatus = 0;
                    $email_obj = new SesMailer();
                    $email_id = $model->email;
                    $name = $model->first_name;
                    $verification_code = $rand;
                    $email_obj->setView('resend_verify');
                    $email_obj->setData(
                        array(
                            'name' => $name , 'ver_code' => $verification_code , 'email' => $email_id , 'username' => $username
                        )
                        );
                    $email_obj->setFrom("support@doothan.in",$settings->from_name);
                    $email_obj->setTo($email_id, $model->first_name);
                    $email_obj->setSubject('DOOTHAN : Verification code');
                    if ($email_obj->Send()) {
                        $emailStatus = 1;
                    }
                    if($emailStatus==1){
                        $status_message = "Verification code successfully sent";
                        $message = "success";
                    }else{
                        $status_message = "Error while sent verification code";
                        $message = "error";
                    }
                    $result = array('status' => $status_message, 'user_id' => $userId, 'message' => $message,'mobile'=>$mobile,'email'=>$email_id);
                    Common::activityLog($model->id, 'Verification code resent for '.$model->first_name.' '.$model->last_name, $message, date('Y-m-d H:i:s'));
                    $this->renderJSON($result);
                    $this->refresh(true);
            }else{
                    $result = array('status' => 'Error while generating code,try after some time', 'user_id' => $userId, 'message' => 'Error occured','mobile'=>$mobile);
                    $this->renderJSON($result);
                    $this->refresh(true);
            }
            /*}else{
                $result = array('status' => 'Please verify your mobile number', 'user_id' => $userId, 'message' => 'Error occured','mobile'=>$mobile);
                $this->renderJSON($result);
                $this->refresh(true);
            }*/
        }else{
            $result = array('message'=>'invalid email id');
            $this->renderJSON($result);
        }
        
    }
    
    public function actionFeedbackSubmit(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $model = Users::model()->findByPk($_POST['user_id']);
            if($model){
                $feedback = new Feedback;
                $feedback->user_id =  $_POST['user_id'];
                $feedback->feedback = $_POST['feedback'];
                $feedback->status='Y';
                $feedback->created_at = date('Y-m-d H:i:s');
                if($feedback->save()){
                    $result = array('status' =>true,'message'=>'success');
                }else{
                    $result = array('status' =>false,'message'=>'error');
                }
             }else{
                    $result = array('status' =>false,'message'=>'invalid_token');
             }
             $this->renderJSON($result);
             $this->refresh(true);
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('status' =>false,'message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    
    public function actionCallbackSubmit(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $model = Users::model()->findByPk($_POST['user_id']);
            if($model){
                $feedbackCount = Feedback::model()->countByAttributes(array('user_id'=>$_POST['user_id'],'type'=>'1','status'=>'N'));
                if($feedbackCount>=3){
                    $result = array('status' =>false,'message'=>'limit_exceeded');
                }else{
                    $feedback = new Feedback;
                    $feedback->user_id =  $_POST['user_id'];
                    $feedback->type=1;
                    $feedback->created_at = date('Y-m-d H:i:s');
                    if($feedback->save(false)){
                        $result = array('status' =>true,'message'=>'success');
                    }else{
                        $result = array('status' =>false,'message'=>'failed');
                    }
                }
            }else{
                $result = array('status' =>false,'message'=>'invalid_token');
            }
            $this->renderJSON($result);
            $this->refresh(true);
        } else {
            $result = array('status' =>false,'message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    
    
    /*public function actionGenerate(){
        $total_requests = Request::model()->findAll(array('order'=>'id DESC'));
        $request_code_data = $total_requests[0]->request_code;
        if($request_code_data){
            $request_code = explode("Dh-",$request_code_data);
            $request_code = $request_code[1]+10;
            $request_code = "Dh-".$request_code;
        }else{
            $request_code = "Dh-10";
        }
        echo $request_code;die;
        
    }*/
    public function actionRequest() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($_POST['user_id']);
            $total_requests = Request::model()->findAll(array('order'=>'id DESC'));
            $request_code_data = $total_requests[0]->request_code;
            if($request_code_data){
                $request_code = explode("Dh-",$request_code_data);
                $request_code = $request_code[1]+10;
                $request_code = "Dh-".$request_code;
            }else{
                $request_code = "Dh-10";
            }
            if ($user) {
                $request = new Request;
                $request->request_code = $request_code;
                $request->type = $_POST['type'];
                $request->item_details = $_POST['item_details'];
                $request->request_date = $_POST['request_date'];
                $request->to_address = $_POST['from_address'];
                $cityAddress = Cities::model()->findByAttributes(array('city_name'=>trim($_POST['from_city'])));
                $request->to_city = $cityAddress->city_id;
                $request->to_state = $_POST['from_state'];
                $request->to_pincode = $_POST['from_pin'];
                $request->phone = !empty($_POST['from_phone'])?$_POST['from_phone']:'0';
                $request->dropbox_id = $_POST['dropbox_id'];
                $request->user_id = $user['id'];
                $request->device_token = $_POST['device_token'];
                $request->status = 'Request Placed';
                $request->created_on = date('Y-m-d H:i:s');;
                $request->updated_on = date('Y-m-d H:i:s');;
                if ($_POST['image']) {
                    $requestName = Uuid::uuid5(Uuid::uuid4(), time() . 'request')->toString();
                    file_put_contents(Yii::app()->params['uploadPath'] . 'request/' . $requestName, base64_decode($_POST['image']));
                    if ($extension = Common::validateImage(Yii::app()->params['uploadPath'] . 'request/' . $requestName)) {
                        $big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'request/' . $requestName);
                        //                        $big->adaptiveResize(500, 500);
                        $big->save(Yii::app()->params['uploadPath'] . 'request/' . $requestName . '.' . $extension);
                        $client = S3Client::factory(array(
                            'key' => Yii::app()->params['awsKey'],
                            'secret' => Yii::app()->params['awsSecret'],
                            'region' => Yii::app()->params['awsRegion'],
                        ));
                        
                        $client->putObject(array(
                            'Bucket' => Yii::app()->params['requestImageBucket'],
                            'Key' => $requestName . '.' . $extension,
                            'SourceFile' => Yii::app()->params['uploadPath'] . 'request/' . $requestName,
                            'ACL' => 'public-read',
                            "Cache-Control" => "max-age=94608000",
                            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                        ));
                        @unlink(Yii::app()->params['uploadPath'] . 'request/' . $imageName);
                        $request->image = $requestName . '.' . $extension;
                    } else {
                        $result = array('message'=>'invalid_image');
                        $this->renderJSON($result);
                    }
                }
                if ($request->validate()) {
                    $request->save();
                    $result_msg = "success";
                    $request_code = $request->request_code;
                    $status = 'true';
                    $this->renderJSON(array('status' => $status,'request_code'=>$request_code,'message'=>$result_msg));
                } else {
                    $validationError = array();
                    $validationError['status'] = false;
                    foreach ($request->errors as $attribute => $attr_errors) {
                        foreach ($attr_errors as $attr_error) {
                            $validationError[$attribute] = $attr_error;
                        }
                    }
                    $this->renderJSON($validationError);
                    $this->refresh(true);
                    $this->renderJSON(array('status' => 'true', 'message' => 'failed'));
                }
            } else {
                $result = array('message'=>'Invalid user');
                $this->renderJSON($result);
            }
        } else {
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    
    public function actionDropboxUsersList(){
        ini_set('max_execution_time', 30000);
        //if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            //$userAddress = UserAddress::model()->findByAttributes(array('user_id'=>$tokenDetails['user_id']));
            if($_POST['from_city']){
                if($_POST['from_state']){
                    //$location = $_POST['from_city']. ' ' . $_POST['from_state'] . ' ' . $_POST['from_pincode'];
                    $location = $_POST['from_pincode'];
                    //$location = $userAddress->city. ' ' . $userAddress->state . ' ' . $userAddress->postal_code;
                    $all_user_address=array();
                    $all_user_details = Users::model()->findAllByAttributes(array('member_type'=>'dropbox','status'=>2,'account_status'=>'APPROVED'));
                    //echo "<pre>";print_r($all_user_details);die;
                    if(count($all_user_details)>0){
                        $result_array = array();$j++;
                        foreach($all_user_details as $all_user){
                            $singleAddress = UserAddress::model()->findByAttributes(array('user_id'=>$all_user['id']));
                            if(count($singleAddress)){
                                //$second_location = $singleAddress->city.' '.$singleAddress->state.' '.$singleAddress->postal_code;
                                $second_location = $singleAddress->postal_code;
                                $params = array($location,$second_location);
                                $result_array[$j]['id']=$singleAddress->user_id;
                                $result_array[$j]['name']=$all_user['first_name'];
                                $result_array[$j]['country_code']=$all_user['country_code'];
                                $result_array[$j]['phone']=$all_user['phone'];
                                $result_array[$j]['distance']=Helper::getLocationDistance($params);
                                $result_array[$j]['address']=$singleAddress->address;
                                $result_array[$j]['city']=$singleAddress->city;
                                $result_array[$j]['state']=$singleAddress->state;
                                $result_array[$j]['postal_code']=$singleAddress->postal_code;
                                $j++;
                            }
                        }
                        //echo "<pre>";print_r($result_array);die;
                        $km_array = array();
                        $settings = Settings::model()->find();
                        foreach($result_array as $result){
                            if(intval($result['distance'])<=intval($settings->minimum_km)){
                                $data = array();
                                $data['id'] = $result['id'];
                                $data['name'] = $result['name'];
                                $data['country_code'] = $result['country_code'];
                                $data['phone'] = $result['phone'];
                                $data['address'] = $result['address'];
                                $data['distance'] = ($result['distance'])?$result['distance']:'0';
                                $data['city'] = $result['city'];
                                $data['state'] = ($result['state'])?$result['state']:'Kerala';
                                $data['postal_code'] = $result['postal_code'];
                                array_push($km_array, $data);
                            }
                        }
                        $this->renderJSON(array('message'=>'success','dropbox'=>$km_array));
                    }else{
                        $km_array = array();
                        $this->renderJSON(array('message'=>'success','dropbox'=>$km_array));
                    }
                    
                }else{
                    $result = array('message'=>'Invalid State');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('message'=>'Invalid City');
                $this->renderJSON($result);
            }
        /*}else {
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }*/
    }
    
    //  get State List
    public function actionGetState() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $stateList = array();
            $state = array();
            $state['name'] = 'Kerala';
            $state['value'] = 'Kerala';
            array_push($stateList, $state);
            $this->renderJSON(array('message'=>'success','state'=>$stateList));
        } else {
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    
    public function actionGetCity() {
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if ($_POST['state']) {
                $cities = Cities::model()->findAll(array("condition" => 'city_state="Kerala"'),array(
                    'order' => 'city_id asc',
                    'limit' => 15,
                    'offset' => 0
                ));
                $city_list = array();
                foreach ($cities as $city) {
                    $data = array();
                    $data['city_id'] = $city->city_id;
                    $data['city_name'] = $city->city_name;
                    $data['pin_code'] = $city->city_pin_code;
                    array_push($city_list, $data);
                }
            }
            $this->renderJSON($city_list);
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    
    public function actionOrderList(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if($_POST['type']){
                if($_POST['user_id']){
                    $criteria=new CDbCriteria;
                    if($_POST['type']==1){
                        $criteria->compare('user_id',$_POST['user_id']);
                        //$user_type='user_id';
                    }else if($_POST['type']==2){
                        $criteria->compare('doothan_id',$_POST['user_id']);
                       // $user_type='doothan_id';
                    }else{
                        $criteria->compare('dropbox_id',$_POST['user_id']);
                        //$user_type='dropbox_id';
                    }
                    $criteria->order = 'request_date DESC';
                    $order_list = Request::model()->findAll($criteria);
                    if($order_list){
                        $result_array = array();
                        foreach($order_list as $order){
                            $order_details = Request::model()->findByPk($order->id);
                            $ratig_details = RequestRating::model()->findByAttributes(array('request_id'=>$order->id));
                            //$payment_method = ($order_details->status!="Request Placed" || $order_details->status!="Waiting for payment" || $order_details->status!="Request Placed")?"PayuMoney":'';
                            $dropbox_details = Users::model()->findByPk($order->dropbox_id);
                            $dropboxAddressDetails  = UserAddress::model()->findByAttributes(array('user_id'=>$order->dropbox_id));
                            $user_name = ($order_details->user)?$order_details->user->first_name.' '.$order_details->user->last_name:'';
                            $data = array();
                            $date = Common::getTimezone($order->request_date,'d M y');
                            $data['request_code']=$order->request_code;
                            $data['request_id']=$order->id;
                            $data['item_image']=Yii::app()->params['requestImageBucketUrl'].$order_details->image;
                            $data['item']=$order_details->item_details;
                            $data['customer_name']=$user_name;
                            $data['customer_phone']=$order_details->phone;
                            $charge=$order_details->base_amount;
                            $de = $order_details->coupon_amount+$order_details->discount;
                            $bc = $order_details->rate_per_km * $order_details->distance;
                            $abc = $charge+$bc;
                            $last_total = $abc-$de+$order_details->weight;
                            $vat = ($last_total*$order_details->gst)/100;
                            $after_vat = $last_total+$vat;
                            $last_last = $after_vat+$order_details->product_price;
                            $gst_value = ($last_total * $order_details->gst)/100;
                            $data['service_charge']=$after_vat;
                            $data['total_cost']=$order_details->amount;
                            $data['request_date']=$date;
                            $data['type'] = $_POST['type'];
                            $city_data = ($order_details->city)?$order_details->city->city_name:'';
                            $data['from_address'] = $order_details->to_address;
                            $data['from_city'] = $city_data;
                            $data['from_state'] = $order_details->to_state;
                            $data['from_pincode'] = $order_details->to_pincode;
                            $data['from_phone'] = ($order_details->phone)?$order_details->phone:'';
                            
                            if($_POST['type']==1){
                                $data['rating'] = "";
                            }else if($_POST['type']==2){
                                $data['rating'] = ($ratig_details->doothan_rating)?$ratig_details->doothan_rating:'0';
                            }else{
                                $data['rating'] = ($ratig_details->dropbox_rating)?$ratig_details->dropbox_rating:'0';
                            }
                            $data['to_address']  = $dropboxAddressDetails->address;
                            $data['to_state']    = $dropboxAddressDetails->state;
                            $data['to_city']    = $dropboxAddressDetails->city;
                            $data['to_pincode']     = $dropboxAddressDetails->postal_code; 
                            $data['to_phone'] = $dropbox_details->phone;
                            $data['product_price'] = $order->product_price;
                            $data['data']['doothan_service_fee']="";
                            $data['status_text'] = $order->status;
                            if($order->status=="Request Placed" || $order->status=="Waiting for payment"){
                                $data['status'] = "1";
                                $payment_method = "";
                            }elseif($order->status=="Payment completed" || $order->status=="Delivered to dropbox" || $order->status=="Received to dropbox"){
                                $data['status'] = "2";
                                $payment_method = "PayuMoney";
                            }else if($order->status=="Delivered"||$order->status=="Delivered to user"){
                                $data['status'] = "3";
                                $payment_method = "PayuMoney";
                            }else{
                                $data['status'] = "0";
                                $payment_method = "";
                            }
                            $data['payment_option'] = $payment_method;
                            array_push($result_array, $data);
                        }
                        $this->renderJSON($result_array);
                    }
                }else{
                    $result = array('message'=>'Invalid User');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('message'=>'Invalid Type');
                $this->renderJSON($result);
            }
        }else {
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }
    
    public function actionPaymentOrderList(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
                $order_list = Request::model()->findAllByAttributes(array('user_id'=>$_POST['user_id'],'status'=>'Waiting for payment'));
                $userDetails = Users::model()->findByPk($_POST['user_id']);
                if($order_list){
                    $result_array = array();
                    foreach($order_list as $order){
                        $settings = Settings::model()->find();
                        $order_details = Request::model()->findByPk($order->id);
                        $user_name = $userDetails->first_name;
                        $cust_phone = $userDetails->phone;
                        /*if(intval($order->distance) >=0){
                            $dcharge=(intval($order->distance)*2 *$order->rate_per_km);
                        }else{
                            $dcharge=$default_distance_charge;
                        }
                        $charge=$order->base_amount+$dcharge;
                        //redeem coupon amount
                        if($order->coupon_amount > 0){
                            $charge=$charge-intval($order->coupon_amount);
                            
                        }
                        //discount calculation
                        if($order->discount > 0){
                            $charge=$charge-intval($order->discount);
                        }
                        $charge=$charge+$order->weight;
                        if($order->gst > 0){
                            $gst_amount=$charge/100*$order->gst;
                        }
                        $feeGst=$charge+$gst_amount;*/
                        $charge=$order_details->base_amount;
                        $de = $order_details->coupon_amount+$order_details->discount;
                        $bc = $order_details->rate_per_km * $order_details->distance;
                        $abc = $charge+$bc;
                        $last_total = $abc-$de+$order_details->weight;
                        $vat = ($last_total*$order_details->gst)/100;
                        $after_vat = $last_total+$vat;
                        $last_last = $after_vat+$order_details->product_price;
                        $gst_value = ($last_total * $order_details->gst)/100;
                        $data = array();
                        $date = Common::getTimezone($order->request_date,'d M y');
                        $data['request_id']=$order->id;
                        $data['request_code']=$order->request_code;
                        $data['item_image']=Yii::app()->params['requestImageBucketUrl'].$order_details->image;
                        $data['item_total']=$order_details->product_price;
                        $data['service_charge']=number_format((float)$last_total, 2, '.', '');
                        $data['email_id']=$userDetails->email;
                        $data['discount']=number_format((float)$order_details->discount, 2, '.', '');
                        $data['tax']=$gst_value;
                        $data['tax_value']=$order_details->gst;
                        $data['item']=$order_details->item_details;
                        $data['product_price'] = number_format((float)$order_details->product_price, 2, '.', '');
                        $data['customer_name']=$user_name;
                        $data['customer_phone']=$cust_phone;
                        $data['total_amount']=number_format((float)$order_details->amount, 2, '.', '');
                        $data['request_date']=$date;
                        array_push($result_array, $data);
                    }
                    $this->renderJSON($result_array);
                }
        }else {
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }
    public function actionPaymentList(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $criteria = new CDbCriteria;
            $criteria->compare('status', "Payment completed", '' ,'OR');
            $criteria->compare('status', "Delivered", '' ,'OR');
            $criteria->compare('user_id', $_POST['user_id']);
            $order_details = Request::model()->findAll($criteria);
            //echo "<pre>";print_r($order_details);die;
            $userDetails = Users::model()->findByPk($_POST['user_id']);
            if($order_details){
                $result_array = array();
                foreach($order_details as $order){
                    $settings = Settings::model()->find();
                    $default_distance_charge=$settings->default_distance_charge;
                    $order_details = Request::model()->findByPk($order->id);
                    $user_name = $userDetails->first_name.' '.$userDetails->last_name;
                    $cust_phone = $userDetails->phone;
                    
                    $charge=$order_details->base_amount;
                    $de = $order_details->coupon_amount+$order_details->discount;
                    $bc = $order_details->rate_per_km * $order_details->distance;
                    $abc = $charge+$bc;
                    $last_total = $abc-$de+$order_details->weight;
                    $vat = ($last_total*$order_details->gst)/100;
                    $after_vat = $last_total+$vat;
                    $last_last = $after_vat+$order_details->product_price;
                    //$gst_value = ($last_total * $order_details->gst)/100;
                    
                    $data = array();
                    $date = Common::getTimezone($order->request_date,'d M y');
                    $data['request_id']=$order->id;
                    $data['request_code']=$order->request_code;
                    $data['item_image']=Yii::app()->params['requestImageBucketUrl'].$order_details->image;
                    $data['item_total']=$order_details->product_price;
                    $data['service_charge']=$last_total;
                    $data['email_id']=$userDetails->email;
                    $data['discount']=$order_details->discount;
                    $data['tax']=$vat;
                    $data['tax_value']=$order_details->gst;
                    $data['item']=$order_details->item_details;
                    $data['product_price'] = $order_details->product_price;
                    $data['customer_name']=$user_name;
                    $data['customer_phone']=$cust_phone;
                    $data['total_amount']=$order_details->amount;
                    $data['request_date']=$date;
                    array_push($result_array, $data);
                }
                $this->renderJSON($result_array);
            }
        }else {
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }

    public function actionPaymentSuccess(){
        if($_POST['order_id']){
            $order_details = Request::model()->findByPk($_POST['order_id']);
            $userdetails = Users::model()->findByPk($order_details->user_id);
            $params = $_POST['params'];
            if($order_details){
                $result_arraty = json_decode($params);
                $last_array = $result_arraty->result;
                if($last_array->status=="success"){
                    $order_details->status="Payment completed";
                    $header_subject = "Doothan payment completed";
                    $msg_body = "has been successfully completed";
                }else{
                    $order_details->status="Waiting for payment";
                    $header_subject = "Doothan payment failed";
                    $msg_body = "has been failed,Please try after some time";
                }
                if($order_details->save(false)){
                    if($userdetails->member_type!="requester"){
                        if($userdetails->member_type=="dropbox" && $userdetails->role_change_to=='1,2'){
                            $criteria=new CDbCriteria;
                            $criteria->compare('doothan_id',$userdetails->id);
                            $criteria->compare('dropbox_id',$userdetails->id,'','OR');
                            $doothan_earningDetails = Request::model()->findAll($criteria);
                            $doothan_earning_data = array();
                            $dropbox_earning_data = array();
                            foreach($doothan_earningDetails as $earning){
                                if($earning->doothan_id==$userdetails->id){
                                    $doothan_earning_data[]=$earning->doodhan_fee;
                                }
                                if($earning->dropbox_id==$userdetails->id){
                                    $dropbox_earning_data[]=$earning->dropbox_fee;
                                }
                            }
                            $doothan_earn = array_sum($doothan_earning_data);
                            $dropbox_earn = array_sum($dropbox_earning_data);
                            $earn = $doothan_earn+$dropbox_earn;
                        }else{
                            $earning_data = array();
                            $earningDetails = Request::model()->findAllByAttributes(array('doothan_id'=>$userdetails->id));
                            foreach($earningDetails as $earning){
                                $earning_data[]= $earning->doodhan_fee;
                            }
                            if(count($earning_data)>0){
                                $earn = array_sum($earning_data);
                            }else{
                                $earn = 0;
                            }
                        }
                    }else{
                        $earn = "";
                    }
                    $payment = new PaymentStatus;
                    $payment->firstname = $last_array->firstname;
                    $payment->booking_id = $_POST['order_id'];
                    $payment->transaction_id = $last_array->txnid;
                    $payment->amount = $last_array->amount;
                    $payment->productinfo = $last_array->productinfo;
                    $payment->mode = $last_array->mode;
                    $payment->bankcode = $last_array->bankcode;
                    $payment->bank_ref_num = $last_array->bank_ref_num;
                    $payment->status = $last_array->status;
                    $payment->created_on = date('Y-m-d H:i:s');
                    if($payment->save()){
                        $order_details->payment_date = date('Y-m-d H:i:s');
                        $order_details->save(false);
                        $result = array('message'=>'success');
                    }else{
                        $order_details->dropbox_fee = 0;
                        $order_details->doodhan_fee = 0;
                        $order_details->save(false);
                        $result = array('message'=>'failed');
                    }
                    $settings = Settings::model()->find();
                    
                    /*$mail = new SesMailer();
                    $mail->setView('payment_success');
                    $mail->setData(
                        array(
                            'name' => $userdetails->first_name,'requestDetails'=>$order_details,'header_subject'=>$header_subject,'msg_body'=>$msg_body 
                        )
                    );
                    $mail->setFrom('receipts@doothan.in',$settings->from_name);
                    $mail->setTo($userdetails->email, $userdetails->first_name);
                    $mail->setSubject('DOOTHAN : Payment Completed');
                    $mail->Send();*/
                    $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$order_details->doothan_id),array('order'=>'id DESC'));
                    $device_token = ($device_token_data[0]->device_id)?$device_token_data[0]->device_id:$device_token_data[1]->device_id;
                    $result_msg['data']['email_id']=$user_details->email;
                    $result_msg['data']['name']=$user_details->first_name;
                    $result_msg['data']['order_id']=$order_details->id;
                    
                    $result_msg['data']['order_code']=$order_details->request_code;
                    $result_msg['data']['phone']=$user_details->phone;
                    $result_msg['data']['item_t']=$order_details->product_price;
                    $result_msg['data']['service_c']=$order_details->service_charge;
                    $result_msg['data']['discount_c']=$order_details->discount;
                    $result_msg['data']['tax_value']=$order_details->gst;
                    $result_msg['data']['applicable_t']="";
                    $result_msg['data']['total']=$order_details->amount;
                    $userAddressDetails = UserAddress::model()->findByAttributes(array('user_id'=>$order_details->dropbox_id));
                    $drop_address  = $userAddressDetails->address.' '.$userAddressDetails->city;
                    $result_msg['data']['type']="4";
                    $result_msg['data']['user_id']=$order_details->user_id;
                    $result_msg['data']['dropbox']=wordwrap($drop_address, 30, "\n",true);
                    $result_msg['data']['role']=$user_details->member_type;
                    $result_msg['data']['doothan']="";
                    $result_msg['data']['status_text'] = "";
                    $result_msg['data']['user_address'] = "";
                    $result_msg['data']['service_fee'] = "";
                    $result_msg['data']['earn'] = $earn;
                    $result_msg['data']['product_name']=$order_details->item_details;
                    if($last_array->status=="success"){
                        $result=Common::sendPushNotification($device_token, $result_msg);
                    }else{
                        $result = array('message'=>'failed');
                    }
                    $this->renderJSON(array('status' => true,'request_code'=>$order_details->request_code,'message'=>json_decode($result)));
                }else{
                    $result = array('message'=>'failed');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('message'=>'Order Not Found');
                $this->renderJSON($result);
            }
        }
    }
    
    
    public function actionCancelRequest(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if($_POST['order_id']){
                $requestDetails = Request::model()->findByPk($_POST['order_id']);
                if($requestDetails){
                    $requestDetails->status="Cancelled";
                    $requestDetails->dropbox_fee = 0;
                    $requestDetails->doodhan_fee = 0;
                    if($requestDetails->save(false)){
                        $userDetails = Users::model()->findByPk($requestDetails->user_id);
                        $settings = Settings::model()->find();
                        /*$mail = Yii::app()->Smtpmail;
                        $mail->SetFrom("info@doothan.in",$settings->from_name);
                        $mail->Subject = 'DOOTHAN : Order Cancelled';
                        $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                        $mail->MsgHTML($this->render('/mail/cancel_request',array('name' => $name,'order_code'=>$requestDetails->request_code),true));
                        $mail->AddAddress($userDetails->email);
                        $mail->AddReplyTo("info@doothan.in",$settings->from_name);
                        $mail->SMTPDebug = 0;
                        $mail->SMTPSecure = 'tls';
                        if (!$mail->Send()) {
                            $result = array('message'=>'Request successfully cancelled');
                            $this->renderJSON($result);
                        }else{
                            $result = array('message'=>'Request successfully cancelled, Error while senting mail');
                            $this->renderJSON($result);
                        }*/
                        $mail = new SesMailer();
                        $mail->setView('cancel_request');
                        $mail->setData(array('name' => $userDetails->first_name,'order_code'=>$requestDetails->request_code));
                        $mail->setFrom('info@doothan.in',$settings->from_name);
                        $mail->setTo($userDetails->email, $userDetails->first_name);
                        $mail->setSubject('DOOTHAN : Order Cancelled');
                        if (!$mail->Send()) {
                            $result = array('message'=>'Request successfully cancelled, Error while senting mail');
                            $this->renderJSON($result);
                        }else{
                            $result = array('message'=>'Request successfully cancelled');
                            $this->renderJSON($result);
                        }
                    }else{
                        $result = array('message'=>'Request cancel failed');
                        $this->renderJSON($result);
                    }
                }else{
                    $result = array('message'=>'Request not found');
                    $this->renderJSON($result);
                }
                return $htm;
            }
        }else{
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }
    
    public function actionCheckPage(){
        
    }
    
    public function actionWaitfordoothan(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if($_POST['order_id']){
                $requestDetails = Request::model()->findByPk($_POST['order_id']);
                if($requestDetails){
                    $requestDetails->doothan_waiting_hours=$_POST['hours'];
                    $requestDetails->updated_on=$_POST['request_date'];
                    if($requestDetails->save(false)){
                        $result = array('message'=>'success');
                        $this->renderJSON($result);
                    }else{
                        $result = array('message'=>'error');
                        $this->renderJSON($result);
                    }
                }else{
                    $result = array('message'=>'Request not found');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('message'=>'Invalid order id');
                $this->renderJSON($result);
            }
        }else{
            $result = array('message'=>'Invalid token');
            $this->renderJSON($result);
        }
    }
    
    public function actionDeliveryItem(){
        //error_reporting(E_ALL);
        //ini_set('display_errors', 'On');
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $requestDetails = Request::model()->findByPk($_POST['order_id']);
            $doothan_details = Users::model()->findByPk($requestDetails->doothan_id);
            $dropbox_details = Users::model()->findByPk($requestDetails->dropbox_id);
            $settings = Settings::model()->find();
            if($requestDetails){
                    if($_POST['type']==0){
                        $status = "Delivered to dropbox";
                        $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$requestDetails->dropbox_id),array('order'=>'id DESC'));
                        $device_token_data_requester = OauthTokens::model()->findAllByAttributes(array('user_id'=>$requestDetails->user_id),array('order'=>'id DESC'));
                    }else if($_POST['type']==1){
                        $de = $requestDetails->coupon_amount+$requestDetails->discount;
                        $bc = $requestDetails->rate_per_km * $requestDetails->distance;
                        $abc = $charge+$bc;
                        $last_total = $abc-$de+$requestDetails->weight;
                        $one_way_distance=$requestDetails->distance/2;
                        $doothan_flat_value = ($last_total * 20)/100;
                        $doothan_flat_value = number_format((float)$doothan_flat_value, 2, '.', '');
                        $doothan_calculation_value = $one_way_distance * $requestDetails->rate_per_km + 5;
                        $doothan_calculation_value = number_format((float)$doothan_calculation_value, 2, '.', '');
                        $requestDetails->doodhan_fee = max(array($doothan_first_value,$doothan_calculation_value));
                        $status = "Received to dropbox";
                        $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$requestDetails->user_id),array('order'=>'id DESC'));
                    }else if($_POST['type']==2){
                        $status = "Delivered";
                        $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$requestDetails->user_id),array('order'=>'id DESC'));
                        $requestDetails->delivery_date = date('Y-m-d H:i:s');
                        $requestDetails->dropbox_fee='15';
                    }else{
                        $status = 'Delivered to user';
                        $requestDetails->delivery_date = date('Y-m-d H:i:s');
                    }
                    if($_POST['type']==0){
                        $userDetails = Users::model()->findByPk($requestDetails->dropbox_id);
                        $NomraluserDetails = Users::model()->findByPk($requestDetails->user_id);
                    }else{
                        $userDetails = Users::model()->findByPk($requestDetails->user_id);
                    }
                    $device_token = $device_token_data[0]->device_id;
                    $requestDetails->status = $status;
                    $requestDetails->save(false);
                    $result_msg = array();
                    $result_msg['data']['order_id']=$requestDetails->id;
                    $result_msg['data']['user_id']=$userDetails->id;
                    $result_msg['data']['email_id']=$userDetails->email;
                    $result_msg['data']['order_code']=$requestDetails->request_code;
                    $result_msg['data']['type']=$_POST['type'];
                    $result_msg['data']['doothan']=wordwrap($doothan_details->first_name.",".$doothan_details->phone.",".$doothan_details->user_address->city, 30, "\n",true);
                    $result_msg['data']['dropbox']=wordwrap($dropbox_details->first_name." ".$dropbox_details->phone." ".$dropbox_details->user_address->city, 30, "\n",true);
                    $result_msg['data']['product_name']=$requestDetails->item_details;
                    $result_msg['data']['status_text'] = $requestDetails->status;
                    $result_msg['data']['user_address'] = "";
                    $result_msg['data']['service_fee'] = "";
                    $result_msg['data']['role']=$userDetails->member_type;
                    if($_POST['type']=="0"){
                        $result_msgs = array();
                        $result_msgs['data']['order_id']=$requestDetails->id;
                        $result_msgs['data']['user_id']=$NomraluserDetails->id;
                        $result_msgs['data']['email_id']=$NomraluserDetails->email;
                        $result_msgs['data']['order_code']=$requestDetails->request_code;
                        $result_msgs['data']['type']=$_POST['type'];
                        $result_msgs['data']['doothan']=wordwrap($doothan_details->first_name.",".$doothan_details->phone.",".$doothan_details->user_address->city, 30, "\n",true);
                        $result_msgs['data']['dropbox']=wordwrap($dropbox_details->first_name." ".$dropbox_details->phone." ".$dropbox_details->user_address->city, 30, "\n",true);
                        $result_msgs['data']['product_name']=$requestDetails->item_details;
                        $result_msgs['data']['status_text'] = $requestDetails->status;
                        $result_msgs['data']['user_address'] = "";
                        $result_msgs['data']['service_fee'] = "";
                        $result_msgs['data']['role']=$NomraluserDetails->member_type;
                        $device_id_second = ($device_token_data_requester[0]->device_id)?$device_token_data_requester[0]->device_id:$device_token_data_requester[1]->device_id;
                        
                        /*$mails = Yii::app()->Smtpmail;
                        $mails->SetFrom("info@doothan.in",$settings->from_name);
                        $mails->Subject = "DOOTHAN : Doothan delivered item to dropbox";;
                        $name = $NomraluserDetails->first_name." ".$NomraluserDetails->last_name;
                        
                        $mails->MsgHTML($this->render('/mail/notify_user',array('name' => $name,'content'=>$content),true));
                        $mails->AddAddress($NomraluserDetails->email);
                        $mails->AddReplyTo("info@doothan.in",$settings->from_name);
                        //$mail->AddCC($addcc);
                        $mails->SMTPDebug = 0;
                        $mails->SMTPSecure = 'tls';
                        $mails->Send();*/
                        
                        $mails = new SesMailer();
                        $content = "Your order ".$requestDetails->request_code." item has been delivered at ". $dropbox_details->first_name ." shop";
                        $mails->setView('notify_user');
                        $mails->setData(
                            array(
                                'name' => $NomraluserDetails->first_name,'content'=>$content
                            )
                        );
                        $mails->setFrom('info@doothan.in',$settings->from_name);
                        $mails->setTo($NomraluserDetails->email, $NomraluserDetails->first_name);
                        $mails->setSubject('DOOTHAN : Doothan delivered item to dropbox');
                        $mails->Send();
                        
                        $result=Common::sendPushNotification($device_token, $result_msg);
                        $result=Common::sendPushNotification($device_id_second, $result_msgs);
                        $this->renderJSON(array('status' => 'true','message'=>json_decode($result)));
                    }else{
                        $mails = new SesMailer();
                        $mails->setFrom('info@doothan.in',$settings->from_name);
                        $name = $NomraluserDetails->first_name." ".$NomraluserDetails->last_name;
                        if($_POST['type']=="1"){
                            $mails->Subject = "Item has been received to dropbox";
                            $content = "Your order ".$requestDetails->request_code." has been received at ". $dropbox_details->first_name ." shop, Please collect the item immediately";
                            $mails->setSubject('DOOTHAN : Dropbox received item from doothan');
                        }else{
                            $mails->Subject = "Item has been delivered";
                            $content = "Your order ".$requestDetails->request_code." has been delivered to you";
                            $mails->setSubject('DOOTHAN : Dropbox delivered item to user');
                        }
                        //$mails->MsgHTML($this->render('/mail/notify_user',array('name' => $name,'content'=>$content),true));
                        $mails->setView('notify_user');
                        $mails->setData(
                            array(
                                'name' => $NomraluserDetails->first_name,'content'=>$content
                            )
                        );
                        $mails->setFrom('info@doothan.in',$settings->from_name);
                        $mails->setTo($NomraluserDetails->email, $NomraluserDetails->first_name);
                        
                        //$mails->AddAddress($NomraluserDetails->email);
                        //$mails->AddReplyTo("info@doothan.in",$settings->from_name);
                        //$mail->AddCC($addcc);
                        //$mails->SMTPDebug = 0;
                        //$mails->SMTPSecure = 'tls';
                        $mails->Send();
                        $result=Common::sendPushNotification($device_token, $result_msg);
                        $this->renderJSON(array('status' => 'true','message'=>json_decode($result)));
                    }
                //}
            }else{
                $result = array('status' => 'false','message'=>'Request not found');
                $this->renderJSON($result);
            }
        }else{
            $result_msg = array('status' => 'false','message'=>'Invalid token');
            $this->renderJSON($result_msg);
        }
    }
    
    public function actionRating(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if($_POST['request_id']){
                $requestDetails = Request::model()->findByPk($_POST['request_id']);
                if($requestDetails){
                    $rating = new RequestRating;
                    $rating->request_id = $_POST['request_id'];
                    $rating->doothan_rating = $_POST['doothan_rating'];
                    $rating->dropbox_rating = $_POST['dropbox_rating'];
                    $rating->created_at = date('Y-m-d H:i:s');
                    if($rating->save(false)){
                        $result = array('status' => 'true','message'=>'Rating submitted');
                        $this->renderJSON($result);
                    }else{
                        $result = array('status' => 'false','message'=>'Error while rating');
                        $this->renderJSON($result);
                    }
                }else{
                    $result = array('status' => 'false','message'=>'Request not found');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('status' => 'false','message'=>'Invalid request');
                $this->renderJSON($result);
            }
        }else{
            $result_msg = array('status' => 'false','message'=>'Invalid token');
            $this->renderJSON($result_msg);
        }
    }
    
    public function actionGetDoothans(){
        $list= Yii::app()->db->createCommand('SELECT * FROM `users` where member_type="doothan" and status=2 and account_status="APPROVED" and travel_from_to="Yes" and (mode_of_commute="Bike" OR mode_of_commute="Car" OR mode_of_commute="Bus")Order By Case mode_of_commute When "Bike" Then 1 When "Car" Then 2 When "Bus" Then 3 Else 4 End')->queryAll();
        $ordr_details  = Request::model()->findByPk(1);
        //echo "<pre>";print_r($list);die;
        foreach($list as $user){
            $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$user->id),array('order'=>'id DESC'));
            $device_token = "cH7QgR6TjUU:APA91bFsHvFpc8zeiQcYzKwYDPzZpei8l9D5mTS8hnDk6mz3Jf7JlulfYz9B8pG2mTQ7teFNiVLfhrjqYewZ7dRvZ3no8xBir6fP9_3bpDvSRaXf630_GT9rQ-w43BB3AN8UKBbPt0bZ";
            $result_msg = array();
            $result_msg['data']['order_id']=$ordr_details->id;
            $result_msg['data']['order_code']=$ordr_details->request_code;
            $result_msg['data']['product_name']=$ordr_details->item_details;
            $result_msg['data']['user_id']=$user->id;
            $result_msg['data']['email_id']=$user->email;
            $result=Common::sendPushNotification($device_token, $result_msg);
        }
        $this->renderJSON(array('status' => 'true','message'=>json_decode($result)));
    }
    public function actionAssignDoothan(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            if($_POST['request_id']){
                $requestDetails = Request::model()->findByPk($_POST['request_id']);
                if($requestDetails){
                    if($requestDetails->doothan_id==0){
                        $requestDetails->doothan_id=$_POST['user_id'];
                        if($requestDetails->save(false)){
                            $result = array('status' => 'true','message'=>'Successfully assigned doothan');
                            $this->renderJSON($result);
                        }else{
                            $result = array('status' => 'false','message'=>'Error while assign doothan');
                            $this->renderJSON($result);
                        }
                    }else{
                        $result = array('status' => 'false','message'=>'Doothan is assigned first come first server basis. Another Doothan has accepted the request, please try again next time');
                        $this->renderJSON($result);
                    }
                }else{
                    $result = array('status' => 'false','message'=>'Request not found');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('status' => 'false','message'=>'Invalid request');
                $this->renderJSON($result);
            }
        }else{
            $result_msg = array('status' => 'false','message'=>'Invalid token');
            $this->renderJSON($result_msg);
        }
    }
    
    /*public function actionTestSES(){
        $mail = new SesMailer();
        $mail->setView('welcome');
        $mail->setData(
            array(
                'name' => 'Achu'
            )
            );
        $mail->setFrom('info@doothan.in',"Admin");
        $mail->setTo("achu.xtapps@gmail.com", "Achu");
        $mail->setSubject('DOOTHAN : Welcome to Doodhan');
        
        if (!$mail->Send()) {
            //mail error
        }
    }*/
    
    public function actionNotifyAdmin(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $userDetails = Users::model()->findByPk($_POST['user_id']);
            if($userDetails){
                $notification  = new Notifications;
                $notification->doothan_id = $_POST['user_id'];
                $notification->notification = $_POST['content'];
                $notification->created_at = date('Y-m-d H:i:s');
                $notification->save(false);
                $designation = ucfirst($userDetails->member_type);
                $settings = Settings::model()->find();
                
                /*$mail = Yii::app()->Smtpmail;
                $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                $mail->SetFrom($userDetails->email,$name);
                $mail->Subject = 'DOOTHAN : Notify Admin';
                $admin_name = $settings->from_name;
                $mail->MsgHTML($this->render('/mail/notify_admin',array('name' => $admin_name,'username'=>$name,'content'=>$_POST['content'],'designation'=>$designation),true));
                $mail->AddAddress("info@doothan.in");
                $mail->AddReplyTo($userDetails->email,$name);
                $mail->SMTPDebug = 0;
                $mail->SMTPSecure = 'tls';*/
                
                $mail = new SesMailer();
                $mail->setView('notify_admin');
                $name = $userDetails->first_name . ' ' . $userDetails->last_name;
                $mail->setData(array('name' => $admin_name,'username'=>$name,'content'=>$_POST['content'],'designation'=>$designation));
                $mail->setFrom($userDetails->email,$name);
                $mail->setTo("info@doothan.in",$settings->from_name);
                $mail->setSubject('DOOTHAN : Notify Admin');
                if (!$mail->Send()) {
                    $result = array('status'=>'false','message'=>'Error while sent notification to admin');
                    $this->renderJSON($result);
                }else{
                    $result = array('status'=>'true','message'=>'Notification mail successfully sent');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('status' =>false,'message'=>'User Not found');
            }
            $this->renderJSON($result);
            $this->refresh(true);
        } else {
            //throw new CHttpException(403, 'invalid_token');
            $result = array('status' =>false,'message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    
    public function actionProfileDescription(){
        if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {
            $user = Users::model()->findByPk($_POST['user_id']);
            $result_array = array();
            if($user){
                $data = array();
                /*if($user->member_type!="requester"){
                    if($user->member_type=="dropbox" && $user->role_change_to=='1,2'){
                        $criteria=new CDbCriteria;
                        $criteria->compare('doothan_id',$user->id);
                        $criteria->compare('dropbox_id',$user->id,'','OR');
                        /*$doothan_earningDetails = Request::model()->findAll($criteria);
                        $doothan_earning_data = array();
                        $dropbox_earning_data = array();
                        foreach($doothan_earningDetails as $earning){
                            if($earning->doothan_id==$user->id){
                                $doothan_earning_data[]=$earning->doodhan_fee;
                            }
                            if($earning->dropbox_id==$user->id){
                                $dropbox_earning_data[]=$earning->dropbox_fee;
                            }
                        }
                        $doothan_earn = array_sum($doothan_earning_data);
                        $dropbox_earn = array_sum($dropbox_earning_data);
                        $earn = $doothan_earn+$dropbox_earn;
                        
                    }else{
                        if($user->member_type=="doothan"){
                            $member = "doothan_id";
                            $fee_text = "doodhan_fee";
                        }else{
                            $member = "dropbox_id";
                            $fee_text = "dropbox_fee";
                        }
                        $earning_data = array();
                        $earningDetails = Request::model()->findAllByAttributes(array($member=>$user->id));
                        foreach($earningDetails as $earning){
                            $earning_data[]= $earning->$fee_text;
                        }
                        if(count($earning_data)>0){
                            $earn = array_sum($earning_data);
                        }else{
                            $earn = 0;
                        }
                        $earnArray = array();
                        $earningData = Fee::model()->findAllByAttributes(array('user_id'=>$user->id));
                        if($earningData){
                            $earnArray[] = $earningData->amount;
                        }
                    }
                }else{
                    $earn = "";
                }*/
                $earnArray = array();
                $earningData = Fee::model()->findAllByAttributes(array('user_id'=>$user->id));
                if($earningData){
                    foreach($earningData as $earning){
                        $earnArray[] = $earning->amount;
                    }
                }
                $data['earning'] = array_sum($earnArray);
                $data['role']=$user->member_type;
                $data['login_state']=$user->login_state;
                $data['role_upgrade']=$user->role_change_to;
                array_push($result_array, $data);
                $this->renderJSON($result_array);
            }else{
                $result = array('status' =>false,'message'=>'User Not found');
                $this->renderJSON($result);
            }
        }else{
            $result = array('status' =>false,'message'=>'invalid_token');
            $this->renderJSON($result);
        }
    }
    public function actionCheckEmailExist(){
        $userDetails = Users::model()->findByAttributes(array('email'=>$_POST['email']));
        if($userDetails){
            $result = array('status' =>'false','message'=>'Already exist');
            $this->renderJSON($result);
        }else{
            $result = array('status' =>'true','message'=>'Proceed');
            $this->renderJSON($result);
        }
    }
    
    public function actionGetCurrentLocation(){
        /*if ($tokenDetails = OauthTokens::getTokenInfo($_POST['access_token'])) {*/
            if(isset($_POST['user_id'])){
                $userDetails = Users::model()->findByPk($_POST['user_id']);
                if($userDetails){
                    $userDetails->current_location = $_POST['current_pin'];
                    $userDetails->current_city = $_POST['city'];
                    if($userDetails->save(false)){
                        $result = array('status' =>'true');
                        $this->renderJSON($result);
                    }else{
                        $result = array('status' =>'false');
                        $this->renderJSON($result);
                    }
                }else{
                    $result = array('status' =>'false');
                    $this->renderJSON($result);
                }
            }else{
                $result = array('status' =>'false');
                $this->renderJSON($result);
            }
        /*}else{
            $result = array('status' =>false,'message'=>'invalid_token');
            $this->renderJSON($result);
        }*/
    }
    
    public function actionGetStateAndDistrict(){
       ini_set('max_execution_time', 300000); //300 seconds = 5 minutes
        $dropboxList = Users::model()->findAllByAttributes(array('member_type'=>'doothan'));
        //echo "<pre>";print_r($dropboxList);die;
        if(count($dropboxList)>0){
            foreach($dropboxList as $drop){
                $dropboxPin = UserAddress::model()->findByAttributes(array('user_id'=>$drop->id));
                $dropboxpincode = $dropboxPin->postal_code;
                //echo $dropboxpincode;die;
                $district = Helper::getDistrict($dropboxpincode);
                if($district){
                    //echo $district;die;
                    $dropboxPin->district = $district;
                    if($dropboxPin->save(false)){
                        echo "district=>".$district."</br>";
                    }else{
                        print_r($dropboxPin->getErrors());die;
                    }
                }
            }
        }
     }

     public function actionGetStateAndDistrictDropbox(){
        ini_set('max_execution_time', 300000); //300 seconds = 5 minutes
         $dropboxList = Users::model()->findAllByAttributes(array('member_type'=>'dropbox'));
         //echo "<pre>";print_r($dropboxList);die;
         if(count($dropboxList)>0){
             foreach($dropboxList as $drop){
                 $dropboxPin = UserAddress::model()->findByAttributes(array('user_id'=>$drop->id));
                 $dropboxpincode = $dropboxPin->postal_code;
                 //echo $dropboxpincode;die;
                 $district = Helper::getDistrict($dropboxpincode);
                 if($district){
                     //echo $district;die;
                     $dropboxPin->district = $district;
                     if($dropboxPin->save(false)){
                         echo "district=>".$district."</br>";
                     }else{
                         print_r($dropboxPin->getErrors());die;
                     }
                 }
             }
         }
      }

    /*public function actionScrptFile(){
        ini_set('max_execution_time', 300000); //300 seconds = 5 minutes
        for($i=1232;$i<=5000;$i++){
            $sql = "insert into users (id, username,password,first_name,last_name,email,country_code,phone,dob,profession,status,member_type,gender,created,updated,account_status,login_state,aadhar,photo_id,photo_number,aadhar_number,office_address,travel_from_to,mode_of_commute,shop_location,shop_phone,working_hours) values (:id, :username,:password,:first_name,:last_name,:email,:country_code,:phone,:dob,:profession,:status,:member_type,:gender,:created,:updated,:account_status,:login_state,:aadhar,:photo_id,:photo_number,:aadhar_number,:office_address,:travel_from_to,:mode_of_commute,:shop_location,:shop_phone,:working_hours)";
            $password = "$2y$10$h0rsxgXh6N./mu/w3gOmEeOFebuvoV0Nl.lHa0hBFJUOiKJsLLoCm";
            $phone = "8606382262";
            if($i<750){
                $member_type="requester";
                $aadhar="";
                $photo_id="";
                $photo_number="";
                $adhar_number="";
                $office_address="";
                $travel="";
                $mode="";
                $shop_location="";
                $shop_phone="";
                $working_hours="";
            }else if($i>1232 && $i<2168){
                $member_type = "doothan";
                $aadhar="2954fc64-22bd-55d5-98d6-92f098b1675d.jpg";
                $photo_id="9478dbcf-46e7-56bf-a344-ebcf7d606c1b.jpg";
                $photo_number="123456789012";
                $adhar_number="B179900000";
                $office_address="12, M&M Building, (opp. MR college), GB Road";
                $travel="Yes";
                if($i>1232 && $i<1346){
                    $mode="Bike";
                }elseif($i>1347 && $i<1450){
                    $mode="Car";
                }else{
                    $mode="Bus";
                }
                $shop_location="";
                $shop_phone="";
                $working_hours="";
            }else{
                $member_type = "dropbox";
                $aadhar="2954fc64-22bd-55d5-98d6-92f098b1675d.jpg";
                $photo_id="9478dbcf-46e7-56bf-a344-ebcf7d606c1b.jpg";
                $photo_number="Z3533013";
                $adhar_number="611292992289";
                $office_address="chockli,kannur";
                $travel="Yes";
                $mode="";
                $shop_location="Thrissur";
                $shop_phone="9847443458";
                $working_hours="6 : 30 AM-6 : 25 PM";
            }
            $parameters = array(":id"=>$i, ":username"=>"doothan_".$i."@gmail.com",":password"=>$password,":first_name"=>'Doothan_'.$i,":last_name"=>"Dev".$i,":email"=>"doothan_".$i."@gmail.com",":country_code"=>"+91",":phone"=>$phone,":dob"=>"11-11-1991",":profession"=>"Software Developer",":status"=>2,":member_type"=>$member_type,":gender"=>"Male",":created"=>"2018-01-22 05:33:06",":updated"=>"2018-01-22 05:33:06",":account_status"=>"APPROVED",":login_state"=>"3",":aadhar"=>$aadhar,":photo_id"=>$photo_id,":photo_number"=>$photo_number,":aadhar_number"=>$adhar_number,":office_address"=>$office_address,":travel_from_to"=>$travel,":mode_of_commute"=>$mode,":shop_location"=>$shop_location,":shop_phone"=>$shop_phone,":working_hours"=>$working_hours);
            $list = Yii::app()->db->createCommand($sql)->execute($parameters);
                $address_sql = "insert into user_address(id,created,address,state,country,postal_code,city,user_id,status) value (:id,:created,:address,:state,:country,:postal_code,:city,:user_id,:status)";
                $address_parameters = array(":id"=>$i,":created"=>"2018-01-18 11:58:58",":address"=>"kochi infopark",":state"=>"Kerala",":country"=>'India',":postal_code"=>"682030",":city"=>'Kakkand',":user_id"=>$list,":status"=>'1');
                Yii::app()->db->createCommand($address_sql)->execute($address_parameters);
                sleep(3);
        }
    }*/
}

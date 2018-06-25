<?php
use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class UsersController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $page_title = 'Requestors management';
    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view'),
                'users' => array('@'),
            ),
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'update'),
                'users' => array('@'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete', 'PageLoad','Payments','PayNow', 'Afterupdatefee', 'activate','banned', 'deactivate','paymentactivate','customercontactupdate','paymentview','paymentstatistics', 'customer','bookings','customerDelete','customerviewupdation','Appointments','bookingsview','testing','edituseraddress','DeleteUserAddress','AddUserAddress','imagetemplate','cancelbkng','UpdateAccountStatus','CustomerView','CustomerDocs'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id) {
        $userAddressModel = new UserAddress('search');
        $feeModel  = new Fee('search');
        $FeeModel->user_id = $id;
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        $usrAddressDetails    = Users::getUserAddress($id);
        $userAddressModel->user_id     = $id;
        $addressDetails = UserAddress::model()->findAllByAttributes(array(
                    'user_id' => $id,'status' => 1 
        ));
        $user = Users::model()->findByPk($id);
        $this->render('view', array(
            'model' => $this->loadModel($id),
            'usersId' => $id,
            'bookingmodel' => $BookingsModel,
            'totalCounts' => $totalCustomerBkngs,
            'cancelledBkngs' => $cancellBkngDetails, 
            'therapistOnway' => $therapistOnway,
            'usrCompletedBkngs' => $usrCompletedBkngs,
            'usrReviewDetails' => $usrReviewDetails,
            'usrAddressDetails' => $usrAddressDetails,
            'promoModel' => $promoModel,
            'promocodeModel' => $promocodeModel,
            'total_credits'=>$totalcredits,
            'userAddressModel' => $userAddressModel,
            'addressDetails' => $addressDetails,
            'feeModel'=>$feeModel
        ));
    }
    

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate() {
        $model = new Users;
        $this->page_title ='Requestor management';

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $model->scenario = 'registration';
            $model->username = $_POST['Users']['email'];

            $userDetails = Users::model()->findAllByAttributes(array('email'=>$_POST['Users']['email']));
            if(!$userDetails){
                $randomInviteCode   = Yii::app()->getSecurityManager()->generateRandomString(6);
                $randomInviteCode   = 'RF'.$randomInviteCode;
                $model->invite_code = $randomInviteCode;
                $model->member_type = 0;
                $model->status = 2;
                //$model->dummy_date     = date('Y-m-d h:i:s a');
                $uploadedFile=CUploadedFile::getInstance($model,'image');
                                
                $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
                $fileName  = Uuid::uuid5(Uuid::uuid4(), '597012a1-be5f-52fe-ac71-617f84ee84bc' . 'users')->toString();  
                $model->image = $fileName.'.'.$ext;

                if($uploadedFile){
                   if ($model->save())
                    {
                            $uploadedFile->saveAs(Yii::app()->basePath.'/../uploads/profile-pictures/'.$fileName);
                             // $client = S3Client::factory(array(
                             //                    'key' => Yii::app()->params['awsKey'],
                             //                    'secret' => Yii::app()->params['awsSecret'],
                             //                    'region' => Yii::app()->params['awsRegion'],
                             //        ));

                             //        $client->putObject(array(
                             //            'Bucket' => Yii::app()->params['profileImageBucket'],
                             //            'Key' => $fileName . '.' . $ext,
                             //            'SourceFile' => Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName,
                             //            'ACL' => 'public-read-write',
                             //            "Cache-Control" => "max-age=94608000",
                             //            "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                             //        ));
//                                   
                                    @unlink(Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName);
                            Yii::app()->user->setFlash('success', 'Requestor created succesfully');
                            $this->redirect(array('users/index'));  
                    } else {
                        //var_dump($model->getErrors()); die();
                    } 
                }else{
                    Yii::app()->user->setFlash('error', 'please upload Requestor image!!!');
                }
                
             }else{

                Yii::app()->user->setFlash('error', 'email already exist');
             }
         }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
        $this->page_title ='Requestor management';
        $addressDetails = UserAddress::model()->findAllByAttributes(array(
            'user_id' => $model->id, 'is_default' => 1,
        ));
        $addressId  = $addressDetails[0]->id;
        $addressModel = UserAddress::model()->findByPk($addressId);
        $addressModel->setScenario('admin_profile_update');
        $addressModel->scenario = 'admin_profile_update';
        $FeeModel  = new Fee('search');
        $FeeModel->user_id = $id;
        // echo "<pre>";
        //print_r($_POST['Users']);die;
        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $model->username = $_POST['Users']['email'];
        }
        
         if (isset($_POST['UserAddress'])) {
           $addressModel->attributes = $_POST['UserAddress'];
         }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $uploadedFile=CUploadedFile::getInstance($model,'image');
        //print_r($uploadedFile);die;
        if($uploadedFile){
            $fileName  = Uuid::uuid5(Uuid::uuid4(), $model->id . 'profile')->toString(); 
            //echo $fileName;die;
            $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
            //echo $ext;die;
            $model->image = $fileName.'.'.$ext;
            //echo $model->image;die;
                    /*$big = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'profile-pic/' . $fileName);
                    $big->adaptiveResize(200, 200);
                    $big->save(Yii::app()->params['uploadPath'] . 'profile-pic/' . $fileName);
                    
                    $thumb = Yii::app()->phpThumb->create(Yii::app()->params['uploadPath'] . 'profile-pic/' . $fileName);
                    $thumb->adaptiveResize(80, 80);
                    $thumb->save(Yii::app()->params['uploadPath'] . 'profile-pic/' . 'thumb_' . $fileName);*/
                    
                    $client = S3Client::factory(array(
                        'key' => Yii::app()->params['awsKey'],
                        'secret' => Yii::app()->params['awsSecret'],
                        'region' => Yii::app()->params['awsRegion'],
                    ));
                    
                    $client->putObject(array(
                        'Bucket' => Yii::app()->params['profileImageBucket'],
                        'Key' => $fileName . '.' . $ext,
                        'SourceFile' => Yii::app()->params['uploadPath'] . 'profile-pic/' . $fileName,
                        'ACL' => 'public-read',
                        "Cache-Control" => "max-age=94608000",
                        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                    ));
                    $client->putObject(array(
                        'Bucket' => Yii::app()->params['profileImageBucket'],
                        'Key' => 'thumb_' . $fileName . '.' . $ext,
                        'SourceFile' => Yii::app()->params['uploadPath'] . 'profile-pic/' . 'thumb_' . $fileName,
                        'ACL' => 'public-read',
                        "Cache-Control" => "max-age=94608000",
                        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                    ));
                    //@unlink(Yii::app()->params['uploadPath'] . 'profile-pic/' . $fileName);
                    //@unlink(Yii::app()->params['uploadPath'] . 'profile-pic/' . 'thumb_' . $fileName);
                    $model->attributes = $_POST['Users'];
                    $model->first_name = $_POST['Users']['first_name'];
                    $model->profession = $_POST['Users']['profession'];
                    $model->gender = $_POST['Users']['gender'];
                    $model->office_address = $_POST['Users']['office_address'];
                    $model->travel_from_to = $_POST['Users']['travel_from_to'];
                    $model->mode_of_commute = $_POST['Users']['mode_of_commute'];
                    $model->account_status = $_POST['Users']['account_status'];
                    $model->country_code = $_POST['Users']['country_code'];
                    $model->phone = $_POST['Users']['phone'];
                    if ($model->save())
                    {
                        Yii::app()->user->setFlash('success', 'Requestor updated succesfully');
                        $this->redirect(array('users/index?type='.$model->member_type));
                    } else {
                        Yii::app()->user->setFlash('success', 'Error while uploding image');
                        $this->redirect(array('users/index?type='.$model->member_type));
                    } 
            } else {
                        if (isset($_POST['Users'])) {
                            $model->attributes = $_POST['Users'];
                            $model->first_name = $_POST['Users']['first_name'];
                            $model->profession = $_POST['Users']['profession'];
                            $model->gender = $_POST['Users']['gender'];
                            $model->office_address = $_POST['Users']['office_address'];
                            $model->travel_from_to = $_POST['Users']['travel_from_to'];
                            $model->mode_of_commute = $_POST['Users']['mode_of_commute'];
                            $model->account_status = $_POST['Users']['account_status'];
                            $model->country_code = $_POST['Users']['country_code'];
                            $model->phone = $_POST['Users']['phone'];
                          if($model->validate() && $addressModel->validate()){
                             
                            if($addressModel->save() && $model->save()){
                               Yii::app()->user->setFlash('success', 'Requestor updated succesfully');
                               $this->redirect(array('users/index?type='.$model->member_type));
                            }
                          }
                        }
                }

        $this->render('update', array(
            'model' => $model,'addressModel'=>$addressModel,'FeeModel'=>$FeeModel
        ));
    }

public function actionPayments(){
    $this->layout=false;
    $model = new Fee;
    $userData = Users::model()->findByPk($_POST['user_id']);
    if($userData->member_type=="doothan"){
        $role_model = Request::model()->findByAttributes(array('doothan_id'=>$_POST['user_id']));
    }else{
        $role_model = Request::model()->findByAttributes(array('dropbox_id'=>$_POST['user_id']));
    }
    $this->render('payment_form',array('model'=>$model,'role_model'=>$role_model,'userData'=>$userData));
}

public function actionAfterupdatefee(){
    $this->layout=false;
    if($_POST['user_id']){
        $model = Users::model()->findByPk($_POST['user_id']);
        $feeModel  = new Fee('search');
        $feeModel->unsetAttributes();  // clear any default values
        $feeModel->user_id = $_POST['user_id'];
        if (isset($_GET['Fee']))
            $feeModel->attributes = $_GET['Fee'];
            $this->renderPartial('_payments',array('fee_model'=>$feeModel,'basic_model'=>$model));
    }
}
public function actionPayNow(){
    $this->layout=false;
    if(isset($_POST['Fee']))
    {
        $model=new Fee;
        $request_id = implode(',',$_POST['Fee']['request_id']);
        if($_POST['Fee']['mode']!=1){
            $model->mode = 0;
        }
        $model->attributes=$_POST['Fee'];
        $model->request_id = $request_id;
        $model->description = $_POST['Fee']['description'];
        if ($model->validate()){
            $model->created_at = date('Y-m-d H:i:s');
            if($model->save()){
                echo "1";
            }else{
                echo "0";   
            }
        }else{
            print_r($model->getErrors());
        }
    }
}
public function actioncustomercontactupdate($id) {
        $model = $this->loadModel($id);
        $this->page_title ='customer  management';

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $uploadedFile=CUploadedFile::getInstance($model,'image');
               
        
       
        if($uploadedFile){
            $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
            $fileName  = Uuid::uuid5(Uuid::uuid4(), '597012a1-be5f-52fe-ac71-617f84ee84bc' . 'users')->toString();  
            $model->image = $fileName.'.'.$ext;
            if ($model->save())
                {
                    $uploadedFile->saveAs(Yii::app()->basePath.'/../uploads/profile-pictures/'.$fileName);
                    $client = S3Client::factory(array(
                        'key' => Yii::app()->params['awsKey'],
                        'secret' => Yii::app()->params['awsSecret'],
                                                'region' => Yii::app()->params['awsRegion'],
                                    ));

                                    $client->putObject(array(
                                        'Bucket' => Yii::app()->params['profileImageBucket'],
                                        'Key' => $fileName . '.' . $ext,
                                        'SourceFile' => Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName,
                                        'ACL' => 'public-read-write',
                                        "Cache-Control" => "max-age=94608000",
                                        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                                    ));
//                                    
                                    @unlink(Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName);
                            Yii::app()->user->setFlash('success', 'user updated succesfully');
                            $this->redirect(array('contacts/Customercontacts'));  
                    } else {
                        //var_dump($model->getErrors()); 
                    } 
                    } else {
                        if (isset($_POST['Users'])) {
                            if ($model->save()) {
                                Yii::app()->user->setFlash('success', 'customer updated succesfully');
                                $this->redirect(array('contacts/Customercontacts'));
                            }
                        }
                }

        $this->render('update', array(
            'model' => $model,
        ));
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax'])) {
                Yii::app()->user->setFlash('success', 'customer deleted succesfully');
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('customer'));
            } else {
               // var_dump($model->getErrors()); 
            }
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex($type) {
        $model = new Users('search');
        if($type=="requester"){
            $this->page_title ='Requesters List';
            $model->user_title = "Requesters";
        }elseif($type=="dropbox"){
            $this->page_title ='Dropbox Owners List';
            $model->user_title = 'Dropbox Owners';
        }else if($type=="doothan"){
            $this->page_title ='Doothans List';
            $model->user_title = 'Doothans';
        }else{
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
        }
        $model->unsetAttributes();  // clear any default values
        $model->member_type = $type;
        if (isset($_GET['Users']))
            $model->attributes = $_GET['Users'];
            if(isset($_POST['flag'])&&$_POST['flag']=="flag"){
                $this->layout=false;
                $model->search_val=$_POST['pageCount'];
                $this->render('custom_admin', array(
                    'model' => $model,
                ));
            }else{
                $this->render('admin', array(
                    'model' => $model,
                ));
            }
    }
    public function actionUpdateAccountStatus(){
        $this->layout   = false;
        if(isset($_POST)){
            $user_id=$_POST['user_id'];
            $status=$_POST['value'];
            $send_mail="1";
            $model = Users::model()->findByPk($user_id);
            $past_status = $model->account_status;
            if($model){
                    $model->account_status=$status;
                    $status_message = "status has been changed to '$status'";
                    $subject = 'DOOTHAN : Account Status Changed';
                    if($status=="DOCUMENTS_PENDING"){
                        $status_text = 'DOCUMENTS_PENDING';
                        $status_description = 'Please upload valid documents to continue with us';
                        $login_state = 1;
                    }
                    else if($status=="CALL_VERIFICATION_PENDING"){
                        $status_text = 'CALL_VERIFICATION_PENDING';
                        $status_description = 'We will call you shortly to verify your details';
                        $login_state = 2;
                    }
                    else if($status=="APPROVED"){
                        if($model->aadhar=="" || $model->photo_id=="" || $model->photo_number==NULL || $model->aadhar_number==""){
                            $send_mail="0";
                        }else{
                            $status_text = 'APPROVED';
                            $status_description = 'We are happy to have you as a member';
                            $login_state = 3;
                            if($model->role_change_to_flag!=0){
                                $role = ($model->role_change_to_flag=="1")?"doothan":"dropbox";
                                $model->member_type = $role;
                                $model->role_change_to_flag = 0;
                                $status_message = "role update request to '$role' has been '$status'";
                                $status_description = 'We are happy to have you as a member, Your role has been changed to '.$role;
                                $subject = 'DOOTHAN : Account status changed and user role has been updated';
                            }
                        }
                    }
                    else{
                        if($past_status=="CALL_VERIFICATION_PENDING"){
                            $login_state = 1;
                            $status_description = 'Document rejected,Please upload it once more';
                        }else{
                            $login_state = 4;
                            $status_description = 'We are sorry to lose you as a member';
                        }
                        $status_text = 'REJECTED';
                        
                       // $login_state = 4;
                    }
                    if($send_mail=="1"){
                        $model->login_state=$login_state;
                        if($model->save(false)){
                            $settings = Settings::model()->find();
                            /*$mail = Yii::app()->Smtpmail;
                            $mail->SetFrom("info@doothan.in");
                            $mail->Subject = $subject;
                            $name = $model->first_name . ' ' . $model->last_name;
                            $mail->MsgHTML($this->render('/mail/change_login_status',array('name' => $name,'status_text'=>$status_text,'status_description'=>$status_description ),true));
                            $mail->AddAddress($model->email);
                            $mail->AddReplyTo("info@doothan.in",$settings->from_name);
                            //$mail->AddCC($addcc);
                            $mail->SMTPDebug = 0;
                            $mail->SMTPSecure = 'tls';*/
                            
                            $mail = new SesMailer();
                            $mail->setView('change_login_status');
                            $name = $model->first_name . ' ' . $model->last_name;
                            $mail->setData(array('name' => $name,'status_text'=>$status_text,'status_description'=>$status_description ));
                            $mail->setFrom('info@doothan.in',$settings->from_name);
                            $mail->setTo($model->email, $model->first_name);
                            $mail->setSubject($subject);
                            if (!$mail->Send()) {
                                Yii::app()->user->setFlash('success', "Error while sent mail to user, $status_message");
                                echo "2";
                            }else{
                                Yii::app()->user->setFlash('success', "Mail successfully sent, $status_message");
                                echo "1";
                            }
                       }else{
                        $msg    = $model->getErrors();
                        foreach ($msg as $message) {
                            Yii::app()->user->setFlash('error', $message[0]);
                            break;
                        }
                        echo "0";
                    }
                 }else{
                     echo "3";
                 }
            }
        }
    }

    /**
     * Manages all models.
     */
    public function actionAdmin() {
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        $model->member_type = 0;
        if (isset($_GET['Users']))
            $model->attributes = $_GET['Users'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = Users::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'users-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionActivate($id) {
        $model = $this->loadModel($id);
        $model->status = 2;

        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'User activated successfully!');
            $this->redirect(array('users/index?type='.$model->member_type));
        } else {
            $msg    = $model->getErrors();
            foreach ($msg as $message) {
                Yii::app()->user->setFlash('error', $message[0]);
                break;
            }
            $this->redirect(array('users/index?type='.$model->member_type));
        }
    }

    public function actionDeactivate($id) {
        $model = $this->loadModel($id);

        $model->status = 1;
        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'User deactivated successfully!');
            $this->redirect(array('users/index?type='.$model->member_type));
        } else {
            $msg    = $model->getErrors();
            foreach ($msg as $message) {
                Yii::app()->user->setFlash('error', $message[0]);
                break;
            }
            $this->redirect(array('users/index?type='.$model->member_type));
        }
    }

     public function actionBanned($id) {
        $model = $this->loadModel($id);

        $model->status = 0;
        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'User banned  successfully!');
            $this->redirect(array('users/index?type='.$model->member_type));
        } else {
            $msg    = $model->getErrors();
            foreach ($msg as $message) {
                Yii::app()->user->setFlash('error', $message[0]);
                break;
            }
            $this->redirect(array('users/index?type='.$model->member_type));
        }
    }
    /**
     * Manages all Customers.
     */
    public function actionCustomer() {

        $this->activeLink = 'customer';
        $model = new Users('customer_search');
        $this->page_title ='customer management';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        $model->unsetAttributes();  // clear any default values
        $model->member_type = 0;
        if (isset($_GET['Users']))
            $model->attributes = $_GET['Users'];

        $this->render('customer', array(
            'model' => $model,
        ));
    }
    /**
     * Manages all Customers.
     */
    public function actionBookings() {

        $this->activeLink = 'customer';
        $this->page_title ='customer booking management';

        $BookingsModel = new Bookings('search');
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        $BookingsModel->unsetAttributes();  // clear any default values
        $BookingsModel->status=0;

        if (isset($_GET['Bookings']))
            $BookingsModel->attributes = $_GET['Bookings'];
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
        $model->member_type = 1;
        $model->status = 2;
       
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        if (isset($_GET['Users']))
            $model->attributes = $_GET['Users'];


        $this->render('bookings', array(
            'model' => $BookingsModel,
            'therapistmodel'=>$model
        ));
    }

    public function actionpaymentstatistics() {
        $this->activeLink = 'paymentstatistics';
        $model = new Users;
        $this->page_title ='payment management';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Users']))
            $model->attributes = $_GET['Users'];

        $this->render('paymentview', array(
            'model' => $model,
        ));
    
    }

    public function actionpaymentview($id) {
        $this->activeLink = 'paymentviewmanagement';
        $model = new UserCards;
        $this->page_title ='payment view management';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['UserCards']))
            $model->attributes = $_GET['UserCards'];
            $model->user_id = $id;
        $this->render('paymentstatistics', array(
            'model' => $model,
            'id' => $id,
        ));
    
    }



    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actioncustomerDelete($id) {
         $data = $this->loadModel($id);
        try {
            $order_Details = Request::model()->findAllByAttributes(array('user_id'=>$id));
            if(count($order_Details)>0){
                    foreach($order_Details as $order){
                        $order_data = Request::model()->findByPk($order['id']);
                        $order_data->delete();
                    }
                    $getOauthtokens = OauthTokens::model()->findAllByAttributes(array('user_id'=>$id));
                    if($getOauthtokens){
                        foreach($getOauthtokens as $token){
                            $oathToken = OauthTokens::model()->findByPk($token['id']);
                            $oathToken->delete();
                        }
                    }
                    $userAddress = UserAddress::model()->findByAttributes(array('user_id'=>$id));
                    if($userAddress){
                        $userAddress = UserAddress::model()->findByPk($userAddress['id']);
                        $userAddress->delete();
                    }
                    $ActivityList = ActivityLog::model()->findAllByAttributes(array('user_id'=>$id));
                    if($ActivityList){
                        foreach($ActivityList as $activity){
                            $activities = ActivityLog::model()->findByPk($activity['id']);
                            $activities->delete();
                        }
                    }
                    $FeedbackList = Feedback::model()->findAllByAttributes(array('user_id'=>$id));
                    if($FeedbackList){
                        foreach($FeedbackList as $feedback){
                            $feedbacks = Feedback::model()->findByPk($feedback['id']);
                            $feedbacks->delete();
                        }
                    }
                    $data->delete();
                    Yii::app()->user->setFlash('success', "User has been deleted successfully.");
            }else{
                $getOauthtokens = OauthTokens::model()->findAllByAttributes(array('user_id'=>$id));
                if($getOauthtokens){
                    foreach($getOauthtokens as $token){
                        $oathToken = OauthTokens::model()->findByPk($token['id']);
                        $oathToken->delete();
                    }
                }
                $userAddress = UserAddress::model()->findByAttributes(array('user_id'=>$id));
                if($userAddress){
                    $userAddress = UserAddress::model()->findByPk($userAddress['id']);
                    $userAddress->delete();
                }
                $ActivityList = ActivityLog::model()->findAllByAttributes(array('user_id'=>$id));
                if($ActivityList){
                    foreach($ActivityList as $activity){
                        $activities = ActivityLog::model()->findByPk($activity['id']);
                        $activities->delete();
                    }
                }
                $FeedbackList = Feedback::model()->findAllByAttributes(array('user_id'=>$id));
                if($FeedbackList){
                    foreach($FeedbackList as $feedback){
                        $feedbacks = Feedback::model()->findByPk($feedback['id']);
                        $feedbacks->delete();
                    }
                }
                $data->delete();
                Yii::app()->user->setFlash('success', "User has been deleted successfully.");
            }
        } catch (CDbException $e) {
            Yii::app()->user->setFlash('error', "User could not be deleted");
        }
        /*try {
            $data->delete();
            Yii::app()->user->setFlash('success', "User has been deleted successfully.");
        }catch (CDbException $e) {
            Yii::app()->user->setFlash('error', "User could not be deleted");
        }*/
        $this->redirect(array('users/index?type='.$data->member_type));
    }
    
    public function actionCustomerView($id){
        date_default_timezone_set('Asia/Kolkata');
        $this->page_title = 'View User';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        
        /***************** Activity Starts  **********************/
        $limit = 10;
        $page=isset($_GET['page'])? $_GET['page'] :'1';
        $criteria=new CDbCriteria;
        $criteria->compare('user_id',$id);
        $criteria->order = 'id DESC';
        $criteria->group='created_on';
        $ActivityCount = ActivityLog::model()->findAll($criteria);
        $pages = new CPagination(count($ActivityCount));
        $pages->setPageSize($limit);
        
        if(!$page) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $limit;
        }
        $criteria=new CDbCriteria;
        $criteria->compare('user_id',$id);
        $criteria->order = 'id DESC';
        $criteria->group='created_on';
        $criteria->limit=$limit;
        $criteria->offset=$offset;
        $empty_model = new ActivityLog;
        $activity_model = ActivityLog::model()->findAll($criteria);
        foreach($activity_model as $mod){
            $date_only = explode(' ',$mod->created_on);
            $time=strtotime($date_only[0]);
            $day=date("d",$time);
            $month=date("m",$time);
            $year=date("Y",$time);
            $date[] = $year.'-'.$month;
            $mod->month_year_only=$year.'-'.$month.'-'.$day;
        }
        $empty_model->month_year_only = $date;
        /***************** Activity Ends  **********************/
        
        /***************** Feedback Starts ********************/
        $feedback_data = new Feedback('search');
        $feedback_data->unsetAttributes();  // clear any default values
        $feedback_data->type = '0';
        $feedback_data->user_id = $id;
        if (isset($_GET['Feedback']))
            $feedback_data->attributes = $_GET['Feedback'];
        /***************** Feedback Ends ********************/
            
        /***************** UserAddress Starts ********************/
        $userAddressModel = new UserAddress('search');        
        $totalCounts = Request::model()->countByAttributes(array('user_id'=>$id));
        $userAddressModel->user_id     = $id;        
        $addressEdtModel = UserAddress::model()->findByAttributes(array(
            'user_id' => $id, 'is_default' => 1,
        ));
        if(count($addressEdtModel)>0){
            $addressEdtModel = $addressEdtModel;
        }else{
            $addressEdtModel = array();
        }
        /***************** UserAddress Ends ********************/
        
        /***************** Request Starts ********************/
        $BookingsModel = new Request('search');
        $BookingsModel->unsetAttributes();  // clear any default values
        $BookingsModel->user_id = $id;
        //$addressDetails = Users::model()->findByPk($id);
        if (isset($_GET['Request']))
            $BookingsModel->attributes = $_GET['Request'];
       /***************** Request Ends ********************/
            
       /***************** Fee Starts ********************/
            $feeModel  = new Fee('search');
            $feeModel->unsetAttributes();  // clear any default values
            $feeModel->user_id = $id;
            if (isset($_GET['Fee']))
                $feeModel->attributes = $_GET['Fee'];
       /***************** Fee Ends ********************/
                
        $this->render('view', array(
            'basic_model' => $this->loadModel($id),
            'totalCounts'=>$totalCounts,
            'Complated_requests'=>$Complated_requests,
            'userAddressModel' => $userAddressModel,
            'addressDetails'=>$addressDetails,
            'bookingmodel'=>$BookingsModel,
            'addressEdtModel'=>$addressEdtModel,
            'activity_model'=>$ActivityCount,'pages'=>$pages,'activity_count'=>count($ActivityCount),
            'feedback_data'=>$feedback_data,
            'fee_model'=>$feeModel
        ));
    }

    public function actionCustomerViewUpdation($id) {

        $model = $this->loadModel($id);
        $this->page_title ='customer  management';

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            $model->username = $_POST['Users']['email'];
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $uploadedFile=CUploadedFile::getInstance($model,'image');
               
        
       
        if($uploadedFile){
            $ext = pathinfo($uploadedFile->name, PATHINFO_EXTENSION);
            $fileName  = Uuid::uuid5(Uuid::uuid4(), '597012a1-be5f-52fe-ac71-617f84ee84bc' . 'users')->toString();  
            $model->image = $fileName.'.'.$ext;
            if ($model->save())
                {
                    $uploadedFile->saveAs(Yii::app()->basePath.'/../uploads/profile-pictures/'.$fileName);
                    $client = S3Client::factory(array(
                        'key' => Yii::app()->params['awsKey'],
                        'secret' => Yii::app()->params['awsSecret'],
                                                'region' => Yii::app()->params['awsRegion'],
                                    ));

                                    $client->putObject(array(
                                        'Bucket' => Yii::app()->params['profileImageBucket'],
                                        'Key' => $fileName . '.' . $ext,
                                        'SourceFile' => Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName,
                                        'ACL' => 'public-read-write',
                                        "Cache-Control" => "max-age=94608000",
                                        "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                                    ));
                                    
                                    @unlink(Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName);
                            Yii::app()->user->setFlash('success', 'customer updated succesfully');
                           $this->redirect(array($model->id)); 
                    } else {
                        var_dump($model->getErrors()); 
                    } 
                    } else {
                        if (isset($_POST['Users'])) {
                            if ($model->save()) {
                                Yii::app()->user->setFlash('success', 'customer updated succesfully');
                                $this->redirect(array($model->id));
                            }
                        }
                }

        $this->render('update', array(
            'model' => $model,
        ));

    }

    public function actionEditUserAddress($id) {
        $model  = UserAddress::model()->findByPk($id);

        if (isset($_POST['UserAddress'])) {

            $model->user_id     = $_POST['UserAddress']['user_id'];
            //$model->is_default  = $_POST['UserAddress']['is_default'];
            if ($_POST['UserAddress']['is_default'] == 1) {
              UserAddress::model()->updateAll(array('is_default'=>0),'user_id=:uid',array(':uid'=>$model->user_id));
            } else {
              //$chkDefault   = UserAddress::model()->findAllByAttributes(array('user_id'=>$model->user_id,'is_default'=>1),array('condition'=>'id'!=$id));
              $chkDefault   = UserAddress::model()->findAll(array('condition'=>"user_id = $model->user_id AND is_default = 1 AND id != $id"));
              $countDefault = count($chkDefault);
            }

            $location = $_POST['UserAddress']['street_name'] . ' ' . $_POST['UserAddress']['city'] . ' ' . $_POST['UserAddress']['postal_code'];
            $geoLocation = Helper::getCoordinates($location);
            if (empty($geoLocation['lat']) && empty($geoLocation['long'])) {
                $location = $_POST['UserAddress']['city'];
                $geoLocation = Helper::getCoordinates($location);
            }

            if (empty($geoLocation['lat']) && empty($geoLocation['long'])) {
                Yii::app()->user->setFlash('error', 'enter a valid address');
            } else {

              $model->attributes  = $_POST['UserAddress'];
              $model->modified    = date('Y-m-d H:i:s');
              $model->id          = $id;
              $model->city        = $_POST['UserAddress']['city'];

              $model->geo_location = ($geoLocation['lat'] != '' && $geoLocation['long'] != '') ? new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $geoLocation['lat'] . ' ' . $geoLocation['long'] . ')')) : '';
              $model->place_id = isset($geoLocation['place_id']) ? $geoLocation['place_id'] : '';

              if ($countDefault == 0 ) {
                  $model->is_default  = 1;
              } else {
                  $model->is_default  = $_POST['UserAddress']['is_default'];
              }
              //stop("DEFAULT : " . $model->is_default);

              if ($model->save()) {
                  Yii::app()->user->setFlash('success', 'user address updated succesfully');
                  $this->redirect(array('users/'.$model->user_id));
              } else {
                  var_dump($model->getErrors()); die();
              }
            }
        }

        

        $this->render('user_address_edit', array(
            'addressEdtModel' => $model,
        ));
    }


    public function actionDeleteUserAddress($id) {
        //echo $id; die();
        $model  = UserAddress::model()->findByPk($id);
        $userId = $model->user_id;
        if ($model->is_default != 1 && $model->status == 1 ) {
             $model->status = 2 ;
              if ($model->save())         
              Yii::app()->user->setFlash('success', 'customer address deleted succesfully');
              $this->redirect(array('users/'.$userId));
          
        } else {
          Yii::app()->user->setFlash('error', 'default address cannot be deleted');
          $this->redirect(array('users/'.$userId));
          
        }
    }

    public function actionAddUserAddress($id) {
        $model  = new UserAddress;
        $model->user_id   = $id;



        if (isset($_POST['UserAddress'])) {

                $propName     = $_POST['UserAddress']['property_name'];

                $chkBlcListAdd  = BlacklistedAddress::model()->findAllByAttributes(array('name'=>$propName , 'status' => 1));

            if (!$chkBlcListAdd) {

                $model->user_id     = $_POST['UserAddress']['user_id'];
                if ($_POST['UserAddress']['is_default'] == 1) 
                {
                  UserAddress::model()->updateAll(array('is_default'=>0),'user_id=:uid',array(':uid'=>$model->user_id));
                }
                else 
                {
                  //$chkDefault   = UserAddress::model()->findAllByAttributes(array('user_id'=>$model->user_id,'is_default'=>1),array('condition'=>'id'!=$id));
                  $chkDefault   = UserAddress::model()->findAll(array('condition'=>"user_id = $model->user_id AND is_default = 1 "));
                  $countDefault = count($chkDefault);
                }

              if ($_POST['UserAddress']['city'] != '')  {
                
                $location = $_POST['UserAddress']['street_name'] . ' ' . $_POST['UserAddress']['city'] . ' ' . $_POST['UserAddress']['postal_code'];
                $geoLocation = Helper::getCoordinates($location);
                if (empty($geoLocation['lat']) && empty($geoLocation['long'])) {
                    $location = $_POST['UserAddress']['city'];
                    $geoLocation = Helper::getCoordinates($location);
                }

                if (empty($geoLocation['lat']) && empty($geoLocation['long'])) {
                    Yii::app()->user->setFlash('error', 'enter a valid address');
                } else {
                  $model->attributes  = $_POST['UserAddress'];
                  $model->created     = date('Y-m-d H:i:s');
                  $model->modified    = date('Y-m-d H:i:s');
                  //$model->city        = $_POST['UserAddress']['city'];
                  $model->geo_location = ($geoLocation['lat'] != '' && $geoLocation['long'] != '') ? new CDbExpression("GeomFromText(:point)", array(':point' => 'POINT(' . $geoLocation['lat'] . ' ' . $geoLocation['long'] . ')')) : '';
                  $model->place_id = isset($geoLocation['place_id']) ? $geoLocation['place_id'] : '';
                  if ($countDefault == 0 ) {
                      $model->is_default  = 1;
                      $model->status  = 1;
                  } else {
                      $model->is_default  = $_POST['UserAddress']['is_default'];
                      $model->status  = 1;
                  }
                  //stop("DEFAULT : " . $model->is_default);

                   if ($model->save()) {
                      Yii::app()->user->setFlash('success', 'user address created succesfully');
                      $this->redirect(array('users/'.$model->user_id));
                  } else {
                      //var_dump($model->getErrors()); die();
                  }
                }

              }
              else
              {
                  Yii::app()->user->setFlash('error', 'please fill the required fields.!');
                 //$this->redirect(array('users/AddUserAddress/'.$model->user_id));
              }

            } else {
              Yii::app()->user->setFlash('error', 'the address you are entered is blacklisted . please enter another address');
            }
        }

        $this->render('user_address_add', array(
            'addressEdtModel' => $model,
        ));
    }

    public function actionCancelBkng($id) {
      $model  = Bookings::model()->findByPk($id);
      $model->status  = 3;
      if ($model->save()) {
          Yii::app()->user->setFlash('success', 'bookings cancelled successfully');
          $this->redirect(array('users/bookings'));
      } else {
          $errors   = $model->getErrors();                             
          Yii::app()->user->setFlash('error', $errors[0]);
          $this->redirect(array('users/bookings'));
      }
    }
    
    public function actionCustomerDocs($id){
        $this->page_title = 'User Docs';

        $this->render('docs', array(
            'model' => $this->loadModel($id)
        ));
    }
    public function actionPageLoad(){
        $this->layout=false;
        if($_POST['pageCount']){
            $model = new Users('search');
            if($_POST['userType']=="requester"){
                $this->page_title ='Requesters List';
                $model->user_title = "Requesters";
            }elseif($_POST['userType']=="dropbox"){
                $this->page_title ='Dropbox Owners List';
                $model->user_title = 'Dropbox Owners';
            }else if($_POST['userType']=="doothan"){
                $this->page_title ='Doothans List';
                $model->user_title = 'Doothans';
            }else{
                throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
            }
            $model->unsetAttributes();  // clear any default values
            $model->member_type = $_POST['userType'];
            $model->search_val=$_POST['pageCount'];
            if (isset($_GET['Users']))
                $model->attributes = $_GET['Users'];
                $this->render('custom_admin', array(
                    'model' => $model,
                ));
        }
    }
}

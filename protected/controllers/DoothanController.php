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
                'actions' => array('admin', 'delete', 'activate','banned', 'deactivate','paymentactivate','customercontactupdate','paymentview','paymentstatistics', 'customer','bookings','customerDelete','customerviewupdation','Appointments','bookingsview','testing','edituseraddress','DeleteUserAddress','AddUserAddress','imagetemplate','cancelbkng'),
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

        $BookingsModel  = new Bookings('search');
        $promoModel     = new UserPromoCode('search');
        $promocodeModel = new UserPromoCode('getPromoCodeDetails');
        $userAddressModel = new UserAddress('search');

        $promocodeModel->user_id = $id;
        $BookingsModel->user_id = $id;
        $promoModel->user_id = $id;

        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }

        $totalCustomerBkngs   = Users::getUserTotalBooking($id);
        $cancellBkngDetails   = Users::getUsrCancelledBookings($id);
        $therapistOnway       = Users::getUsrTherapistOnway($id);
        $usrCompletedBkngs    = Users::getUsrCompletedBookings($id);
        $usrReviewDetails     = Bookings::getUserReview($id);
        $usrAddressDetails    = Users::getUserAddress($id);
        $userAddressModel->user_id     = $id;

        $addressDetails = UserAddress::model()->findAllByAttributes(array(
                    'user_id' => $id,'status' => 1 
            ));

        $user = Users::model()->findByPk($id);

            $couponsOrg = Yii::app()->db->createCommand()
                          ->select('t1.id,t2.tittle AS tittle,t2.coupon_code as coupon_code,t2.type as type,t2.offer_discount as offer_discount,t1.used_hours as used_hours')
                          ->from('user_promo_code t1')
                          ->leftjoin('promo_code t2', 't2.id=t1.promo_code')
                          ->where('t1.status=0 AND t1.gift=0 AND t1.user_id =' . $id.' and t2.valid_to>=CURDATE()')
                          ->queryAll();
            $giftCouponsOrg = Yii::app()->db->createCommand()
                          ->select('t1.id,t2.offer_title AS tittle,t2.coupon_code as coupon_code,t2.type as type,t2.offer_discount as offer_discount,t1.used_hours as used_hours')
                          ->from('user_promo_code t1')
                          ->leftjoin('gift_offers t2', 't2.id=t1.promo_code')
                          ->where('t1.status=0 AND t1.gift=1 AND t1.user_id =' . $id.' and DATEDIFF(CURDATE(),t1.created_on)<t2.valid_upto')
                           ->queryAll();
                  $couponsum = 0;
                  $giftCouponsum = 0;
                  $totalcredits = 0;
                  $usedHoursCoupons = 0;
                  $usedHoursGift = 0;

                  foreach ($couponsOrg as $key => $value) {
                      if ($value['type'] == 'CREDIT') {
                          $couponsum+= $value['offer_discount'];
                          $usedHoursCoupons+= $value['used_hours'];
                      }
                  }
                  foreach ($giftCouponsOrg as $key => $value1) {
                      if ($value1['type'] == 'CREDIT') {
                          $giftCouponsum+= $value1['offer_discount'];
                          $usedHoursGift+= $value1['used_hours'];
                      }
                  }

                  $totalcredits = ($giftCouponsum + $couponsum) - ($usedHoursCoupons + $usedHoursGift);                 
                  $totalcredits = $totalcredits / 60;
                  $totalcredits=round($totalcredits,2);
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
            'addressDetails' => $addressDetails
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
                    // $client = S3Client::factory(array(
                    //     'key' => Yii::app()->params['awsKey'],
                    //     'secret' => Yii::app()->params['awsSecret'],
                    //                             'region' => Yii::app()->params['awsRegion'],
                    //                 ));

                    //                 $client->putObject(array(
                    //                     'Bucket' => Yii::app()->params['profileImageBucket'],
                    //                     'Key' => $fileName . '.' . $ext,
                    //                     'SourceFile' => Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName,
                    //                     'ACL' => 'public-read-write',
                    //                     "Cache-Control" => "max-age=94608000",
                    //                     "Expires" => gmdate("D, d M Y H:i:s T", strtotime("+3 years"))
                    //                 ));
//                                    
                                    @unlink(Yii::app()->basePath . '/../uploads/profile-pictures/' . $fileName);
                            Yii::app()->user->setFlash('success', 'Requestor updated succesfully');
                            $this->redirect(array('users/index'));  
                    } else {
                        //var_dump($model->getErrors()); 
                    } 
                    } else {
                        if (isset($_POST['Users'])) {
                            if ($model->save()) {
                                Yii::app()->user->setFlash('success', 'Requestor updated succesfully');
                                $this->redirect(array('users/index'));
                            }
                        }
                }

        $this->render('update', array(
            'model' => $model,
        ));
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
    public function actionIndex() {
        $model = new Users('search');
        $model->unsetAttributes();  // clear any default values
       // $model->member_type = 1;
        if (isset($_GET['Users']))
            $model->attributes = $_GET['Users'];

        $this->render('admin', array(
            'model' => $model,
        ));
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
            Yii::app()->user->setFlash('success', 'customer activated successfully!');
            $this->redirect(array('customer'));
        } else {
            $msg    = $model->getErrors();
            foreach ($msg as $message) {
                Yii::app()->user->setFlash('error', $message[0]);
                break;
            }
            $this->redirect(array('customer'));
        }
    }

    public function actionDeactivate($id) {
        $model = $this->loadModel($id);

        $model->status = 1;
        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'customer deactivated successfully!');
            $this->redirect(array('customer'));
        } else {
            $msg    = $model->getErrors();
            foreach ($msg as $message) {
                Yii::app()->user->setFlash('error', $message[0]);
                break;
            }
            $this->redirect(array('customer'));
        }
    }

     public function actionBanned($id) {
        $model = $this->loadModel($id);

        $model->status = 0;
        if ($model->save()) {
            Yii::app()->user->setFlash('success', 'customer banned  successfully!');
            $this->redirect(array('customer'));
        } else {
            $msg    = $model->getErrors();
            foreach ($msg as $message) {
                Yii::app()->user->setFlash('error', $message[0]);
                break;
            }
            $this->redirect(array('customer'));
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
            $data->delete();
            Yii::app()->user->setFlash('success', "Requestor has been deleted successfully.");
        } catch (CDbException $e) {
            Yii::app()->user->setFlash('error', "Requestor could not be deleted");
        }

        $this->redirect(array('users/index'));
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


      public function actionpaymentactivate($id,$user_id) {
       
        $paymentdetails=UserCards::model()->findByPk($id);
        if($paymentdetails->status==0)
                       { 
                            $paymentdetails->status = 1;
                            $paymentdetails->save();
                            Yii::app()->user->setFlash('success', 'payment status activated successfully!');
                            $this->redirect(array('users/paymentview/'.$user_id));
                       }
                       else
                       {
                            $paymentdetails->status=0;
                            $paymentdetails->save();
                            Yii::app()->user->setFlash('success', 'payment status deactivated successfully!');
                            $this->redirect(array('users/paymentview/'.$user_id));

                       }
    }
 /**
     * Manages all Customers.
     */
    public function actionAppointments() {

        $this->activeLink = 'appointment-booking';
        $this->page_title ='appointment booking management';

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


        $this->render('appointments', array(
            'model' => $BookingsModel,
            'therapistmodel'=>$model
        ));
    }

    public function actionbookingsView($id) {

        $this->activeLink = 'customer';
        $model = new Bookings('search');
        $model->id  = $id;
        $this->page_title ='customer bookings management';
        if (isset($_GET['pageSize'])) {
            Yii::app()->user->setState('pageSize', (int) $_GET['pageSize']);
            unset($_GET['pageSize']);
        }
        $model->unsetAttributes();  // clear any default values

        $current_date = date('Y-m-d H:i:s');
        $NotificationModel = Yii::app()->db->createCommand()->update('notification', array(
                                    'viewed_on'=>$current_date,
                                ), 'rederence_id=:id', array(':id'=>$id));
 
        $userDetails    = Users::getUserDetails($id);
        $approvedDate   = Users::getTherapistApprovedDates($id);
        $therapistDetails = Users::getTherapistDetails($id);
        $this->render('bookings_display_view', array(
            'id' => $id,
            'model' => $model,
            'userDetails' => $userDetails,
            'approvedDate' => $approvedDate,
            'therapistDetails' => $therapistDetails,
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


    
}

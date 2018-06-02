<?php
use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class RequestController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';
    public $page_title = 'Request Management';
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
                'actions' => array('admin', 'delete', 'activate','banned', 'LoadModelContent', 'LoadContent','AddAddtionalInfo', 'Payments', 'Assign_doothan','CancelRequest','requestDeleted', 'PaymentDetail', 'deactivate','PushNotification','notifyDoothan','paymentactivate','customercontactupdate','paymentview','paymentstatistics', 'customer','bookings','customerDelete','customerviewupdation','Appointments','bookingsview','testing','edituseraddress','DeleteUserAddress','AddUserAddress','imagetemplate','cancelbkng','requestview','servicecharge'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
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
    public function actionIndex($search='') {
        $model = new Request('search');
        $this->page_title   = 'Request Management';
        $model->unsetAttributes();  // clear any default values
        if($search!=''){
            $model->status = $search;
        }
        if (isset($_GET['Request']))
            $model->unsetAttributes();  // clear any default values
            $model->attributes = $_GET['Request'];
            if(isset($_POST['flag'])&&$_POST['flag']=="flag"){
                $model->search_val=$_POST['pageCount'];
                $this->layout=false;
                $this->render('custom_request', array(
                    'model' => $model,
                ));
            }else{
                $this->render('admin', array(
                    'model' => $model,
                ));
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
        $model = Request::model()->findByPk($id);
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


    public function actionRequestview($id) {
      $model  = Request::model()->findByPk($id);
      $this->render('view', array(
            'model' => $model,
      ));

    }
    public function actionLoadModelContent(){
        $this->layout=false;
        $request_id = $_POST['id'];
        $this->render('doothan_list', array('request_id'=>$request_id));
    }
    
    public function actionLoadContent(){
        $this->layout=false;
        $limit = 10;
        if (isset($_POST["page"])) { $page  = $_POST["page"]; } else { $page=1; };
        $start_from = ($page-1) * $limit;
        $sql = 'SELECT *,user.id as us_id FROM `users` as user left join `user_address` as address on user.id=address.user_id where user.member_type="doothan" and user.status=2 and user.account_status="APPROVED" and user.travel_from_to="Yes" and (user.mode_of_commute="Bike" OR user.mode_of_commute="Car" OR user.mode_of_commute="Bus")Order By Case user.mode_of_commute When "Bike" Then 1 When "Car" Then 2 When "Bus" Then 3 Else 4 End LIMIT '.$start_from.','. $limit;
        $list_content=Yii::app()->db->createCommand($sql)->queryAll();  
        ?>
        <table class="table table-bordered table-striped">  
            <thead>  
                <tr>  
                <th>Selection Box</th>  
                <th>Doothan ID</th>
                <th>Full Name</th>
                <th>Phone #</th>  
                <th>Regsitered Home Location</th>
                <th>Distance from Pickup location</th>
                <!-- <th>Distance from home to drop box location</th> -->
                <th>Approx. Doothan Fee</th>
                
                </tr>  
            </thead>  
            <tbody>  
            <?php  
            $request_details = Request::model()->findByPk($_POST['request_id']);
            $dropbox_address  = UserAddress::model()->findByAttributes(array('user_id'=>$request_details->dropbox_id));
            $settings = Settings::model()->find();
            $i=1;
            $count = 5;
            $total_pages_count = array();
            foreach($list_content as $list_data) {
                if($i<=$count){
                    $Doothan_pin_code = ($list_data['current_location']!='')?$list_data['current_location']:$list_data['postal_code'];
                    $Doothan_city = ($list_data['current_city']!='')?$list_data['current_city']:$list_data['city'];
                    $Pic_up_location_pincode = $request_details->to_pincode;
                    $params = array($Doothan_pin_code,$Pic_up_location_pincode);
                    $pickup_doothan_distance = Helper::getLocationDistance($params);
                    if(intval($pickup_doothan_distance)<=intval($settings->minimum_km)){
                        $Dropbox_pin_code = $dropbox_address->postal_code;
                        $params = array($Doothan_pin_code,$dropbox_pin_code);
                        $doothan_dropbox_distance = Helper::getLocationDistance($params);
                        if(intval($doothan_dropbox_distance)<=intval($settings->minimum_distance_doothan_dropbox) && $list_data['us_id']!=$request_details->user_id){
                            array_push($total_pages_count,$j);?>
                                    <tr>  
                                        <td>
                                            <div class="form-group">
                                                <label>
                                                  <input name="doothans[]" id="<?=$request_id?>" value=<?=$list_data["us_id"]?> class="chkNumber" type="checkbox" onClick="enable_button();">
                                                  
                                                </label>
                                            </div>
                                        </td>
                                        <td><?php echo $list_data["us_id"]; ?></td>  
                                        <td><?php echo $list_data["first_name"]." ".$list_data["last_name"]; ?></td>  
                                        <td><?php echo $list_data["phone"]; ?></td>  
                                        <td><?php echo $Doothan_city." ".$Doothan_pin_code; ?></td>
                                        <td><?php echo $pickup_doothan_distance;?></td> 
                                        <td><?php echo $pickup_doothan_distance*$settings->default_distance_charge+5; ?></td>  
                                    </tr>  
                        		<?php $i++;
                                
                         } 
                    }
                   
                }
           }?>
            </tbody>  
        </table> 
        <?php 
    }
    public function actionPushNotification(){
        $this->layout=false;
        if($_POST['user_id']){
            if($_POST['order_id']){
                $orderDetails = Request::model()->findByPk($_POST['order_id']);
                $user_details = Users::model()->findByPk($_POST['user_id']);
                    if(count($user_details)>0){
                        if($_POST['type']=="0"){
                            $orderDetails->status = "Waiting for payment";
                            $orderDetails->save();
                        }
                        $result_msg = array();
                        $result_msg['data']['email_id']=$user_details->email;
                        $result_msg['data']['name']=$user_details->first_name;
                        $result_msg['data']['order_id']=$orderDetails->id;
                        
                        $result_msg['data']['order_code']=$orderDetails->request_code;
                        $result_msg['data']['phone']=$user_details->phone;
                        $result_msg['data']['item_t']=$orderDetails->product_price;
                        $result_msg['data']['service_c']=$orderDetails->service_charge;
                        $result_msg['data']['discount_c']=$orderDetails->discount;
                        $result_msg['data']['doothan_service_fee']="";
                        $charge=$orderDetails->base_amount;
                        $de = $orderDetails->coupon_amount+$orderDetails->discount;
                        $bc = $orderDetails->rate_per_km * $orderDetails->distance;
                        $abc = $charge+$bc;
                        $last_total = $abc-$de+$orderDetails->weight;
                        $vat = ($last_total*$orderDetails->gst)/100;
                        $after_vat = $last_total+$vat;
                        $last_last = $after_vat+$orderDetails->product_price;
                        $result_msg['data']['tax_value']=$orderDetails->gst;
                        $result_msg['data']['applicable_t']=$vat;
                        $result_msg['data']['total']=$orderDetails->amount;
                        if($_POST['type']=="0"){
                            $result_msg['data']['type']="";
                            $result_msg['data']['user_id']="";
                            $result_msg['data']['dropbox']="";
                            $subject = 'DOOTHAN : Notify User';
                            $content = "Your request ".$orderDetails->request_code." has been ready for payment, you should pay ".$orderDetails->amount." for this request";
                        }else{
                            $userAddressDetails = UserAddress::model()->findByAttributes(array('user_id'=>$orderDetails->dropbox_id));
                            $dropbox_details = Users::model()->findByPk($orderDetails->dropbox_id);
                            $result_msg['data']['type']="3";
                            $result_msg['data']['user_id']=$_POST['user_id'];
                            $result_msg['data']['dropbox']=wordwrap($dropbox_details->first_name." ".$dropbox_details->phone." ".$userAddressDetails->city, 30, "\n",true);
                            $result_msg['data']['role']=$user_details->member_type;
                            $subject = 'DOOTHAN : Notify User';
                            $content = "Your requested item has been delivered to ".wordwrap($dropbox_details->first_name." ".$dropbox_details->phone." ".$userAddressDetails->city, 30, "\n",true). "shop.Please collect the item immediately";
                        }
                        $result_msg['data']['doothan']="";
                        $result_msg['data']['status_text'] = "";
                        $result_msg['data']['user_address'] = "";
                        $result_msg['data']['service_fee'] = "";
                        $result_msg['data']['product_name']=$orderDetails->item_details;
                        $result_msg['data']['doothan_service_fee']="";
                        $fullname = $user_details->first_name." ".$user_details->last_name;
                        $message = "Notification sent to ".$fullname." for request order ".$orderDetails->request_code;
                        Common::activityLog($user_details->id, "Notification", $message, date('Y-m-d H:i:s'));
                        $settings = Settings::model()->find();
                        
                        $mail = new SesMailer();
                        $mail->setView('notify_user');
                        $mail->setData(array('name' => $fullname,'content'=>$content));
                        $mail->setFrom('support@doothan.in',$settings->from_name);
                        $mail->setTo($user_details->email, $user_details->first_name);
                        $mail->setSubject('DOOTHAN : Notify user');
                        $mail->Send();
                        $status = true;
                    }else{
                        $result_msg = array();
                        $result_msg['data']['order_id']=$orderDetails->id;
                        $result_msg['data']['order_code']="Not found";
                        $result_msg['data']['type']="";
                        $status = true;
                    }
                    $request_code = $orderDetails->request_code;
                    if($_POST['type']=="0"){
                        $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$orderDetails->user_id),array('order'=>'id DESC'));
                        $device_token = ($device_token_data[0]->device_id)?$device_token_data[0]->device_id:$device_token_data[1]->device_id;
                        //$device_token = "cdXch5-6ovU:APA91bFEgZxmSb6-pyXQYU40xNIsrGdjjhp6uPUFhOigogkwBgG84X8PAxAoUIknl5xyq4VBCwKWkFh3JE4gonbxd2WqtJMdOBd9t7WPcyYzqJvYKWyhSXJMFuw5dznThuYRCz0nIzxZ";
                        $result=Common::sendPushNotification($device_token, $result_msg);
                    }else{
                        $device_token_data_requester = OauthTokens::model()->findAllByAttributes(array('user_id'=>$orderDetails->user_id),array('order'=>'id DESC'));
                        $device_id_second = ($device_token_data_requester[0]->device_id)?$device_token_data_requester[0]->device_id:$device_token_data_requester[1]->device_id;
                        $result=Common::sendPushNotification($device_id_second, $result_msg);
                    }
                    $result_array = json_decode($result);
                    $result_array = json_decode( json_encode($result_array), true);
                    $error_result = ($result_array['results'][0]['error'])?$result_array['results'][0]['error']:'';
                    $this->renderJSON(array('status' => $status,'request_code'=>$request_code,'flag_value'=>$_POST['type'],'error_msg'=>$error_result,'message'=>json_decode($result)));
            }
        }
    }
    
    
    public function actionServiceCharge($id) {
      $settings = Settings::model()->find();
      $model  = Request::model()->findByPk($id);
      if(isset($_POST['Request'])){
        if($_POST['Request']['distance']){
          $distance=intval($_POST['Request']['distance']);
          $one_way_distance=$_POST['Request']['distance']/2;
          //echo $one_way_distance;die;
        }else{
            $one_way_distance=Helper::getLocationDistance(array($_POST['Request']['delivery_address'],$_POST['Request']['request_address']));
            $distance=$one_way_distance*2;
        }
        //if($distance){
            $model->attributes = $_POST['Request'];
            //$default_weight_limit=$settings->default_weight_limit;
            //$default_distance_limit=$settings->default_distance_limit;
            //$default_weight_limit_charge=$settings->default_weight_limit_charge;
            $default_weight_charge=$settings->default_weight_charge;
            //$default_distance_limit_charge=$settings->default_distance_limit_charge;
            $default_distance_charge=$settings->default_distance_charge;
            $charge=$_POST['Request']['base_amount'];
            
            /*************** My calculation *************/
            $de = $_POST['Request']['coupon_amount']+$_POST['Request']['discount'];
            $bc = $_POST['Request']['rate_per_km'] * $_POST['Request']['distance'];
            $abc = $charge+$bc;
            $last_total = $abc-$de+$_POST['Request']['weight'];
            $vat = ($last_total*$_POST['Request']['gst'])/100;
            $after_vat = $last_total+$vat;
            $last_last = $after_vat+$_POST['Request']['product_price'];
            /************* Ends ***************************/
            
            $doothan_flat_value = ($last_total * 20)/100;
            $doothan_flat_value = number_format((float)$doothan_flat_value, 2, '.', '');
            $doothan_calculation_value = $one_way_distance * $_POST['Request']['rate_per_km'] + 5;
            $doothan_calculation_value = number_format((float)$doothan_calculation_value, 2, '.', '');
            $model->doodhan_fee = max(array($doothan_first_value,$doothan_calculation_value));
 
            //calculate final amount
            $model->amount=$last_last;
            $model->product_price=$_POST['Request']['product_price'];
            $model->base_amount=$_POST['Request']['base_amount'];
            $model->weight=$_POST['Request']['weight'];
            $model->weight_unit=$_POST['Request']['weight_unit'];
            $model->gst=$_POST['Request']['gst'];
            $model->service_charge=$after_vat;
            //$model->status='Waiting for payment';
            $model->rate_per_km=$_POST['Request']['rate_per_km'];
            $model->distance=$distance;
            $model->discount=$_POST['Request']['discount'];
            $model->coupon_amount=$_POST['Request']['coupon_amount'];
            //$model->dropbox_fee='15';
            if($model->save()){ 
               Yii::app()->user->setFlash('success', "Fare Calculated successfully");
            
            }else{
               Yii::app()->user->setFlash('error', "Error Occured Please try again");
            
            }
        /*}else{
           Yii::app()->user->setFlash('error', "Address Not found. Please try again");
        }*/
        
      }
      $this->render('servicecharge', array(
          'model' => $model
      ));

    }
    
    public function actionnotifyDoothan(){
        
        $this->layout=false;
        if($_POST['id']){
            $ordr_details = Request::model()->findByPk($_POST['id']);
            $list = explode(',',$_POST['chkId']);
            $settings = Settings::model()->find();
            if($ordr_details){
                $result_arrayss = array();
                $result_arrayss_success = array();
                $imploded_arrays = '';
                $count_value = count($list);
                foreach($list as $users){
                    if($user['id']!=$ordr_details->user_id){
                        $user = Users::model()->findByPk($users);
                        $dropbox_details = Users::model()->findByPk($ordr_details->dropbox_id);
                        $device_token_data = OauthTokens::model()->findAllByAttributes(array('user_id'=>$user['id']),array('order'=>'id DESC'));
                        $device_token = $device_token_data[0]->device_id;
                        $doothanAddressDetails = UserAddress::model()->findByAttributes(array('user_id'=>$ordr_details->doothan_id));
                        $dropoxAddressDetails = UserAddress::model()->findByAttributes(array('user_id'=>$ordr_details->dropbox_id));
                        $NormaluserAddressDetails = UserAddress::model()->findByAttributes(array('user_id'=>$ordr_details->user_id));
                        $normal_address  = $NormaluserAddressDetails->address.' '.$NormaluserAddressDetails->city;
                        $pick_up_location = $ordr_details->to_pincode;
                        //$second_location = $dropoxAddressDetails->postal_code;
                        $doothan_location = ($user['current_location']!='')?$user['current_location']:$doothanAddressDetails->postal_code;
                        $params = array($pick_up_location,$doothan_location);
                        $distance = Helper::getLocationDistance($params);
                        $amount = $distance*$settings->default_distance_charge+5;
                        //echo $distance."=>".$settings->minimum_km;die;
                        //if($distance<=$settings->minimum_km){
                            $result_msg = array();
                            //$error_result  = array();
                            $result_msg['data']['order_id']=$ordr_details->id;
                            $result_msg['data']['order_code']=$ordr_details->request_code;
                            $result_msg['data']['product_name']=$ordr_details->item_details;
                            //$result_msg['distance']=Helper::getLocationDistance($params);
                            $result_msg['data']['doothan']="";
                            $result_msg['data']['dropbox']=wordwrap($dropbox_details->first_name." ".$dropbox_details->phone." ".$userAddressDetails->city, 30, "\n",true);
                            $result_msg['data']['user_address']=wordwrap($normal_address, 30, "\n",true);
                            $result_msg['data']['user_id']=$user['id'];
                            $result_msg['data']['email_id']=$user['email'];
                            $result_msg['data']['type']="";
                            $result_msg['data']['service_fee']="50";
                            $result_msg['data']['doothan_service_fee']="Rs ".$amount;
                            $result=Common::sendPushNotification($device_token, $result_msg);
                            $result_array = json_decode($result);
                            $result_array = json_decode( json_encode($result_array), true);
                            $resultanct_array = ($result_array['results'][0]['error'])?$result_array['results'][0]['error']:'success';
                            if($resultanct_array=="NotRegistered"){
                                $result_arrayss[] = $user['first_name'];
                            }else if($resultanct_array=="InvalidRegistration"){
                                $result_arrayss[] = $user['first_name'];
                            }else if($resultanct_array=="missingregistration"){
                                $result_arrayss[] = $user['first_name'];
                            }
                            $settings = Settings::model()->find();
                            $mail = new SesMailer();
                            $name = $user['first_name'] . ' ' . $user['last_name'];
                            $mail->setView('notify_doothan');
                            $address = wordwrap($dropbox_details->first_name." ".$dropbox_details->phone." ".$userAddressDetails->city, 30, "\n",true);
                            $mail->setData(array('name' => $name,'dropbox_name'=>$address,'link'=>''));
                            $mail->setFrom('support@doothan.in',$settings->from_name);
                            $mail->setTo($user['email'],$user['first_name']);
                            $mail->setSubject('DOOTHAN : Notify Doothan');
                            $mail->Send();
                            $status = true;
                    }
                }
                if(empty($result) || $result==NULL){
                    if(!empty($result_arrayss)){
                        $imploded_arrays = implode(',',$result_arrayss);
                    }
                    $this->renderJSON(array('status' => 'false','unregistred'=>$imploded_arrays,'count_value'=>$count_value,'messages'=>"Oops, couldn't find below doothans device id, try to login again"));
                }else{
                    if(!empty($result_arrayss)){
                        $imploded_arrays = implode(',',$result_arrayss);
                        $this->renderJSON(array('status' => 'false','unregistred'=>$imploded_arrays,'count_value'=>$count_value,'message'=>json_decode($result),'messages'=>"Oops..couldn't find below doothans device id, try to login again"));
                    }else{
                        $this->renderJSON(array('status' => 'true','unregistred'=>0,'count_value'=>$count_value,'message'=>json_decode($result)));
                    }
                }
            }
        }
    }
    public function actionrequestDeleted($id){
        $model = $this->loadModel($id);
        try {
            $model->delete();
            Yii::app()->user->setFlash('success', "Request has been deleted successfully");
        }catch (CDbException $e) {
            Yii::app()->user->setFlash('error', "Request could not be deleted");
        }
        $this->redirect(array('request/index'));
    }

    public function actionCancelRequest(){
        $this->layout=false;
        if($_POST['id']){
            $requestDetails = Request::model()->findByPk($_POST['id']);
            if($requestDetails){
                $requestDetails->status="Cancelled";
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
                    //$mail->AddCC($addcc);
                    $mail->SMTPDebug = 0;
                    $mail->SMTPSecure = 'tls';*/
                    
                    $mail = new SesMailer();
                    $mail->setView('cancel_request');
                    $mail->setData(array('name' => $userDetails->first_name,'order_code'=>$requestDetails->request_code));
                    $mail->setFrom('info@doothan.in',$settings->from_name);
                    $mail->setTo($userDetails->email, $userDetails->first_name);
                    $mail->setSubject('DOOTHAN : Order Cancelled');                    
                    if (!$mail->Send()) {
                        Yii::app()->user->setFlash('success', "Request ". $requestDetails->request_code ." cancelled successfully ,Error while sent mail");
                        $htm = '0'; 
                    }else{
                        Yii::app()->user->setFlash('success', "Request ". $requestDetails->request_code ." cancelled successfully");
                        $htm = '1';
                    }
                }else{
                    $htm = '0';
                    Yii::app()->user->setFlash('error', "Error while cancelling request,please try after some time");
                }
            }else{
                $htm = '0';
                Yii::app()->user->setFlash('error', "Error while cancelling request,please try after some time");
            }
            return $htm;
        }
    }
    
    public function actionPayments(){
        $model = new PaymentStatus('search');
        $this->page_title   = 'Payment Management';
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PaymentStatus']))
            $model->attributes = $_GET['PaymentStatus'];
            if(isset($_POST['flag'])&&$_POST['flag']=="flag"){
                $this->layout=false;
                $model->search_val=$_POST['pageCount'];
                $this->render('custom_payments', array(
                    'model' => $model,
                ));
            }else{
                $this->render('payments', array(
                    'model' => $model,
                ));
            }
    }
    
    public function actionPaymentDetail($id){
        $this->page_title   = 'Payment Management';
        $model = PaymentStatus::model()->findByPk($id);
        $this->render('payment_detail',array('model'=>$model));
    }
    public function actionAssign_doothan(){
        if($_POST['doothan_id'] && $_POST['request_id']){
            $requestDetails = Request::model()->findByPk($_POST['request_id']);
            if($requestDetails){
                $requestDetails->doothan_id = $_POST['doothan_id'];
                if($requestDetails->save(false)){
                    $doothan_Details = UserAddress::model()->findByAttributes(array('user_id'=>$_POST['doothan_id']));
                    $doothan_datas = Users::model()->findByPk($_POST['doothan_id']);
                    $doothan_address  = "<b>Name : </b>".$doothan_datas->first_name." ".$doothan_datas->last_name."<br/><br/><b>Address : </b>".$doothan_Details->address.' '.$doothan_Details->city. '<br/> '.$doothan_Details->state.' '.$doothan_Details->postal_code;
                    echo $doothan_address;
                }else{
                    echo "0";
                }
            }
        }
    }
    public function actionAddAddtionalInfo(){
        if($_POST['value'] & $_POST['type']){
            $requestDetails = Request::model()->findByPk($_POST['request']);
            if($requestDetails){
                if($_POST['type']==1){
                    $requestDetails->item_details  = $_POST['value'];
                }else if($_POST['type']==2){
                    $requestDetails->additional_info  = $_POST['value'];
                }else{
                    $requestDetails->vendor_info  = $_POST['value'];
                }
                if($requestDetails->save(false)){
                    echo "1";
                }else{
                    echo "0";
                }
            }else{
                echo "0";
            }
        }
    }
    
}

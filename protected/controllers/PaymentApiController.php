<?php

use Rhumsaa\Uuid\Uuid;
use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;


class PaymentApiController extends Controller {
    
    public function actionGetHashes(){
        // $firstname, $email can be "", i.e empty string if needed. Same should be sent to PayU server (in request params) also.
        /*$key =  $_POST['key'];//'gtKFFx';
        $salt = Yii::app()->params['_SALT'];//'eCwWELxi';
        $txnid=$_POST['txnid'];
        $amount=$_POST['amount'];
        $productinfo=$_POST['productinfo'];
        $firstname=$_POST['firstname'];
        $email=$_POST['email'];
        // $user_credentials=$_POST['user_credentials'];//merchant key|registerd email id
        $udf1=$_POST['udf1']; // request id
        $udf2=$_POST['udf2'];
        $udf3=$_POST['udf3'];
        $udf4=$_POST['udf4'];
        $udf5=$_POST['udf5'];
        //$request_id = Request::model()->findByAttributes(array('request_code'=>$udf1)); // Taking request id by using request code which recieved from android device
        //$udf1 = $request_id->id;
        $payhash_str = $key . '|' . $this->actionCheckNull($txnid) . '|' .$this->actionCheckNull($amount)  . '|' .$this->actionCheckNull($productinfo)  . '|' . $this->actionCheckNull($firstname) . '|' . $this->actionCheckNull($email) . '|' . $this->actionCheckNull($udf1) . '|' . $this->actionCheckNull($udf2) . '|' . $this->actionCheckNull($udf3) . '|' . $this->actionCheckNull($udf4) . '|' . $this->actionCheckNull($udf5) . '||||||' . $salt;
        $paymentHash = strtolower(hash('sha512', $payhash_str));
        $arr['payment_hash'] = $paymentHash;
        
        $cmnNameMerchantCodes = 'get_merchant_ibibo_codes';
        $merchantCodesHash_str = $key . '|' . $cmnNameMerchantCodes . '|default|' . $salt ;
        $merchantCodesHash = strtolower(hash('sha512', $merchantCodesHash_str));
        $arr['get_merchant_ibibo_codes_hash'] = $merchantCodesHash;
        
        $cmnMobileSdk = 'vas_for_mobile_sdk';
        $mobileSdk_str = $key . '|' . $cmnMobileSdk . '|default|' . $salt;
        $mobileSdk = strtolower(hash('sha512', $mobileSdk_str));
        $arr['vas_for_mobile_sdk_hash'] = $mobileSdk;
        
        $cmnPaymentRelatedDetailsForMobileSdk1 = 'payment_related_details_for_mobile_sdk';
        $detailsForMobileSdk_str1 = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk1 . '|default|' . $salt ;
        $detailsForMobileSdk1 = strtolower(hash('sha512', $detailsForMobileSdk_str1));
        $arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk1;
        
        //used for verifying payment(optional)
        $cmnVerifyPayment = 'verify_payment';
        $verifyPayment_str = $key . '|' . $cmnVerifyPayment . '|'.$txnid .'|' . $salt;
        $verifyPayment = strtolower(hash('sha512', $verifyPayment_str));
        $arr['verify_payment_hash'] = $verifyPayment;
        
        //if($user_credentials != NULL && $user_credentials != '')
        //{
            $cmnNameDeleteCard = 'delete_user_card';
            $deleteHash_str = $key  . '|' . $cmnNameDeleteCard . '|' . $user_credentials . '|' . $salt ;
            $deleteHash = strtolower(hash('sha512', $deleteHash_str));
            $arr['delete_user_card_hash'] = $deleteHash;
            
            $cmnNameGetUserCard = 'get_user_cards';
            $getUserCardHash_str = $key  . '|' . $cmnNameGetUserCard . '|' . $user_credentials . '|' . $salt ;
            $getUserCardHash = strtolower(hash('sha512', $getUserCardHash_str));
            $arr['get_user_cards_hash'] = $getUserCardHash;
            
            $cmnNameEditUserCard = 'edit_user_card';
            $editUserCardHash_str = $key  . '|' . $cmnNameEditUserCard . '|' . $user_credentials . '|' . $salt ;
            $editUserCardHash = strtolower(hash('sha512', $editUserCardHash_str));
            $arr['edit_user_card_hash'] = $editUserCardHash;
            
            $cmnNameSaveUserCard = 'save_user_card';
            $saveUserCardHash_str = $key  . '|' . $cmnNameSaveUserCard . '|' . $user_credentials . '|' . $salt ;
            $saveUserCardHash = strtolower(hash('sha512', $saveUserCardHash_str));
            $arr['save_user_card_hash'] = $saveUserCardHash;
            
            $cmnPaymentRelatedDetailsForMobileSdk = 'payment_related_details_for_mobile_sdk';
            $detailsForMobileSdk_str = $key  . '|' . $cmnPaymentRelatedDetailsForMobileSdk . '|' . $user_credentials . '|' . $salt ;
            $detailsForMobileSdk = strtolower(hash('sha512', $detailsForMobileSdk_str));
            $arr['payment_related_details_for_mobile_sdk_hash'] = $detailsForMobileSdk;*/
        //}
        
        $key=$_POST['key'];
        $salt=Yii::app()->params['_SALT'];;
        $txnId=$_POST['txnid'];
        $amount=$_POST["amount"];
        $productName=$_POST["productinfo"];
        $firstName=$_POST["firstname"];
        $email=$_POST["email"];
        $udf1=$_POST["udf1"]; // request id
        $udf2=$_POST["udf2"];
        $udf3=$_POST["udf3"];
        $udf4=$_POST["udf4"];
        $udf5=$_POST["udf5"];
        $payhash_str = $key . '|' . $this->actionCheckNull($txnId) . '|' .$this->actionCheckNull($amount)  . '|' .$this->actionCheckNull($productName)  . '|' . $this->actionCheckNull($firstName) . '|' . $this->actionCheckNull($email) . '|' . $this->actionCheckNull($udf1) . '|' . $this->actionCheckNull($udf2) . '|' . $this->actionCheckNull($udf3) . '|' . $this->actionCheckNull($udf4) . '|' . $this->actionCheckNull($udf5) . '||||||' . $salt;
        $hash = strtolower(hash('sha512', $payhash_str));
        $arr['payment_hash'] = $hash;
        $arr['status']=0;
        $arr['errorCode']=null;
        $arr['responseCode']=null;
        $arr['hashtest']=$payhash_str;
        $output=$arr;
        echo json_encode($output);
    }
    
    public function actionCheckNull($value) {
        if ($value == null) {
            return '';
        } else {
            return $value;
        }
    }
    public function actionSuccess() {
        if(isset($_POST["hash"])){
            $status = $_POST ["status"];
            $firstname = $_POST ["firstname"];
            $amount = $_POST ["amount"];
            $txnid = $_POST ["txnid"];
            $posted_hash = $_POST ["hash"];
            $key = $_POST ["key"];
            $productinfo = $_POST ["productinfo"];
            $email = $_POST ["email"];
            $request_id = $_POST ["udf2"]; // Request id //booking id
            $mihpayid =$_POST ["mihpayid"]; // Unique ID generated for a transaction by PayU.in
            $mode =$_POST ["mode"]; // 'CC' for credit-card / ‘DC’ for Debit Card / 'NB' for net-banking
            $bank_code =$_POST ["bankcode"];
            $bank_ref_num = $_POST ["bank_ref_num"];
            $added_on=$_POST["addedon"];
            $salt = Yii::app()->params['_SALT'];
            if(isset($_POST ["additional_charges"])){
                $additional_charges=$_POST ["additional_charges"];
            }else{
                $additional_charges=null;
            }
            If ($additional_charges != null) {
                //$additionalCharges = $_POST ["additional_charges"];
                $retHashSeq = $additional_charges . "|" . $salt . "|" . $status  . "||||||" . $_POST["udf5"] . "|" . $_POST["udf4"] . "|" . $_POST["udf3"] . "|" . $_POST["udf2"] . "|" . $_POST["udf1"] . "|" .
                    $email . "|" . $firstname . "|" . $productinfo . "|" . $amount . "|" . $txnid . "|" . $key ;
            } else {
                
                $retHashSeq =  $salt . "|" . $status  . "||||||" . $_POST["udf5"] . "|" . $_POST["udf4"] . "|" . $_POST["udf3"] . "|" . $_POST["udf2"] . "|" . $_POST["udf1"] . "|" .
                    $email . "|" . $firstname . "|" . $productinfo . "|" . $amount . "|" . $txnid . "|" . $key ;
            }
            $hash = hash ( "sha512", $retHashSeq );
            
            if ($hash != $posted_hash) {
                
                echo "Invalid Transaction. Please try again";
                echo "Please close this window to go back to your app";
            } else {
                    //echo "<h3>Thank You. Your payment is successfully completed.</h3>";
                    $payment_status = new PaymentStatus();
                    $payment_status->booking_id = $request_id;
                    $payment_status->firstname = $firstname;
                    $payment_status->transaction_id = $txnid;
                    $payment_status->amount = $amount;
                    $payment_status->productinfo = $productinfo;
                    $payment_status->mode = $mode;
                    $payment_status->bankcode = $bank_code;
                    $payment_status->bank_ref_num = $bank_ref_num;
                    $payment_status->status = $status;
                   if($payment_status->save()){
                       /*echo "<h3>Your order code is " . $productinfo . ".</h3>";
                       echo "<h4>Your Transaction ID for this transaction is " . $txnid . ".</h4>";
                       echo "<h4>We have received a payment of Rs. " . $amount . ". Your order will soon be shipped.</h4>";*/
                       //$this->layout = false;
                       //$this->render('//login/success');
                   }else{
                       //$this->layout = false;
                       //$this->render('//login/failure');
                   }
                
                // echo "<h3>Your order status is " . $status . ".</h3>";
                
                
            }
        }else{
            $this->layout = false;
            $this->render('//login/failure');
        }
    }
    
    public function actionFailure() {
        $status = $_POST ["status"];
        $firstname = $_POST ["firstname"];
        $amount = $_POST ["amount"];
        $txnid = $_POST ["txnid"];
        
        $posted_hash = $_POST ["hash"];
        $key = $_POST ["key"];
        $productinfo = $_POST ["productinfo"];
        $email = $_POST ["email"];
        $salt = $this->_SALT;
        $order_id = $_POST ["udf1"];
        if(isset($_POST ["additional_charges"])){
            $additional_charges=$_POST ["additional_charges"];
        }else{
            $additional_charges=null;
        }
        
        If ($additional_charges != null) {
            //$additionalCharges = $_POST ["additional_charges"];
            $retHashSeq = $additional_charges . "|" . $salt . "|" . $status  . "||||||" . $_POST["udf5"] . "|" . $_POST["udf4"] . "|" . $_POST["udf3"] . "|" . $_POST["udf2"] . "|" . $_POST["udf1"] . "|" .
                $email . "|" . $firstname . "|" . $productinfo . "|" . $amount . "|" . $txnid . "|" . $key ;
        } else {
            
            $retHashSeq =  $salt . "|" . $status  . "||||||" . $_POST["udf5"] . "|" . $_POST["udf4"] . "|" . $_POST["udf3"] . "|" . $_POST["udf2"] . "|" . $_POST["udf1"] . "|" .
                $email . "|" . $firstname . "|" . $productinfo . "|" . $amount . "|" . $txnid . "|" . $key ;
        }
        $hash = hash ( "sha512", $retHashSeq );
        
        if ($hash != $posted_hash) {
            
            echo "Invalid Transaction. Please try again";
        } else {
            $this->layout = false;
            $this->render('//login/failure');
            /*echo "<h3>Your order status is " . $status . ".</h3>";
            echo "<h4>Your transaction id for this transaction is " . $txnid . ". You may re-try making the payment later.</h4>";*/
        }
        
    }
    
}

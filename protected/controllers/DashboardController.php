<?php

class DashboardController extends Controller {

    public $layout = '//layouts/column2';
    public $page_title = 'Dashboard';

    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
//            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view','loadmap','loadloginstatus'),
                'users' => array('admin'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionIndex() {
        $this->activeLink = 'dashboard';
        $all_user_address=array();
        //if(isset($_SESSION['user_type'])&&!empty($_SESSION['user_type'])){
          //  $type = $_SESSION['user_type'];
        //}else{
          //  $type = 'doothan';
        //}
        $type = 'requester';
        // $all_user_details = Users::model()->findAllByAttributes(array('member_type'=>$type,'status'=>2));
        // if(count($all_user_details)>0){
        //     foreach($all_user_details as $all_user){
        //         $singleAddress = UserAddress::model()->findByAttributes(array('user_id'=>$all_user['id']));
        //         if(count($singleAddress)){
        //             $location = $singleAddress->city . ' ' . $singleAddress->state . ' ' . $singleAddress->postal_code;
        //             $geoLocation = Helper::getCoordinates($location);
        //             $lat = $geoLocation['lat'];
        //             $long = $geoLocation['long'];
        //             $user_info = $all_user['first_name']." ".$all_user['last_name'];
        //             $all_user_address[]=array(
        //                 'title'=>$singleAddress->city,
        //                 'lat'=>"$lat",
        //                 'lng'=>"$long",
        //                 'description'=>"<span style=color:#000;font-weight:bold;>$user_info</span><br><span style=color:#000;>$singleAddress->city<br>$singleAddress->state<br>$singleAddress->postal_code</span>"
        //             );
        //         }
        //     }
        // }
        $all_user_address[]= array();
        // $User_model = new Users('search');
        // $User_model->unsetAttributes();  // clear any default values
        // $model->member_type != 'requester';
        if (isset($_GET['Users']))
            $User_model->attributes = $_GET['Users'];
            $this->render('index',array('all_user_address'=>$all_user_address,'type'=>$type));
    }
    public function actionLoadmap(){
        ini_set('max_execution_time', 300); 
        $usertype = $_POST['value'];
      //  $_SESSION['user_type'] = $usertype;
        //echo $usertype;
        $all_user_details = Users::model()->findAllByAttributes(array('member_type'=>$usertype,'status'=>2));
        if(count($all_user_details)>0){
            foreach($all_user_details as $all_user){
                $singleAddress = UserAddress::model()->findByAttributes(array('user_id'=>$all_user['id']));
                if(count($singleAddress)){
                    $location = $singleAddress->city . ' ' . $singleAddress->state . ' ' . $singleAddress->postal_code;
                    $geoLocation = Helper::getCoordinates($location);
                    $lat = $geoLocation['lat'];
                    $long = $geoLocation['long'];
                    $user_info = $all_user['first_name']." ".$all_user['last_name'];
                    $all_user_address[]=array(
                        'title'=>$singleAddress->city,
                        'lat'=>"$lat",
                        'lng'=>"$long",
                        'description'=>"<span style=color:#000;font-weight:bold;>$user_info</span><br><span style=color:#000;>$singleAddress->city<br>$singleAddress->state<br>$singleAddress->postal_code</span>"
                    );
                }
            }
        }
        $this->renderPartial('load_map',array('all_user_address'=>$all_user_address,'type'=>$usertype));
    }

    public function actionLoadloginstatus(){
        $usertype = $_POST['value'];
        //$_SESSION['user_type'] = $usertype;

        //$this->$layout = '//layouts/column2';

        $docPending     = Users::model()->findAllByAttributes(array('account_status' => 'DOCUMENTS_PENDING' , 'member_type'=>$usertype,'status'=>2));
        $docPendCount   = count($docPending);

        $callVerfPend   = Users::model()->findAllByAttributes(array('account_status' => 'CALL_VERIFICATION_PENDING' , 'member_type'=>$usertype,'status'=>2));
        $calVerfCount   = count($callVerfPend);

        $rejectedUser   = Users::model()->findAllByAttributes(array('account_status' => 'APPROVED' , 'member_type'=>$usertype,'status'=>2));
        $rejUserCount   = count($rejectedUser);

        $apprUser       = Users::model()->findAllByAttributes(array('account_status' => 'REJECTED' , 'member_type'=>$usertype,'status'=>2));
        $apprUserCount  = count($apprUser);

        
        
        $this->renderPartial('load_login_status',array('userType' => $usertype,'docPending'=>$docPendCount,'callVerfPend'=>$calVerfCount ,'rejectedUser' => $rejUserCount , 
            'apprUser' => $apprUserCount ));
    }

    
}

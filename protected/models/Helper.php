<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Helper extends CActiveRecord {

    public static function requestorCount() {
        $userList = Users::model()->findAllByAttributes(array('member_type' => 'requester'));
        $userCount = count($userList);
        return $userCount;
    }

    public static function doothanCount() {
        $doothanList = Users::model()->findAllByAttributes(array('member_type' => 'doothan'));
        $doothanCount = count($doothanList);
        return $doothanCount;
    }
    public static function dropboxCount() {
        $dropboxList = Users::model()->findAllByAttributes(array('member_type' => 'dropbox'));
        $dropboxCount = count($dropboxList);
        return $dropboxCount;
    }
    public static function deliveryCount() {
        $deliveryList = Request::model()->findAllByAttributes(array('status' => 'Delivered'));
        $deliveryCount = count($deliveryList);
        return $deliveryCount;
    }

    public static function active_users(){
        $activeList = Users::model()->findAllByAttributes(array('status' => '2'));
        $activeCount = count($activeList);
        return $activeCount;
    } 
    public static function inactive_users(){
        $inactiveList = Users::model()->findAllByAttributes(array('status' => '1'));
        $inactiveCount = count($inactiveList);
        return $inactiveCount;
    } 
    public static function banned_users(){
        $bannedList = Users::model()->findAllByAttributes(array('status' => '0'));
        $bannedCount = count($bannedList);
        return $bannedCount;
    } 
    public static function order_status($status){
        $OrderStatus = Request::model()->findAllByAttributes(array('status' => $status));
        $orderCount = count($OrderStatus);
        return $orderCount;
    }
    public static function order_status_by_user($status,$user_id){
        $OrderStatus = Request::model()->findAllByAttributes(array('status' => $status,'user_id'=>$user_id));
        $orderCount = count($OrderStatus);
        return $orderCount;
    }
    public static function login_status($status , $memberType){
        $LoginStatus = Users::model()->findAllByAttributes(array('account_status' => $status , 'member_type' => $memberType));
        $loginCount = count($LoginStatus);
        return $loginCount;
    }
    public static function order_status_percentage($status){
        $all_orders = Request::model()->findAll();
        $OrderStatus = Request::model()->findAllByAttributes(array('status' => $status));
        $orderCount = count($OrderStatus);
        $percentaged_data = (count($all_orders) * $orderCount)/100;
        return $percentaged_data;
    } 
    public static function getDatesOfQuarter($quarter = 'current', $year = null, $format = null) {
        if (!is_int($year)) {
            $year = (new DateTime)->format('Y');
        }
        $current_quarter = ceil((new DateTime)->format('n') / 3);

        $quarter = (!is_int($quarter) || $quarter < 1 || $quarter > 4) ? $current_quarter : $quarter;
        $start = new DateTime($year . '-' . (3 * $quarter - 2) . '-1 00:00:00');
        $end = new DateTime($year . '-' . (3 * $quarter) . '-' . ($quarter == 1 || $quarter == 4 ? 31 : 30) . ' 23:59:59');

        return array(
            'start' => $format ? $start->format($format) : $start,
            'end' => $format ? $end->format($format) : $end,
        );
    }

    // print_r(get_dates_of_quarter(1,2017, 'Y-m-d'));

    public static function userCntByDates() {
        $countList = array();
        for ($i = 1; $i < 5; $i++) {
            $dateTimes = Helper::getDatesOfQuarter($i, 2017, 'Y-m-d');
            $dtStart = $dateTimes['start'];
            $dtEnd = $dateTimes['end'];
            $userList = Yii::app()->db->createCommand()
                    ->select('*')
                    ->from('users t1')
                    ->where("date(t1.created) BETWEEN date('$dtStart') AND date('$dtEnd')")
                    ->queryAll();
            $userCount = count($userList);
            $dates[] = array($dtStart, $dtEnd);
            array_push($countList, array(
                'y' => "2017 Q$i",
                'requestor' => $userCount,
            ));
        }
        //$countListJson 	= json_encode($countList);
        return $countList;
    }

    public static function status() {
        return array(
            array(
                'id' => '2',
                'name' => 'active'
            ),
            array(
                'id' => '1',
                'name' => 'inactive'
            ),
            array(
                'id' => '0',
                'name' => 'banned'
            )
        );
    }

    public static function getCoordinates($address) {
        $address = preg_replace('/\s+/', '+', str_replace(array("\r\n", "\r", "\n"), ',', trim($address)));
        $url = "https://maps.google.com/maps/api/geocode/json?sensor=false&address=$address&key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY";
        $response = file_get_contents($url);
        $output = json_decode($response);
        if(!empty($output->results)){
            $result['lat'] = $output->results[0]->geometry->bounds->northeast->lat;
            $result['long'] = $output->results[0]->geometry->bounds->northeast->lng;
            //echo $result['lat']."=>".$result['long'];die;
            $result['place_id'] = $output->results[0]->place_id;
        }else{
            $result['lat']='';
            $result['long']='';
            $result['place_id']='';
        }
        return $result;
    }
    public static function getDistance($lat1, $lon1, $lat2, $lon2, $unit,$user_id) {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $miles_last = $miles * 1.609344;
        $unit = strtoupper($unit);
        return array(
            'miles'=>$miles_last,
            'user_id'=>$user_id,
        );
    }

    public static function requestStatus() {
        return array(
            array(
                'id' => 'Request Placed',
                'name' => 'Request Placed'
            ),
            array(
                'id' => 'Waiting for payment',
                'name' => 'Waiting for payment'
            ),
            array(
                'id' => 'Payment in progress',
                'name' => 'Payment in progress'
            ),
            array(
                'id' => 'Payment completed',
                'name' => 'Payment completed'
            ),
            array(
                'id' => 'Delivered to dropbox',
                'name' => 'Delivered to dropbox'
            ),
            array(
                'id' => 'Received to dropbox',
                'name' => 'Received to dropbox'
            ),
            array(
                'id' => 'Delivered',
                'name' => 'Delivered'
            ),
            array(
                'id' => 'Delivered to user',
                'name' => 'Delivered to user'
            ),
            array(
                'id' => 'Cancelled',
                'name' => 'Cancelled'
            )
        );
    }

    public static function dateFormat($date) {
        date_default_timezone_set('Asia/Kolkata');
        $date           = strtotime($date);
        $returnDate     = date('j F Y',$date);
        return $returnDate;
    } 

    public static function getLocationDistance($params) {
       
        $addressFrom = preg_replace('/\s+/', '+', str_replace(array("\r\n", "\r", "\n"), ',', trim($params[0])));
        $addressTo = preg_replace('/\s+/', '+', str_replace(array("\r\n", "\r", "\n"), ',', trim($params[1])));
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$addressFrom&destinations=$addressTo&key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY";
        $response = file_get_contents($url);
       
        $output = json_decode($response);
        if($output->rows[0]->elements[0]){
          $result=$output->rows[0]->elements[0]->distance->text;  
          if($result=="1 m"){
              $result = "0 m";
          }
        }else{
            $result=0;
        }
        
        return $result;
    }
    
    public static function getLocationDistance_pickup($params) {
        
        $addressFrom = preg_replace('/\s+/', '+', str_replace(array("\r\n", "\r", "\n"), ',', trim($params[0])));
        $addressTo = preg_replace('/\s+/', '+', str_replace(array("\r\n", "\r", "\n"), ',', trim($params[1])));
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$addressFrom&destinations=$addressTo&key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY";
        $response = file_get_contents($url);
        
        $output = json_decode($response);
        //echo "<pre>";print_r($output);die;
        if($output->rows[0]->elements[0]){
            $result=$output->rows[0]->elements[0]->distance->text;
            if($result=="1 m"){
                $result = "0 m";
            }
        }else{
            $result=0;
        }
        
        return $result;
    }
    public static function userGender() {
        return array(
            array(
                'id' => 'Male',
                'name' => 'male'
            ),
            array(
                'id' => 'Female',
                'name' => 'female'
            )
        );
    }
    
    public static function getDistrict($pin_code){
        $url = "https://maps.google.com/maps/api/geocode/json?address=$pin_code&sensor=false&key=AIzaSyCo5F-SOvu8_wVKVpPSl_3_t4V9fbSHdwA";
        //$url = "https://maps.google.com/maps/api/geocode/json?sensor=false&address=$address&key=AIzaSyA7IZt-36CgqSGDFK8pChUdQXFyKIhpMBY";
        
        $response = file_get_contents($url);
        $output = json_decode($response);
        $DistrictList =  json_decode( json_encode($output), true);
        return $DistrictList['results'][0]['address_components'][1]['long_name'];
        //echo "<pre>";print_r($output);die;
    }

}
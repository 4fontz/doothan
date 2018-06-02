<?php

class Common {

    public static function validateImage($path) {
        $size = getimagesize($path);
        if (!$size) {
            return false;
        }

        $validTypes = array(IMAGETYPE_GIF => 'gif', IMAGETYPE_JPEG => 'jpeg', IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_BMP => 'bmp');

        if (in_array($size[2], array_keys($validTypes))) {
            return $validTypes[$size[2]];
        } else {
            return false;
        }
    }

    public static function getExtension($mime_type) {

        $extensions = array('image/gif' => 'gif', 'image/png' => 'png', 'image/jpeg' => 'jpeg', 'image/JPEG' => 'JPEG', 'image/PNG' => 'PNG', 'image/GIF' => 'GIF');
        return $extensions[$mime_type];
    }

    public static function sendPushNotification($device_id, $message) {

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $registatoin_ids = array(
            $device_id
        ); // should be an array of device ids read from db

        $message = array(
            "message" => $message
        );

        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message
        );

        $headers = array(
            'Authorization: key=' . Yii::app()->params['google_api_key'],
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    public static function getTimezone($time = "", $format = "") {
        // timezone by php friendly values
        $date = new DateTime($time, new DateTimeZone('UTC'));
        if(isset($_COOKIE['Timezone'])){
            $date->setTimezone(new DateTimeZone($_COOKIE['Timezone']));
        }else{
            $date->setTimezone(new DateTimeZone('IST'));
        }
        $time= $date->format($format);
        return $time;
        //set the timezone here
        
    }
    public static function activityLog($user_id,$type,$message,$created_on){
        $Activity = new ActivityLog();
        $Activity->user_id=$user_id;
        $Activity->type=$type;
        $Activity->message=$message;
        $Activity->created_on=$created_on;
        $Activity->save();
    }
    public static function generate_by_uniq($length){
//         $random = '';
//         for ($i = 0; $i < $length; $i++) {
//             $random .= chr(rand(ord('0'), ord('9')));
//         }
        $six_digit_random_number = mt_rand(100000, 999999);
        return "Dh-".$six_digit_random_number;
    }

}

?>

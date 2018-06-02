<?php

/**
 * Manage SMS System
 *
 */
class PhoneVerifierProcessor {

    /* protected static $apiKey = '5013f5ff';
    protected static $apiSecret = 'bf7f7a7f'; */
    
      protected static $apiKey = '81c5f9cf';
      protected static $apiSecret = '1a7e06a82db68cb7';

    public static function verifyRequest($number) {
        $serviceHost = 'https://api.nexmo.com/verify/json?';
        $response = array('status' => false, 'message' => 'Failed to send verification code. Please try again later.');

        if (isset($number) && $number != '') {
            $brand = 'Doothan';
            $number = str_replace('+', '', $number);
            $url = $serviceHost . http_build_query([
                        'api_key' => self::$apiKey,
                        'api_secret' => self::$apiSecret,
                        'number' => $number,
                        'brand' => $brand,
                        'pin_expiry'=>900
            ]);

            $ret = self::makeRequest($url);
            $ret = json_decode($ret, true);
            //echo "<pre>";print_r($ret);die;
            if (trim($ret['status']) == 3) {
                $error_text = 'please provide a valid phone number';
            } else if (trim($ret['status']) == 11 || trim($ret['status']) == 10) {
                $error_text = 'please try again after 5 minutes';
            } else {
                //$error_text = $ret['error_text'];
                $error_text = '';
            }

            if (isset($ret['status']) && ($ret['status'] == 0 || trim($ret['status']) == 11 || trim($ret['status']) == 10)) {
                $response = array('status' => true, 'request_id' => $ret['request_id']);
            } else {
                $response = array('status' => false, 'message' => $error_text);
            }
        }

        return $response;
        exit;
    }

    public static function verifyCode($data = array()) {
        $serviceHost = 'https://api.nexmo.com/verify/check/json?';
        $response = array('status' => false, 'message' => 'Verfication code doesn\'t match');

        if (isset($data) && !empty($data) && isset($data['code']) && !empty($data['code']) && isset($data['request_id']) && !empty($data['request_id'])) {
            $url = $serviceHost . http_build_query([
                        'api_key' => self::$apiKey,
                        'api_secret' => self::$apiSecret,
                        'request_id' => $data['request_id'],
                        'code' => $data['code'],
            ]);

            $ret = self::makeRequest($url);
            $ret = json_decode($ret, true);

            if (isset($ret['status']) && $ret['status'] == 0) {
                $response = array('status' => true, 'data' => $ret);
            } else {
                $response = array('status' => false, 'message' => $ret['error_text'], 'user_id' => "",'access_token' =>"",'expires' => "");
            }
        }

        return $response;
        exit;
    }
    public static function forgotPassword($number,$rand) {
        $serviceHost = 'https://rest.nexmo.com/sms/json?';
        $response = array('status' => false, 'message' => 'Failed to send verification code. Please try again later.');
        if (isset($number) && $number != '') {
            $brand = 'Doothan';
            $number = str_replace('+', '', $number);
            $url = $serviceHost . http_build_query([
                        'api_key' => self::$apiKey,
                        'api_secret' => self::$apiSecret,
                        'to' => $number,
                        'from' => $brand,
                        'text' => 'Your Doothan password reset code is '.$rand
            ]);
            $ret = self::makeRequest($url);
            $ret = json_decode($ret, true);
            if (trim($ret['messages'][0]['status']) == 3) {
                $error_text = 'please provide a valid phone number';
            } else if (trim($ret['messages'][0]['status']) == 11 || trim($ret['messages'][0]['status']) == 10) {
                $error_text = 'please try again after 5 minutes';
            } else {
                $error_text = $ret['messages'][0]['error-text'];
            }

            if (isset($ret['messages'][0]['status']) && $ret['messages'][0]['status'] == 0) {
                $response = array('status' => true, 'request_id' => $ret['request_id']);
            } else {
                $response = array('status' => false, 'message' => $error_text);
            }
        }
        return $response;
        exit;
    }
    protected static function makeRequest($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

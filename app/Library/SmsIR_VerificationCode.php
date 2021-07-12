<?php

namespace App\Library;

class SmsIR_VerificationCode
{

    protected function getAPIVerificationCodeUrl()
    {
        return "api/VerificationCode";
    }


    protected function getApiTokenUrl()
    {
        return "api/Token";
    }

    public function __construct($APIKey, $SecretKey, $APIURL)
    {
        $this->APIKey = $APIKey;
        $this->SecretKey = $SecretKey;
        $this->APIURL = $APIURL;
    }


    public function verificationCode($Code, $MobileNumber)
    {
        $token = $this->_getToken($this->APIKey, $this->SecretKey);
        if ($token != false) {
            $postData = array(
                'Code' => $Code,
                'MobileNumber' => $MobileNumber,
            );

            $url = $this->APIURL.$this->getAPIVerificationCodeUrl();
            $VerificationCode = $this->_execute($postData, $url, $token);
            $object = json_decode($VerificationCode);

            $result = false;
            if (is_object($object)) {
                $result = $object;
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }
        return $result;
    }

    private function _getToken()

    {
        $postData = array(
            'UserApiKey' => $this->APIKey,
            'SecretKey' => $this->SecretKey,
            'System' => 'php_rest_v_2_0'
        );
        $postString = json_encode($postData);

        $ch = curl_init($this->APIURL.$this->getApiTokenUrl());
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result);

        $resp = false;
        $IsSuccessful = '';
        $TokenKey = '';
        if (is_object($response)) {
            $IsSuccessful = $response->IsSuccessful;
            if ($IsSuccessful == true) {
                $TokenKey = $response->TokenKey;
                $resp = $TokenKey;
            } else {
                $resp = false;
            }
        }
        return $resp;
    }


    private function _execute($postData, $url, $token)
    {
        $postString = json_encode($postData);

        $ch = curl_init($url);

        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'x-sms-ir-secure-token: '.$token
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}
/*
try {
    date_default_timezone_set("Asia/Tehran");

    // your sms.ir panel configuration
    $APIKey = "104fbcf2fe8a5de68efbcf";
    $SecretKey = "Aminam";
    $APIURL = "https://ws.sms.ir/";

    // your code
    $Code = "12345";

    // your mobile number
    $MobileNumber = "09129332006";

    $SmsIR_VerificationCode = new SmsIR_VerificationCode($APIKey, $SecretKey, $APIURL);
    $VerificationCode = $SmsIR_VerificationCode->verificationCode($Code, $MobileNumber);
    var_dump($VerificationCode);

} catch (Exeption $e) {
    echo 'Error VerificationCode : '.$e->getMessage();
}
*/


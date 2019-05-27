<?php
namespace api\helpers;

use common\helpers\MyCurl;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\web\UrlManager;

/**
 * Created by PhpStorm.
 * User: TuanPham
 * Date: 12/21/2016
 * Time: 9:40 AM
 */
class APIHelper
{
    const API_ANSWER = 'http://45.119.80.39:7070/questions/answer';
    const API_CHECK_VOUCHER_PHONE = '/TopupAPI/ScratchCard';
    // gọi sang bên nạp thẻ điện thoại
    public static function CallAPI($method, $url, $data = null)
    {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                    curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    $data =http_build_query($data);
                    yii::info('du lieu gui di'.$data);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        yii::info('du lieu tu ben voucher phone tra ve '.$result);
        curl_close($curl);

        return $result;
    }
    // gọi sang bên thẻ tvod2
    public static function apiQuery($method, $url, $data = null) {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        Yii::info('du lieu tu ben voucher phone tra ve '.$result);
        curl_close($curl);

        return $result;
    }

    public static function apiQueryV1($method, $url, $data = null, $authen = null) {
        $curl = curl_init();

        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        $headr = array();
        $headr[] = 'Content-Type: application/json';
        $headr[] = 'Accept: application/json';
        $headr[] = 'Authorization:Basic b21paHViczp5eVlzbVZ4aA==';

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);

        $result = curl_exec($curl);
//        if ($result === false)
//        {
//            // throw new Exception('Curl error: ' . curl_error($crl));
//            print_r('Curl error: ' . curl_error($curl));
//        }
        curl_close($curl);

        return $result;
    }

    public static function isResultSuccess($apiResults) {
        return ($apiResults != null) && ($apiResults['success'] == true);
    }

    public static function getOTP(){
        $length = rand(5,9);
        $chars = array_merge(range(0,9));
        shuffle($chars);
        $otp = implode(array_slice($chars, 0,$length));
        return $otp;
    }
}

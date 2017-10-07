<?php
/**
 * Created by PhpStorm.
 * User: HungChelsea
 * Date: 07-Oct-17
 * Time: 8:26 PM
 */

namespace console\controllers;


use yii\console\Controller;

class WeatherController extends  Controller
{
    public function actionGetFile(){
        // define some variables
        $local_file = '/backend/web/filename.jpg';
        $server_file = '/incoming/09_NIAPP/Weather';
        $ftp_server="ftp://ftp.nelen-schuurmans.nl";
        $ftp_user_name="greencoffee";
        $ftp_user_pass="nice cup of green coffee";

        $conn_id = ftp_connect($ftp_server);

        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written to $local_file\n";
        }
        else {
            echo "There was a problem\n";
        }
        ftp_close($conn_id);
    }
}
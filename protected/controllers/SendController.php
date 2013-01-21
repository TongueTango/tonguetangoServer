<?php

Yii::import('application.components.urbanairship.Airship');

class SendController extends Controller
{
    public function actionIndex()
    {
        // Your testing data
        $APP_MASTER_SECRET = '_oyD43aERV2VgUWpM7xC_A';
        $APP_KEY = 'e5MAdCH7RLqqXvJ_Rqq0-Q';
        $TEST_DEVICE_TOKEN = 'aa0114da1abc9fa3b17ae053955a04c9a6658c0e0c5b4edc16286225fe1a90be';

        
        $headers = array(
            
            'Content-Type: application/json',
        );

        $url = 'https://go.urbanairship.com/api/device_tokens/'.$TEST_DEVICE_TOKEN;
        $query = '{"alias": "AShwin"}';

        $ch = curl_init();

        // Clean up string
        $putString = stripslashes($query);
        // Put string into a temporary file
        $putData = tmpfile();
        // Write the string to the temporary file
        fwrite($putData, $putString);
        // Move back to the beginning of the file
        fseek($putData, 0);

        // Headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Binary transfer i.e. --data-BINARY
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $APP_KEY.":".$APP_MASTER_SECRET);
        curl_setopt($ch, CURLOPT_URL, $url);
        // Using a PUT method i.e. -XPUT
        curl_setopt($ch, CURLOPT_PUT, true);
        // Instead of POST fields use these settings
        curl_setopt($ch, CURLOPT_INFILE, $putData);
        curl_setopt($ch, CURLOPT_INFILESIZE, strlen($putString));

        $output = curl_exec($ch);
        echo $output;

        // Close the file
        fclose($putData);
        // Stop curl
        curl_close($ch);
    }
}
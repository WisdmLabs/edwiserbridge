<?php

/**
*
*/
class api_handler
{
/*    public function __construct()
    {

    }
*/

    protected static $instance = null;


    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }





    public function connect_to_wp_with_args($request_url, $request_data)
    {
        global $CFG;
        $success          = 1;
        $response_message = 'success';
        $request_url .= '/wp-json/edwiser-bridge/wisdmlabs/';

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $request_url,
            CURLOPT_TIMEOUT => 100
        ));

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
        $response = curl_exec($curl);
        if (curl_error($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return array("error"=> 1, "msg" => $error_msg );
        } else {
            curl_close($curl);
            return array("error"=> 0, "data" => json_decode($response));
        }
    }
}

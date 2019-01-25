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


        // $fields_string = http_build_query($request_data);

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



        /*if (is_wp_error($response)) {
            $success          = 0;
            $response_message = $response->get_error_message();
        } elseif (wp_remote_retrieve_response_code($response) == 200) {
            $body = json_decode(wp_remote_retrieve_body($response));
            if (!empty($body->exception)) {
                $success = 0;
                if (isset($body->debuginfo)) {
                    $response_message = $body->message . ' - ' . $body->debuginfo;
                } else {
                    $response_message = $body->message;
                }
            } else {
                $success       = 1;
                $response_data = $body;
            }
        } else {
            $success          = 0;
            $response_message = __('Please check Moodle URL !', 'eb-textdomain');
        }

        return array(
            'success'          => $success,
            'response_message' => $response_message,
            'response_data'    => $response_data,
        );*/

    }


}
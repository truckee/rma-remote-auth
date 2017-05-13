<?php

namespace Rma;

/**
 * Description of Tools
 *
 * @author George
 */
class Tools
{

    /**
     * Get RESTful API's response
     * 
     * @param type $email
     * @return Json Response
     */
    public function getData($email) {
        $uri = get_option('rma_user_data_uri');
        $authType = get_option('rma_auth_type');

        //create complete $_GET uri based on authentication type
        $args = [];
        switch ($authType) {
            case 'API key':
                $key = get_option('rma_auth_type_api_key');
                $keyName = get_option('rma_auth_type_api_key_field_name');
                $args = array(
                    'headers' => array(
                        $keyName => $key
                    )
                );
                break;
            case 'HTTP Basic':
                $ee = get_option('rma_auth_type_basic_username');
                $password = get_option('rma_auth_type_basic_password');
                $args = array(
                    'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode($username . ':' . $password)
                    )
                );
                break;
            default:

                break;
        }
        $getURI = $uri . '/' . $email;
//        var_dump($getURI, $args);die;
        $data = wp_remote_get($getURI, $args);
//        var_dump($data);die;

        return $data;
    }

    /**
     * Send register email
     */
    public function sendRegistrationData($email) {
        $args = [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'body' =>
            ['email' => $email,
            ]
        ];
        $uri = get_option('rma_user_data_uri');
        $sent = wp_remote_post($uri, $args);
    }

}

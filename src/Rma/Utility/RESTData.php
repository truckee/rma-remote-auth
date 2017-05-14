<?php

namespace Rma\Utility;

use Rma\Utility\Mailer;

/**
 * Description of RESTData
 *
 * @author George
 */
class RESTData
{
    private $headers;
    private $uri;

    public function __construct($uri) {
        $this->uri = $uri;
        
        $key = get_option('rma_auth_type_api_key');
        $keyName = get_option('rma_auth_type_api_key_field_name');
        $user = get_option('rma_auth_type_basic_username');
        $password = get_option('rma_auth_type_basic_password');
        $type = get_option('rma_auth_type');
        
        switch ($type) {
            case 'API key':
                $this->headers = [$keyName => $key];
                break;
            case 'HTTP Basic':
                $this->headers = ['Authorization' => 'Basic ' . base64_encode($username . ':' . $password)];
            default:
                break;
        }
    }

    /**
     * Get RESTful API's response
     * 
     * @param type $email
     * @return Json Response
     */
    public function getData($email) {
        //create complete $_GET uri based on authentication type
        $getURI = $this->uri . '/' . $email;
        $data = wp_remote_get($getURI, ['headers' => $this->headers]);

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
            'headers' => $this->headers,
            'body' =>
            ['email' => $email]
        ];
        $sent = wp_remote_post($this->uri, $args);
        if (null !== $sent && '200' == $sent['response']['code']) {
            $body = json_decode($sent['body']);
            $password = $body->password;
            $mailer = new Mailer();
            $wasSent = $mailer->registrantEmail($email, $password);
        }
        
        return $sent;
    }

}
                
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
     * Process login form
     *
     * Uses HTTP API to GET a user's encrypted password from remote host.
     * Entered password is verified against returned hash. If verified
     * user is given access to protected content
     *
     */
    static function validate_sign_in() {
        return (new self)->validateSignIn();
    }

    public function validateSignIn() {
        //$validEmail: null if not set, false if validation fails
        $validEmail = filter_input(INPUT_POST, '_email', FILTER_VALIDATE_EMAIL);
        //do check only when a email value present
        if (null !== $validEmail) {
            //save address to reproduce in failed form
            $validSignIn['_email_address'] = $validEmail;
            if (false === $validEmail) {
                //set error value to true
                $validSignIn['_email_error'] = true;
            }

            //get data for valid email
            $data = $this->getData($validEmail);
            if (null !== $data && '200' == $data['response']['code']) {
                //if good data returned
                $user = json_decode($data['body']);
                $validSignIn['found'] = true;
                
                //is user active?
                $statusField = get_option('rma_status_field_name');
                $statusValue = get_option('rma_status_field_value');
                $userStatus = $user[0]->$statusField;
                $validSignIn['active'] = ($userStatus == $statusValue) ? true : false;

                //may user register?
                $hash = $user[0]->password;
                $validSignIn['register'] = (null === $hash && $validSignIn['active']) ? true : false;

                //was password entered
                $password = filter_input(INPUT_POST, '_password');
                $validSignIn['pw_error'] = (empty($password)) ? true : null;

                //check active user's password
                $passwordVerified = password_verify($password, $hash);
                //if verified, show content link
                if ($passwordVerified && $validSignIn['active']) {
                    $_SESSION['rma_member_active'] = true;
                    $validSignIn['validated'] = true;
                }
                
                return $validSignIn;;
            }
            $validSignIn['rma_member_form_error'] = true;
        }

        return $validSignIn;
    }

    /**
     * Get RESTful API's response
     * 
     * @param type $email
     * @return Json Response
     */
    private function getData($email) {
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

}

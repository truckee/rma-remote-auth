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

    //validate User data URI
    static function validate_rma_user_data_uri() {
        $uri = filter_input(INPUT_POST, 'rma_user_data_uri');
        $uriFiltered = filter_var($uri, FILTER_VALIDATE_URL);
        if ($uri !== $uriFiltered) {
            $invalid = $uri . ' is an invalid User data URI';
            $empty = 'User data URI may not be empty';
            add_settings_error('rma_user_data_uri', 'rma_uri', (empty($uri) ? $empty : $invalid));
        }

        return $uri;
    }

    //validate Sstatus field name
    static function validate_rma_status_field_name() {
        $fieldName = filter_input(INPUT_POST, 'rma_status_field_name');
        if (empty($fieldName)) {
            add_settings_error('rma_status_field_name', 'rma_key', 'Field name may not be empty');
        }

        return $fieldName;
    }

    //validate Active status value
    static function validate_rma_status_field_value() {
        $value = filter_input(INPUT_POST, 'rma_status_field_value');
        if (empty($value)) {
            add_settings_error('rma_status_field_value', 'rma_key', 'Active status value may not be empty');
        }

        return $value;
    }

    //validate API key
    static function validate_rma_auth_type_api_key() {
        $key = filter_input(INPUT_POST, 'rma_auth_type_api_key');
        if (empty($key) && 'API key' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_api_key', 'rma_key', 'API key may not be empty');
        }

        return $key;
    }

    //validate Username
    static function validate_rma_auth_type_basic_username() {
        $name = filter_input(INPUT_POST, 'rma_auth_type_basic_username');
        if (empty($name) && 'HTTP Basic' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_basic_username', 'rma_name', 'Username may not be empty');
        }

        return $name;
    }

    //validate Password
    static function validate_rma_auth_type_basic_password() {
        $password = filter_input(INPUT_POST, 'rma_auth_type_basic_password');
        if (empty($password) && 'HTTP Basic' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_basic_password', 'rma_name', 'Password may not be empty');
        }

        return $password;
    }

    public function validateSignIn() {
        //$validEmail: null if not set, false if validation fails
        $validEmail = filter_input(INPUT_POST, '_email', FILTER_VALIDATE_EMAIL);
        //do check only when a email value present
        if (null !== $validEmail) {
            //assign $email to value in form
            $email = filter_input(INPUT_POST, '_email');
            //save address to reproduce in failed form
            $_SESSION['_email_address'] = $email;
            if (false === $validEmail) {
                //set error value to true
                $_SESSION['_email_error'] = true;
            }
            
            $data = $this->getData($email);
            $code = $data['response']['code'];
            if ('200' == $code) {
                //if good data returned
                $user = json_decode($data['body']);
                $hash = $user[0]->password;
                $password = $_POST['_password'];
                if (password_verify($password, $hash)) {
                    setcookie('rma_member', 'active', time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
                    //remove all sign in data
                    session_destroy();
                    wp_redirect(home_url('member-content'));
                    exit;
                }
            }
            $_SESSION['rma_member_form_error'] = true;
        }
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
                $getURI = $url . '/' . $key . '/' . $email;
                break;
            case 'HTTP Basic':
                $username = get_option('rma_auth_type_basic_username');
                $password = get_option('rma_auth_type_basic_password');
                $args = array(
                    'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode($username . ':' . $password)
                    )
                );
            default:
                $getURI = $uri . '/' . $email;
                break;
        }

        $data = wp_remote_get($getURI, $args);

        return $data;
    }

}

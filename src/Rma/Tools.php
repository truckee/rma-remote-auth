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
                $password = filter_input(INPUT_POST, '_password');
                
                //is user active?
                $statusField = get_option('rma_status_field_name');
                $statusValue = get_option('rma_status_field_value');
                $userStatus = $user[0]->$statusField;
                if ($userStatus != $statusValue) {
                    //user not active; return to sign-in with error msg
                    $_SESSION['status_error'] = true;
                    wp_redirect(home_url('member-sign-in'));
                    exit;
                }
                
                //check active user's password
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
                $keyName = get_option('rma_auth_type_api_key_field_name');
                $args = array(
                    'headers' => array(
                        $keyName => $key
                    )
                );
                break;
            case 'HTTP Basic':
                $username = get_option('rma_auth_type_basic_username');
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

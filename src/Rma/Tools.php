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
    static function member_password_check() {
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
            $url = get_option('rma_base_url');
            $get_user = get_option('rma_get_user');
            $getURI = $url . $get_user . '/' . $email;
            $data = wp_remote_get($getURI);
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

}

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
        if (isset($_POST['_email'])) {
            $email = $_POST['_email'];
            if (empty($email)) {
                die('No email provided!');
            }
            $url = get_option('rma_base_url');
            $get_hash = get_option('rma_get_hash');
            $getURI = $url . $get_hash . '/' . $email;
            $data = wp_remote_get($getURI);
            $code = $data['response']['code'];
            if ('200' == $code) {
                //if good data returned
                $pwObject = json_decode($data['body']);
                $hash = $pwObject[0]->password;
                $password = $_POST['_password'];
                if (password_verify($password, $hash)) {
                    setcookie('rma_member', 'active', time() + 3600, COOKIEPATH, COOKIE_DOMAIN);
                    wp_redirect(home_url('member-content'));
                    exit;
                }
            } else {
                die('Username/password not found');
            }
        }
    }

}

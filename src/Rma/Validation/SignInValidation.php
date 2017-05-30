<?php

namespace Rma\Validation;

use Rma\Utility\RESTData;

/**
 * Description of SignInValidation
 *
 * @author George
 */
class SignInValidation
{

    /**
     * Process login form
     *
     * Uses HTTP API to GET a user's encrypted password from remote host.
     * Entered password is verified against returned hash. If verified
     * user is given access to protected content
     *
     */
    static public function validateSignInForm()
    {
        return (new self)->validateSignIn();
    }

    private function validateSignIn()
    {
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
            $rest = new RESTData();
            $data = $rest->getData($validEmail);
            if (isset($data['data_error'])) {
                return $data;
            }
            //is member found?
            if (!isset($data['member'])) {
                return ['rma_member_not_found' => true];
            }
            $member = $data['member'];
            //is user active?
            $statusField = get_option('rma_status_field_name');
            $statusValue = ('true' === get_option('rma_status_field_value')) ? true : get_option('rma_status_field_value');
            $memberStatus = $member->$statusField;
            $validSignIn['active'] = ($memberStatus == $statusValue) ? true : false;
            //was password entered
            $password = filter_input(INPUT_POST, '_password');
            $validSignIn['pw_error'] = (empty($password)) ? true : null;
            //may user register?
            $hash = $member->password;
            $validSignIn['register'] = ($validSignIn['active'] && empty($hash)) ? true : false;
            if ($validSignIn['register']) {
                return $validSignIn;
            }
            //check active user's password
            $passwordVerified = password_verify($password, $hash);
            //if verified, show content link
            if ($passwordVerified && $validSignIn['active']) {
                $_SESSION['rma_member_active'] = true;
                $validSignIn['validated'] = true;

                return $validSignIn;
            }
            $validSignIn['rma_member_not_found'] = true;
        }

        return $validSignIn;
    }
}

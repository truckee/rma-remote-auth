<?php

namespace Rma\Validation;

use Rma\Utility\RESTData;

/**
 * Description of ForgotPassword
 *
 * @author George
 */
class ForgotPasswordValidation
{

    static public function validateForgotPasswordForm() {
        return (new self)->validateForgotPassword();
    }

    private function validateForgotPassword() {
        //$validEmail: null if not set, false if validation fails
        $validEmail = filter_input(INPUT_POST, '_email', FILTER_VALIDATE_EMAIL);
        if ($validEmail) {
            //check if member
            $rest = new RESTData();
            if (is_wp_error($data = $rest->getSingleMemberData($validEmail))) {
                $validForgot['data_error'] = true;
                
                return $validForgot;
            }
            if (null !== $data && isset($data['member'])) {
                $validForgot['valid'] = true;
                $validForgot['email'] = $validEmail;
                
                return $validForgot;
            } else {
                $validForgot['rma_member_not_found'] = true;
            }
        } 
            
        return $validForgot['_email_error'] = true;
    }

}

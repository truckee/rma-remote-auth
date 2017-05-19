<?php

namespace Rma\Validation;

/**
 * Description of ResetPasswordValidation
 *
 * @author George
 */
class ResetPasswordValidation
{
    public function validateResetPasswordGet() {
        $validGet = filter_input(INPUT_GET, 'rp');
        
        return $validGet === $_SESSION['reset']['time'];
    }
    
    public function validateResetPasswordPost() {
        $pw1 = filter_input(INPUT_POST, '_password1');
        $pw2 = filter_input(INPUT_POST, '_password2');
        
        return !empty($pw1) && $pw1 === $pw2;
    }

}
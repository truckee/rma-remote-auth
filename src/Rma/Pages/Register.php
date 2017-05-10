<?php

namespace Rma\Pages;

/**
 * Description of Register
 *
 * @author George
 */
class Register
{

    public static function createRegisterForm() {
        return (new self)->registerForm();
    }
    
    public function registerForm() {
        $form = '<div><h3>Greetings, Planet Earth!</h3></div>';
        
        return $form;
    }
}

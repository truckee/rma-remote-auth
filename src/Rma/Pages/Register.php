<?php

namespace Rma\Pages;

use Rma\Utility\RESTData;

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
    
    private function registerForm() {
        unset($_SESSION['rma_member_active']);
        $form = '<h3 style="text-align: center;" class="text-success">Thank you for registering</h3> '
                . '<p>An email will be sent to ' . $_SESSION['rma_email'] . ' with an '
                . 'automatically generated password.';
        $email = $_SESSION['rma_email'];
        unset($_SESSION['rma_email']);
        $uri = get_option('rmaSetPasswordURI');
        $tools = new RESTData();
        $result = $tools->sendRegistrationData($email);
        
        return $form;
    }
}

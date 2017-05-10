<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rma\Pages;

/**
 * Description of SigninForm
 *
 * @author George
 */
class Pages
{

    public static function createRegisterForm() {
        return (new self)->registerForm();
    }
    
    public function registerForm() {
        $form = '<div><h3>Greetings, Planet Earth!</h3></div>';
        
        return $form;
    }
}

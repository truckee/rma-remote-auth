<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rma\Shortcodes;

/**
 * Description of SigninForm
 *
 * @author George
 */
class SigninForm
{

    public static function createSignInForm() {
        //allow for dynamic form on a static form call
        return (new self)->signinForm();
    }

    public function signinForm() {
        define('USER_DATA_URI_ERROR', 'User data URI not set');
        define('INVALID_EMAIL', 'Invalid email');
        define('NOT_FOUND', 'Member credentials not found');
        if (empty(get_option('rma_user_data_uri'))) {
            
            $form = <<<EOT
<div class="panel panel-info">
    <div class="panel-heading">Member sign-in</div>
    <div class="panel-body">
                    <div class="row"><div class="col-md-7 text-danger">
EOT;
            $form .= USER_DATA_URI_ERROR;
            $form .= '</div></div></div></div>';
            return $form;
        }
        $email = '';
        //retrieve entered email address if exists
        if (isset($_SESSION['_email_address'])) {
            $email = $_SESSION['_email_address'];
            unset($_SESSION['_email_address']);
        }
        //was an invalid email entered?
        $mailError = '';
        if (isset($_SESSION['_email_error'])) {
            $mailError = '</div><div class="row"><div class="col-md-7 text-danger">' . INVALID_EMAIL . '</div>';
            //clear error
            unset($_SESSION['_email_error']);
        }
        //were credentials not found?
        $formError = '';
        if (isset($_SESSION['rma_member_form_error'])) {
            $formError = '<br><div class="row"><div class="col-md-7 text-danger">' . NOT_FOUND . '</div></div>';
            unset($_SESSION['rma_member_form_error']);
        }
        $form = <<<EOT
<div class="panel panel-info">
    <div class="panel-heading">Member sign-in</div>
    <div class="panel-body">
        <form action=""  method="post">
            <input  type="hidden" name="action" value="member_sign_in">
            <div class="row">
                <div class="col-md-4">Email address</div>
EOT;
        $form .= '<div class="col-md-3"><input type="text" name="_email" value="' . $email . '"/></div>' .
                $mailError;
        $formPassword = <<<EOT
   </div>
            <br />
            <div class="row">
                <div class="col-md-4">Password</div>
                <div class="col-md-3"><input type="password" name="_password" /></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4"><input type="submit" value="Sign in" /></div>
            </div>
        </form>
EOT;
        $form .= $formPassword . $formError . '</div></div>';

        return $form;
    }

}

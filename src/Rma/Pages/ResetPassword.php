<?php

namespace Rma\Pages;

use Rma\Validation\ResetPasswordValidation;
use Rma\Utility\RESTData;

/**
 * Description of ResetPassword
 *
 * @author George
 */
class ResetPassword
{

    public static function createResetPasswordForm() {
        return (new self)->createResetPassword();
    }

    private function createResetPassword() {
        $validReset = [];
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'GET') {
            $resetData = filter_input_array(INPUT_GET);
            $validator = new ResetPasswordValidation();
            $validGet = $validator->validateResetPasswordGet();
        }
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $resetData = filter_input_array(INPUT_POST);
            $validator = new ResetPasswordValidation();
            $validPost = $validator->validateResetPasswordPost();
        }

        $getError = $postError = '';

        $form = '<div class="panel panel-info">
    <div class="panel-heading">Create new password</div>
    <div class="panel-body">
    <form action=""  method="post">
            <div class="row">
                <div class="col-sm-12">';
        $footer = '</div></div>';

        if (isset($validGet) && false == $validGet) {
            $getError = '<div class="row"><div class="col-sm-7 text-danger">' . RP_GET_ERROR . '</div></div>';
            $form .= $getError;
            $form .= $footer;

            return $form;
        }
        if (isset($validPost) && true == $validPost) {
            $email = $_SESSION['reset']['email'];
            $pw1 = filter_input(INPUT_POST, '_password1');
            $hash = password_hash($pw1, PASSWORD_BCRYPT);
            $rest = new RESTData();
            $rest->setMemberPassword($email, $hash);
            $form .= '<div class="row"><div class="col-sm-7 text-success">Your password is updated</div></div>';
            $form .= $footer;
            unset($_SESSION['reset']);

            return $form;
        }

        $form .= '<div class="row">
    <div class="col-md-4">Password</div>
    <div class="col-md-3"><input type="password" name="_password1" /></div>
</div>
<div class="row">
    <div class="col-md-4">Confirm password</div>
    <div class="col-md-3"><input type="password" name="_password2" /></div>
</div>
';

        if (isset($validPost) && false == $validPost) {
            $postError = '<div class="row"><div class="col-sm-7 text-danger">' . PW_MATCH_ERROR . '</div></div>';
            $form .= $postError;
        }

        $form .= '<div class="row"><div class="col-sm-4"><input type="submit" value="Reset password" /></div></div>
            </div>
        </div>
            </form>
        </div></div>';

        return $form;
    }

}

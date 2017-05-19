<?php

namespace Rma\Pages;

use Rma\Utility\Mailer;

use Rma\Validation\ForgotPasswordValidation;

/**
 * Description of ForgotPassword
 *
 * @author George
 */
class ForgotPassword
{

    public static function createForgotPasswordForm() {
        return (new self)->forgotPasswordForm();
    }

    private function forgotPasswordForm() {
        $validForgot = [];
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $forgotData = filter_input_array(INPUT_POST);
            $validator = new ForgotPasswordValidation();
            $validForgot = $validator->validateForgotPasswordForm();
        }

        $form = '<div class="panel panel-info">
    <div class="panel-heading">Password reset</div>
    <div class="panel-body">
    <form action=""  method="post">
            <div class="row">
                <div class="col-sm-12">';
        $footer = '</div></div>';

        //was an invalid email entered?
        $mailError = '';
        if (isset($validForgot['_email_error'])) {
            $mailError = '<div class="row"><div class="col-sm-7 text-danger">' . INVALID_EMAIL . '</div></div>';
        }
        //were credentials not found?
        $formError = '';
        if (isset($validForgot['rma_member_not_found'])) {
            $formError = '<div class="row"><div class="col-sm-7 text-danger">' . NOT_FOUND . '</div></div>';
        }

        //was valid data received?
        if (isset($validForgot['data_error'])) {
            $form .= '<div class="row"><div class="col-sm-7 text-danger">' . DATA_ERROR . '</div></div>';
            $form .= $footer;

            return $form;
        }
        if (isset($validForgot['valid'])) {
            $_SESSION['reset']['time'] = md5(time());
            $_SESSION['reset']['email'] = $validForgot['email'];
            $form .= '<div class="row"><div class="col-sm-12 text-success">Check your email for a reset password link</div></div>';
            $form .= $footer;
            $mailer = new Mailer();
            $mailer->forgotPasswordEmail($validForgot['email']);
            
            return $form;
        }
        $form .= $mailError;
        $form .= '<p>We will send a link for resetting your password to the email address submitted here: </p>
                    <div class="row"><div class="col-sm-4">Email address</div>
                        <div class="col-sm-3"><input type="text" name="_email" value=""/></div>
                    </div>
            <div class="row">';
        $form .= $formError;
        $form .= '<div class="col-sm-4"><input type="submit" value="Request reset" /></div>
            </div>
            </form>
        </div>
        </div>';

        return $form;
    }

}

<?php

namespace Rma\Pages;

use Rma\Validation\SignInValidation;

/**
 * Description of SignIn
 *
 * @author George
 */
class SignIn
{

    public static function createSignInForm() {
        //allow for dynamic form on a static form call
        return (new self)->signinForm();
    }

    private function signinForm() {
        $validSignIn = [];
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $validator = new SignInValidation();
            $validSignIn = $validator->validateSignInForm();
        }

        $header = '<div class="panel panel-info">
    <div class="panel-heading">Member sign-in</div>
    <div class="panel-body">';
        $formDanger = '<div class="row"><div class="col-sm-7 text-danger">';
        $footer = str_repeat('</div>', 4);

        $form = $header;

        //show error if required options not set
        if (empty(get_option('rma_user_data_uri')) || empty(get_option('rma_status_field_name')) || empty(get_option('rma_status_field_value'))) {
            $form .= $formDanger;
            $form .= USER_DATA_URI_ERROR;
            $form .= $footer;

            return $form;
        }
        
        //show error if REST api unreachable
        if (isset($validSignIn['data_error'])) {
            $form .= $formDanger;
            $form .= DATA_ERROR;
            $form .= $footer;

            return $form;
        }

        //if validated member show link to content
        if (isset($validSignIn['validated'])) {
            $pageId = url_to_postid($_SESSION['memberContentURI']);
            $title = get_the_title($pageId);
            $form .= '<div class="row"><div class="col-sm-7"> '
                    . '<a href="' . $_SESSION['memberContentURI'] . '">Go to ' . $title . '</a>';
            unset($_SESSION['memberContentURI']);
            $form .= $footer;

            return $form;
        }

        //if member exists without password, allow to register
        if (isset($validSignIn['register']) && $validSignIn['register']) {
            $registerPath = get_permalink(get_page_by_path('rma-register'));
            $_SESSION['rma_email'] = $validSignIn['_email_address'];
            $_SESSION['rma_member_active'] = true;
            $form .= '<div class="row"><div class="col-sm-10"> '
                    . '<a href="' . $registerPath . '">Click here to register for content access</a>';
            $form .= $footer;

            return $form;
        }

        //if member not active
        if (isset($validSignIn['active']) && !$validSignIn['active']) {
            $form .= $formDanger;
            $form .= NOT_ACTIVE;
            $form .= $footer;

            return $form;
        }

        //present form now that no RMA option errors exist
        $email = '';
        //retrieve entered email address if exists
        if (isset($validSignIn['_email_address'])) {
            $email = $validSignIn['_email_address'];
        }
        //was an invalid email entered?
        $mailError = '';
        if (isset($validSignIn['_email_error'])) {
            $mailError = '<div class="row"><div class="col-sm-7 text-danger">' . INVALID_EMAIL . '</div></div>';
        }
        //was password not entered?
        $pwError = '';
        if (isset($validSignIn['pw_error'])) {
            $pwError = '<div class="row"><div class="col-sm-7 text-danger">' . PW_ERROR . '</div></div>';
        }
        //were credentials not found?
        $formError = '';
        if (isset($validSignIn['rma_member_not_found'])) {
            $formError = '<div class="row"><div class="col-sm-7 text-danger">' . NOT_FOUND . '</div></div>';
        }

        //build form
        $form = $header . '<form action=""  method="post">
            <div class="row">
                <div class="col-sm-12">
                <p>Active members without passwords may register for content by
                signing in without a password.</p>
                </div>
            </div>
            ';
        $form .= ' <div class="row">
                <div class="col-sm-4">Email address</div>
                <div class="col-sm-3"><input type="text" name="_email" value="' . $email . '"/></div>
                </div>' .
                $mailError;
        $form .= '<div class="row">
                <div class="col-sm-4">Password</div>
                <div class="col-sm-3"><input type="password" name="_password" /></div>
                </div>' .
                $pwError;
        $form .= $formError;
        $form .= '
            <div class="row">
                <div class="col-sm-4"><a href="' . home_url('rma-password-lost') . '">Forgot password?</a></div>
            </div>
            <div class="row">
                <div class="col-sm-4"><input type="submit" value="Sign in" /></div>
            </div>
            </form>';

        return $form . $footer;
    }

}

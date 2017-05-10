<?php

namespace Rma\Pages;

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

    public function signinForm() {
        define('USER_DATA_URI_ERROR', 'Not all RMA options set');
        define('INVALID_EMAIL', 'Invalid email');
        define('NOT_FOUND', 'Member credentials not found');
        define('NOT_ACTIVE', 'Member not considered active');
        define('PW_ERROR', 'Password not entered');

        $validSignIn = [];
        if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') === 'POST') {
            $signInData = filter_input_array(INPUT_POST);
            $tools = new \Rma\Tools();
            $validSignIn = $tools->validate_sign_in($signInData);
        }

        $formHeader = '<div class="panel panel-info">
    <div class="panel-heading">Member sign-in</div>
    <div class="panel-body">';
        $formDanger = '<div class="row"><div class="col-md-7 text-danger">';
        $formFooter = str_repeat('</div>', 4);
        
        $form = $formHeader;

        //show error if required options not set
        if (empty(get_option('rma_user_data_uri')) || empty(get_option('rma_status_field_name')) || empty(get_option('rma_status_field_value'))) {

            $form .= $formDanger;
            $form .= USER_DATA_URI_ERROR;
            $form .= $formFooter;

            return $form;
        }
        
        //if validated member show link to content
        if (isset($validSignIn['validated'])) {
            $pageId = url_to_postid($_SESSION['memberContentURI']);
            $title = get_the_title($pageId);
            $form .= '<div class="row"><div class="col-md-7"> '
                    . '<button><a href="' . $_SESSION['memberContentURI'] . '">Go to ' . $title . '</a></button>';
            $form .= $formFooter;
            
            return $form;
        }
        
        //if member exists without password, allow to register
        if (isset($validSignIn['register']) && $validSignIn['register']) {
            $registerPath = get_permalink(get_page_by_path('member-register'));
            $form .= '<div class="row"><div class="col-md-10"> '
                    . '<button><a href="' . $registerPath . '">Click here to register for content access</a></button>';
            $form .= $formFooter;
            
            return $form;
        }
        
        //if member not active
        if (isset($validSignIn['active']) && !$validSignIn['active']) {
            $form .= $formDanger;
            $form .= NOT_ACTIVE;
            $form .= $formFooter;

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
            $mailError = '</div><div class="row"><div class="col-md-7 text-danger">' . INVALID_EMAIL . '</div>';
        }
        $pwError = '';
        if (isset($validSignIn['pw_error'])) {
            $pwError = '</div><div class="row"><div class="col-md-7 text-danger">' . PW_ERROR . '</div>';
        }
        //were credentials not found?
        $formError = '';
        if (isset($validSignIn['rma_member_form_error'])) {
            $formError = '<br><div class="row"><div class="col-md-7 text-danger">' . NOT_FOUND . '</div></div>';
        }

        $form = $formHeader . '<form action=""  method="post">
            <input  type="hidden" name="action" value="member_sign_in">
            <div class="row">
                <div class="col-md-4">Email address</div>
            ';
        $form .= '<div class="col-md-3"><input type="text" name="_email" value="' . $email . '"/></div>' .
                $mailError;
        $form .= '</div>
            <br />
            <div class="row">
                <div class="col-md-4">Password</div>
                <div class="col-md-3"><input type="password" name="_password" /></div>' .
            $pwError;
        $form .= '</div>
            <br>
            <div class="row">
                <div class="col-md-4"><input type="submit" value="Sign in" /></div>
            </div>
            </form>';
        $form .= $formError . '</div></div>';

        return $form;
    }
}

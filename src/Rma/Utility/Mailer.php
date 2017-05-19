<?php

namespace Rma\Utility;

/**
 * Description of Mailer
 *
 * @author George
 */
class Mailer
{
    private $headers;
    
    public function __construct() {
        $this->headers[] = (WP_DEBUG) ? 'From: Ad Min <admin@bogus.info>' : null;
        $this->headers[] = 'Content-Type: text/html; charset=UTF-8';
    }

    public function registrantEmail($email, $password) {
        $to = $email;
        $subject = "Your registration";
        $body = "<p>Thank you for registering. You can view member content by "
                . "signing in with your email address and the password " . $password . "</p> ";

        return wp_mail($to, $subject, $body, $this->headers);
    }
    
    public function forgotPasswordEmail($email) {
        $to = $email;
        $subject = "Password reset";
        $body = '<p>A request has been made to reset your password. If you did not '
                . 'make this request you may ignore this email</p> '
                . '<p>To reset your password click on '
                . '<a href="' . home_url() . '/rma-password-reset?rp=' . $_SESSION['reset']['time'] . '">this link</a>';

        return wp_mail($to, $subject, $body, $this->headers);
    }

}

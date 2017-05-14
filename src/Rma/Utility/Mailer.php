<?php

namespace Rma\Utility;

/**
 * Description of Mailer
 *
 * @author George
 */
class Mailer
{

    public function registrantEmail($email, $password) {
        $to = $email;
        $subject = "Your registration";
        $body = "<p>Thank you for registering. You can view member content by "
                . "signing in with your email address and the password " . $password . "</p> ";
        $headers[] = 'Content-Type: text/html; charset=UTF-8';

        if (WP_DEBUG) {
            $headers[] = 'From: Ad Min <admin@bogus.info>';
        }

        return wp_mail($to, $subject, $body, $headers);
    }

}

<?php

namespace Rma\Utility;

use Rma\Utility\Mailer;

/**
 * Description of RESTData
 *
 * @author George
 */
class RESTData
{
    private $headers;
    private $uri;

    public function __construct()
    {
        $this->uri = get_option('rmaDataURI');
        $key = get_option('rmaApiKey');
        $keyName = get_option('rmaKeyFieldName');
        $user = get_option('rmaBasicUsername');
        $password = get_option('rmaBasicPassword');
        $type = get_option('rmaAuthType');

        switch ($type) {
            case 'API key':
                $this->headers = [$keyName => $key];
                break;
            case 'HTTP Basic':
                $this->headers = ['Authorization' => 'Basic ' . base64_encode($username . ':' . $password)];
            default:
                break;
        }
    }

    /**
     * Get RESTful API's response
     * 
     * @param type $email
     * @return Json Response
     */
    public function getSingleMemberData($email)
    {
        $data = [];

        if ('on' == get_option('rmaOnlyGet')) {
            //do local get
            global $wpdb;
            $stmt = $wpdb->prepare(
                'SELECT * FROM ' . $wpdb->prefix . 'member WHERE email = %s', $email
            );
            if (is_wp_error($data = $wpdb->get_row($stmt, ARRAY_A))) {
                return ['data_error' => true];
            }
            if (null !== $data) {
                //locally stored members are by definition active
                $statusField = get_option('rmaStatusName');
                $data[$statusField] = true;
                return ['member' => $data];
            }
        } else {
            //do remote GET
            //create complete $_GET uri based on authentication type
            $getURI = $this->uri . '/' . $email;
            //could be null, code = 404
            if (is_wp_error($data = wp_remote_get($getURI, [
                'headers' => $this->headers,
                'timeout' => 10,
                ]))) {
                return ['data_error' => true];
            }
            if ('200' == $data['response']['code']) {
                return ['member' => json_decode($data['body'], TRUE)[0]];
            }
        }

        return $data;
    }

    /**
     * Send register email
     */
    public function sendRegistrationData($email)
    {
        $pw = $this->createPasswordHash();
        $sent = $this->setMemberPassword($email, $pw['hash']);
        if (null !== $sent && '200' == $sent['response']['code']) {
            $mailer = new Mailer();
            $wasSent = $mailer->registrantEmail($email, $pw['password']);
        }

        return $wasSent;
    }

    /**
     * Set member's password
     */
    public function setMemberPassword($email, $hash)
    {
        $args = [
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => $this->headers,
            'timeout' => 10,
            'body' =>
            ['email' => $email, 'hash' => $hash],
        ];
        if ('on' == get_option('rmaOnlyGet')) {
            //do local save
            global $wpdb;
            $stmt = $wpdb->prepare(
                'UPDATE ' . $wpdb->prefix . 'member SET password = %s '
                . 'WHERE email = %s', $hash, $email
            );

            return $wpdb->query($stmt);
        } else {
            //do remote POST
            $uri = get_option('rmaSetPasswordURI');
//            var_dump($uri);die;
            return wp_remote_post($uri, $args);
        }
    }

    /**
     * Get all members
     *
     * @return array All members
     */
    public function getAllMembers()
    {
        $data = [];
        $uri = get_option('rmaAllMembersURI');
        if (is_wp_error($data = wp_remote_get($uri, ['headers' => $this->headers]))) {
            return ['data_error' => true];
        }
        if ('200' == $data['response']['code']) {
            return json_decode($data['body'], true);
        }
    }

    /**
     * Generate member password & hash
     *
     * @return string
     */
    private function createPasswordHash()
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $password = substr(str_shuffle($chars), 0, 8);
        $hash = password_hash($password, PASSWORD_BCRYPT);

        return [
            'hash' => $hash,
            'password' => $password,
        ];
    }
}

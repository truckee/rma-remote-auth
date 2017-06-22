<?php

namespace Rma\Validation;

/**
 * Description of SettingValidation
 *
 * @author George
 */
class SettingValidation
{

    //validate User data URI
    static function validate_rmaDataURI() {
        $uri = filter_input(INPUT_POST, 'rmaDataURI');
        $uriFiltered = filter_var($uri, FILTER_VALIDATE_URL);
        if ($uri !== $uriFiltered) {
            $invalid = $uri . ' is an invalid User data URI';
            $empty = 'User data URI may not be empty';
            add_settings_error('rmaDataURI', 'rma_uri', (empty($uri) ? $empty : $invalid));
        }

        return $uri;
    }

    //validate Set user password URI
    static function validate_rmaSetPasswordURI() {
        $uri = filter_input(INPUT_POST, 'rmaSetPasswordURI');
        $uriFiltered = filter_var($uri, FILTER_VALIDATE_URL);
        if ($uri !== $uriFiltered) {
            $invalid = $uri . ' is an invalid URI';
            $empty = 'Set password URI may not be empty';
            add_settings_error('rmaSetPasswordURI', 'rma_uri', (empty($uri) ? $empty : $invalid));
        }

        return $uri;
    }

    //validate Status field name
    static function validate_rmaStatusName() {
        $fieldName = filter_input(INPUT_POST, 'rmaStatusName');
        if (empty($fieldName)) {
            add_settings_error('rmaStatusName', 'rma_key', 'Status field name may not be empty');
        }

        return $fieldName;
    }

    //validate Active status value
    static function validate_rmaStatusValue() {
        $value = filter_input(INPUT_POST, 'rmaStatusValue');
        if (empty($value)) {
            add_settings_error('rmaStatusValue', 'rma_key', 'Active status value may not be empty');
        }

        return $value;
    }

    //validate API key
    static function validate_rmaApiKey() {
        $key = filter_input(INPUT_POST, 'rmaApiKey');
        $type = filter_input(INPUT_POST, 'rmaAuthType');
        if (0 === strlen($key) && 'API key' === $type) {
            add_settings_error('rmaApiKey', 'rma_key', 'API key may not be empty');
        }

        return $key;
    }

    //validate API key field name
    static function validate_rmaKeyFieldName() {
        $name = filter_input(INPUT_POST, 'rmaKeyFieldName');
        $type = filter_input(INPUT_POST, 'rmaAuthType');
        if (0 === strlen($name) && 'API key' === $type) {
            add_settings_error('rmaKeyFieldName', 'rma_key', 'API key field name may not be empty');
        }

        return $name;
    }

    //validate Username
    static function validate_rmaBasicUsername() {
        $name = filter_input(INPUT_POST, 'rmaBasicUsername');
        $type = filter_input(INPUT_POST, 'rmaAuthType');
        if (0 === strlen($name) && 'HTTP Basic'  === $type) {
            add_settings_error('rmaBasicUsername', 'rma_name', 'Username may not be empty');
        }

        return $name;
    }

    //validate Password
    static function validate_rmaBasicPassword() {
        $password = filter_input(INPUT_POST, 'rmaBasicPassword');
        $type = filter_input(INPUT_POST, 'rmaAuthType');
        if (0 === strlen($password) && 'HTTP Basic' === $type) {
            add_settings_error('rmaBasicPassword', 'rma_name', 'Password may not be empty');
        }

        return $password;
    }

    //validate remote members uri
    static function validate_rmaAllMembersURI() {
        $uri = filter_input(INPUT_POST, 'rmaAllMembersURI');
        $type = filter_input(INPUT_POST, 'rmaOnlyGet');
        if (0 === strlen($uri) && 'on' === $type) {
            add_settings_error('rmaAllMembersURI', 'rma_name', 'Remote members URI may not be empty');
        }

        return $uri;
    }

}

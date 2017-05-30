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
    static function validate_rma_member_data_uri() {
        $uri = filter_input(INPUT_POST, 'rma_member_data_uri');
        $uriFiltered = filter_var($uri, FILTER_VALIDATE_URL);
        if ($uri !== $uriFiltered) {
            $invalid = $uri . ' is an invalid User data URI';
            $empty = 'User data URI may not be empty';
            add_settings_error('rma_member_data_uri', 'rma_uri', (empty($uri) ? $empty : $invalid));
        }

        return $uri;
    }

    //validate Set user password URI
    static function validate_rma_set_password_uri() {
        $uri = filter_input(INPUT_POST, 'rma_set_password_uri');
        $uriFiltered = filter_var($uri, FILTER_VALIDATE_URL);
        if ($uri !== $uriFiltered) {
            $invalid = $uri . ' is an invalid URI';
            $empty = 'Set password URI may not be empty';
            add_settings_error('rma_set_password_uri', 'rma_uri', (empty($uri) ? $empty : $invalid));
        }

        return $uri;
    }

    //validate Sstatus field name
    static function validate_rma_status_field_name() {
        $fieldName = filter_input(INPUT_POST, 'rma_status_field_name');
        if (empty($fieldName)) {
            add_settings_error('rma_status_field_name', 'rma_key', 'Field name may not be empty');
        }

        return $fieldName;
    }

    //validate Active status value
    static function validate_rma_status_field_value() {
        $value = filter_input(INPUT_POST, 'rma_status_field_value');
        if (empty($value)) {
            add_settings_error('rma_status_field_value', 'rma_key', 'Active status value may not be empty');
        }

        return $value;
    }

    //validate API key
    static function validate_rma_auth_type_api_key() {
        $key = filter_input(INPUT_POST, 'rma_auth_type_api_key');
        if (0 === strlen($key) && 'API key' === $type) {
            add_settings_error('rma_auth_type_api_key', 'rma_key', 'API key may not be empty');
        }

        return $key;
    }

    //validate API key field name
    static function validate_rma_auth_type_api_key_field_name() {
        $name = filter_input(INPUT_POST, 'rma_auth_type_api_key_field_name');
        $type = filter_input(INPUT_POST, 'rma_auth_type');
        if (0 === strlen($name) && 'API key' === $type) {
            add_settings_error('rma_auth_type_api_key_field_name', 'rma_key', 'API key field name may not be empty');
        }

        return $name;
    }

    //validate Username
    static function validate_rma_auth_type_basic_username() {
        $name = filter_input(INPUT_POST, 'rma_auth_type_basic_username');
        $type = filter_input(INPUT_POST, 'rma_auth_type');
        if (0 === strlen($name) && 'HTTP Basic'  === $type) {
            add_settings_error('rma_auth_type_basic_username', 'rma_name', 'Username may not be empty');
        }

        return $name;
    }

    //validate Password
    static function validate_rma_auth_type_basic_password() {
        $password = filter_input(INPUT_POST, 'rma_auth_type_basic_password');
        $type = filter_input(INPUT_POST, 'rma_auth_type');
        if (0 === strlen($password) && 'HTTP Basic' === $type) {
            add_settings_error('rma_auth_type_basic_password', 'rma_name', 'Password may not be empty');
        }

        return $password;
    }

    //validate remote members uri
    static function validate_rma_get_remote_members() {
        $uri = filter_input(INPUT_POST, 'rma_get_remote_members');
        $type = filter_input(INPUT_POST, 'rma_member_get_only');
        if (0 === strlen($uri) && 'on' === $type) {
            add_settings_error('rma_get_remote_members', 'rma_name', 'Remote members URI may not be empty');
        }

        return $uri;
    }

}

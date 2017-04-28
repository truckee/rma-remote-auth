<?php

namespace Rma;

/**
 * Description of Fields
 *
 * @author George
 */
class Fields
{

    static function fieldHtml($field) {
        switch ($field['fieldName']) {
            case 'rma_user_data_uri':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' size='60' id='$name' name='$name' value='$value' />";
                break;
            case 'rma_status_field_name':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rma_status_field_value':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rma_auth_type':
                $name = $field['fieldName'];
                $value = get_option($name);
                $fieldNone = ($value == 'None') ? "checked='checked' " : '';
                $fieldAPI = ($value == 'API key') ? "checked='checked' " : '';
                $fieldBasic = ($value == 'HTTP Basic') ? "checked='checked' " : '';
                echo "<input type='radio' id='$name' name='$name' value='API key' " .
                $fieldAPI .
                " />API key<br>";
                echo "<input type='radio' id='$name' name='$name' value='HTTP Basic'" .
                $fieldBasic . ' />HTTP Basic<br>';
                echo "<input type='radio' id='$name' name='$name' value='None'" .
                $fieldNone . ' />None';
                break;
            case 'rma_auth_type_api_key':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' size='40' name='$name' value='$value' />";
                break;
            case 'rma_auth_type_api_key_field_name':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rma_auth_type_basic_username':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rma_auth_type_basic_password':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            default:
                break;
        }
    }

    //validate User data URI
    static function validate_rma_user_data_uri() {
        $uri = filter_input(INPUT_POST, 'rma_user_data_uri');
        $uriFiltered = filter_var($uri, FILTER_VALIDATE_URL);
        if ($uri !== $uriFiltered) {
            $invalid = $uri . ' is an invalid User data URI';
            $empty = 'User data URI may not be empty';
            add_settings_error('rma_user_data_uri', 'rma_uri', (empty($uri) ? $empty : $invalid));
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
        if (empty($key) && 'API key' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_api_key', 'rma_key', 'API key may not be empty');
        }

        return $key;
    }

    //validate API key field name
    static function validate_rma_auth_type_api_key_field_name() {
        $key = filter_input(INPUT_POST, 'rma_auth_type_api_key_field_name');
        if (empty($key) && 'API key' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_api_key_field_name', 'rma_key', 'API key field name may not be empty');
        }

        return $key;
    }

    //validate Username
    static function validate_rma_auth_type_basic_username() {
        $name = filter_input(INPUT_POST, 'rma_auth_type_basic_username');
        if (empty($name) && 'HTTP Basic' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_basic_username', 'rma_name', 'Username may not be empty');
        }

        return $name;
    }

    //validate Password
    static function validate_rma_auth_type_basic_password() {
        $password = filter_input(INPUT_POST, 'rma_auth_type_basic_password');
        if (empty($password) && 'HTTP Basic' === filter_input(INPUT_POST, 'rma_auth_type')) {
            add_settings_error('rma_auth_type_basic_password', 'rma_name', 'Password may not be empty');
        }

        return $password;
    }
}

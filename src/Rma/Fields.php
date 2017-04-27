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

}

<?php

namespace Rma\Fields;

/**
 * Description of SettingFields
 *
 * @author George
 */
class SettingFields
{

    static function fieldHtml($field) {
        switch ($field['fieldName']) {
            case 'rmaDataURI':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' size='60' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaSetPasswordURI':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' size='60' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaStatusName':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaStatusValue':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaAuthType':
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
            case 'rmaApiKey':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' size='40' name='$name' value='$value' />";
                break;
            case 'rmaKeyFieldName':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaBasicUsername':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaBasicPassword':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rmaOnlyGet':
                $name = $field['fieldName'];
                $value = get_option($name);
                $checked = ($value == 'on') ? ' checked="checked"' : null;
                echo "<input type='checkbox' id='$name' name='$name' $checked />";
                break;
            case 'rmaAllMembersURI':
                $name = $field['fieldName'];
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            default:
                break;
        }
    }
}

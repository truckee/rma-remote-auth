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
            case 'rma_base_url':
                $name = 'rma_base_url';
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            case 'rma_get_hash':
                $name = 'rma_get_hash';
                $value = get_option($name);
                echo "<input type='text' id='$name' name='$name' value='$value' />";
                break;
            default:
                break;
        }
    }

}

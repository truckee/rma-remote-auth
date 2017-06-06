<?php
/**
  Plugin Name: Remote Member Authorization
  Plugin URI:  https://tbd
  Description: Remote member authorization
  Version:     1.0.1-alpha
  Author:      Truckee Solutions
  Author URI:  https://tbd
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: wporg
  Domain Path: /languages

 * Credits: Nico Amarilla: Plugin framework - https://www.smashingmagazine.com/2015/05/how-to-use-autoloading-and-a-plugin-container-in-wordpress-plugins/
 *
  Remote Member Authorization is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  any later version.

  Remote Member Authorization is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with Remote Member Authorization. If not, see {URI to Plugin License}.
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

use Rma\Pages\PageLoader;
use Rma\Pages\SettingsPage;
use Rma\Plugin;
use Rma\Templates\PageTemplater;
use Rma\Utility\Deactivation;
use Rma\Utility\MemberTable;

const USER_DATA_URI_ERROR = 'Not all RMA options set';
const INVALID_EMAIL = 'Invalid email';
const NOT_FOUND = 'Member credentials not found';
const NOT_ACTIVE = 'Member not considered active';
const PW_ERROR = 'Password not entered';
const API_ERROR = 'API entries missing';
const BASIC_ERROR = 'HTTP Basic entries missing';
const DATA_ERROR = 'Member data not available';
const RP_GET_ERROR = 'Reset validation error. Try again.';
const PW_MATCH_ERROR = 'Passwords do not match';

spl_autoload_register('rma_autoloader');

function rma_autoloader($class_name)
{
    if (false !== strpos($class_name, 'Rma')) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        require_once $classes_dir . $class_file;
    }
}
add_action('init', 'rmaInit'); // Hook initialization function

function rmaInit()
{
    $plugin = new Plugin(); // Create container
    $plugin['path'] = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR;
    $plugin['url'] = plugin_dir_url(__FILE__);
    $plugin['version'] = '0.1.0';
    $plugin['properties'] = [
        'parent_slug' => 'options-general.php',
        'page_title' => '<h2>Remote Member Authentication</h2>',
        'menu_title' => 'Remote Member Authentication',
        'capability' => 'manage_options',
        'menu_slug' => 'rma-settings',
        'option_group' => 'rma_option_group',
        'section_heading' => '',
        'submit_label' => 'Save stuff',
        'options' => [
            ['fieldName' => 'rma_auth_type',
                'label' => 'Authentication type'],
            ['fieldName' => 'rma_auth_type_api_key',
                'label' => 'API key'],
            ['fieldName' => 'rma_auth_type_api_key_field_name',
                'label' => 'API key field name'],
            ['fieldName' => 'rma_auth_type_basic_username',
                'label' => 'Username'],
            ['fieldName' => 'rma_auth_type_basic_password',
                'label' => 'Password'],
            ['fieldName' => 'rma_member_data_uri',
                'label' => 'Get member data URI',],
            ['fieldName' => 'rma_member_get_only',
                'label' => 'Get members data only',],
            ['fieldName' => 'rma_set_password_uri',
                'label' => 'Set member password URI',],
            ['fieldName' => 'rma_get_remote_members',
                'label' => 'Get all members data URI',],
            ['fieldName' => 'rma_status_field_name',
                'label' => 'Status field name'],
            ['fieldName' => 'rma_status_field_value',
                'label' => 'Active status value'],
        ],
    ];
    $plugin['settings_page'] = function ( $plugin ) {
        static $object;

        if (null !== $object) {
            return $object;
        }
        return new SettingsPage($plugin['properties']);
    };
    // Information needed for creating the plugin's pages
    global $pageDefinitions;
    $pageDefinitions = array(
        'rma-sign-in' => array(
            'title' => __('Member sign in', 'rma-member-auth'),
            'content' => '[rma_sign_in]',
            'class' => 'Rma\Pages\SignIn',
            'function' => 'createSignInForm',
        ),
        'rma-register' => array(
            'title' => __('Register', 'rma-member-auth'),
            'content' => '[rma_register_form]',
            'class' => 'Rma\Pages\Register',
            'function' => 'createRegisterForm',
        ),
        'rma-password-lost' => array(
            'title' => __('Forgot Your Password?', 'rma-member-auth'),
            'content' => '[rma-password-lost-form]',
            'class' => 'Rma\Pages\ForgotPassword',
            'function' => 'createForgotPasswordForm',
        ),
        'rma-password-reset' => array(
            'title' => __('Create a New Password', 'rma-member-auth'),
            'content' => '[rma-password-reset-form]',
            'class' => 'Rma\Pages\ResetPassword',
            'function' => 'createResetPasswordForm',
        )
    );

    //template MUST be in Templates folder; use ./ for template path
    global $rmaTemplates;
    $rmaTemplates = [
        './member-content-template.php' => 'Restricted Member Content',
        './rma-registration-template.php' => 'RMA Registration',
    ];
    //initialization functions
    //add tempate(s)
    $templater = new PageTemplater($rmaTemplates);
    //add page(s) & shortcodes
    $pages = new PageLoader();
    $pages->pageCreator($pageDefinitions);
    $pages->shortcodeGenerator($pageDefinitions);
    //add scripts
    add_action('admin_enqueue_scripts', 'rmaQueueScripts');
    //add template to Register page
    $id = get_page_by_title('Register')->ID;
    $meta_key = '_wp_page_template';
    $meta_value = './rma-registration-template.php';
    $pages->updatePostMeta($id, $meta_key, $meta_value);

    $plugin->run();
}
register_activation_hook(__FILE__, ['Rma\Utility\MemberTable', 'memberTableHook']);

add_action('updateMemberTableEvent', ['Rma\Utility\MemberTable', 'loadMemberTable']);

function rmaQueueScripts($hook)
{
    //rmaQueueScripts
    if ($hook != 'settings_page_rma-settings') {
        return;
    }
    wp_enqueue_script('rma_js_script', plugins_url('/js/rma_settings.js', __FILE__), ['jquery']);
}
register_deactivation_hook(__FILE__, 'rmaDeactivate');

function rmaDeactivate()
{
    global $pageDefinitions;
    $deactivate = new Deactivation();
    $deactivate->removePages($pageDefinitions);
    $deactivate->removeShortcodes($pageDefinitions);
    $tables = [
        'member',
    ];
    $deactivate->dropTable($tables);
    $deactivate->stopMemberTableUpdate();
}

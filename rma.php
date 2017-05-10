<?php

/**
  Plugin Name: Remote Member Authorization
  Plugin URI:  https://tbd
  Description: Remote member authorization
  Version:     0.1
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

use Rma\Plugin;
use Rma\SettingsPage;

spl_autoload_register('rma_autoloader');

function rma_autoloader($class_name) {
    if (false !== strpos($class_name, 'Rma')) {
        $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
        $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        require_once $classes_dir . $class_file;
    }
}

add_action('init', 'rma_init'); // Hook initialization function
//add_action('plugins_loaded', 'rma_init'); // Hook initialization function

function rma_init() {
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
            ['fieldName' => 'rma_user_data_uri',
                'label' => 'User data URI',],
            ['fieldName' => 'rma_status_field_name',
                'label' => 'Status field name'],
            ['fieldName' => 'rma_status_field_value',
                'label' => 'Active status value'],
            ['fieldName' => 'rma_auth_type_api_key',
                'label' => 'API key'],
            ['fieldName' => 'rma_auth_type_api_key_field_name',
                'label' => 'API key field name'],
            ['fieldName' => 'rma_auth_type_basic_username',
                'label' => 'Username'],
            ['fieldName' => 'rma_auth_type_basic_password',
                'label' => 'Password'],
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
    $page_definitions = array(
        'member-sign-in' => array(
            'title' => __('Member sign in', 'rma-member-auth'),
            'content' => '[member_sign_in]',
            'class' => 'Rma\Pages\SignIn',
            'function' => 'createSignInForm'
        ),
//        'member-account' => array(
//            'title' => __('Your Account', 'personalize-login'),
//            'content' => '[account-info]'
//        ),
        'member-register' => array(
            'title' => __('Register', 'rma-member-auth'),
            'content' => '[custom_register_form]', 
            'class' => 'Rma\Pages\Pages',
            'function' => 'createRegisterForm'
        ),
//        'member-password-lost' => array(
//            'title' => __('Forgot Your Password?', 'personalize-login'),
//            'content' => '[custom-password-lost-form]'
//        ),
//        'member-password-reset' => array(
//            'title' => __('Pick a New Password', 'personalize-login'),
//            'content' => '[custom-password-reset-form]'
//        )
    );


    $templates = [
        './Templates/member-content.php' => 'Restricted Member Content',
    ];
    //initialization functions
    $templater = new Rma\PageTemplater($templates);
    $pages = new Rma\Pages\PageLoader();
    $pages->pageCreator($page_definitions);
    $pages->shortcodeGenerator($page_definitions);
    add_action('admin_enqueue_scripts', 'rmaQueueScripts');
    add_action('plugins_loaded', array('Rma\PageTemplater', 'get_instance'));

    $plugin->run();
}

function rmaQueueScripts($hook) {
    //rmaQueueScripts
    if ($hook != 'settings_page_rma-settings') {
        return;
    }
    wp_enqueue_script('rma_js_script', plugins_url('/js/rma_settings.js', __FILE__), ['jquery']);
}

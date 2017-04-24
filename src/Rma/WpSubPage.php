<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Rma;

/**
 * Description of WpSubPage
 *
 * @author George
 */
abstract class WpSubPage
{

    protected $properties;

    public function __construct($properties) {
        $this->properties = $properties;
    }

    public function run() {
        add_action('admin_menu', array($this, 'add_menu_and_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_menu_and_page() {

        add_submenu_page(
                $this->properties['parent_slug'], $this->properties['page_title'], $this->properties['menu_title'], $this->properties['capability'], $this->properties['menu_slug'], array($this, 'render_settings_page')
        );
    }

    public function register_settings() {
        $group = $this->properties['option_group'];
        $options = $this->properties['options'];
        $page = $this->properties['menu_slug'];
        add_settings_section($page . '_main', '', function() {
            echo $this->properties['section_heading'];
        }, $page);
        foreach ($options as $option) {
            $fieldName = $option['fieldName'];
            if (method_exists('Rma\Tools', 'validate_' . $fieldName)) {
                register_setting($group, $fieldName, ['Rma\Tools', 'validate_' . $fieldName]);
            } else {
                register_setting($group, $fieldName);
            }
            add_settings_field($fieldName, $option['label'], ['Rma\Fields', 'fieldHtml'], $page, $page . '_main', ['fieldName' => $fieldName]);
        }
    }

    public function render_settings_page() {
        
    }

}

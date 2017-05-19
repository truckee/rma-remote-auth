<?php

namespace Rma\Utility;

/**
 * delete pages: member-sign-in, member-register
 * delete templates: rma-registration-template.php, member-content-template.php
 * delete shortcodes: from $pageDefinitions; remove_shortcode()
 * delete options
 *
 * @author George
 */
class Deactivation
{

    public function removePages($pageDefinitions) {
        $keys = array_keys($pageDefinitions);
        foreach ($keys as $slug) {
            $page = get_page_by_path($slug);
            $pageId = $page->ID;
            wp_delete_post($pageId);
        }
    }

    public function removeTemplates($rmaTemplates) {
        foreach ($templates as $file) {
            wp_delete_file($file);
        }
    }

    public function removeShortcodes($pageDefinitions) {
        foreach ($pageDefinitions as $slug => $page) {
            remove_shortcode($page['content']);
        }
    }

}

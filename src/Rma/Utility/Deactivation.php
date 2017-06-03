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
            $deleted[] = wp_delete_post($pageId);
        }

        return $deleted;
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

    public function dropTable($tables)
    {
        global $wpdb;
        foreach ($tables as $tableName) {
            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}$tableName");
        }
    }

    public function stopMemberTableUpdate()
    {
        wp_clear_scheduled_hook('updateMemberTableEvent');
    }

}

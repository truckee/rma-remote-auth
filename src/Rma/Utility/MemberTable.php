<?php
//src\Rma\Utility\MemberTable.php

namespace Rma\Utility;

/**
 * MemberTable
 *
 */
class MemberTable
{

    public function createMemberTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'member';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (id INTEGER NOT NULL AUTO_INCREMENT, email VARCHAR(255) NOT NULL, "
                . "password VARCHAR(255) DEFAULT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            
            return dbDelta($sql);
        }
    }

    public function dropMemberTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'member';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
            $sql = "DROP TABLE $table_name";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            return dbDelta($sql);
        }

    }
}

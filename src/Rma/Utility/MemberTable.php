<?php
//src\Rma\Utility\MemberTable.php

namespace Rma\Utility;

use Rma\Utility\RESTData;

/**
 * MemberTable
 *
 */
class MemberTable
{

    /**
     *
     * @global object $wpdb
     * @return array
     */
    public function createMemberTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'member';
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL,
  UNIQUE KEY `email` (`email`)) ";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            return dbDelta($sql);
        }
    }

    /**
     * Updates member table if table is used
     *
     * @global object $wpdb
     * @return int
     */
    public function loadMemberTable()
    {
        global $wpdb;
        $rest = new RESTData();
        $members = $rest->getAllMembers();
        $table_name = $wpdb->prefix . 'member';
        $n = 0;
        foreach ($members as $member) {
            //if member email does not match, insert record
            //if member email matches, enabled does not, update enabled
            $match = $wpdb->get_row("SELECT * FROM $table_name WHERE email = '$member->email'", OBJECT);
            if (null === $match) {
                $n += $wpdb->query("INSERT INTO $table_name (email, enabled) VALUES ('$member->email', '$member->enabled')");
                continue;
            }
            if ($match->enabled !== $member->enabled) {
                //update record
                $n += $wpdb->query("UPDATE $table_name set enabled = '$member->enabled' WHERE email = '$member->email'");
            }
        }

        return $n;
    }
}

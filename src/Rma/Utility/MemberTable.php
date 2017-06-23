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
    protected static $instance = NULL;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
//            self::load_files();
        }
        return self::$instance;
    }

    /**
     *
     * @global object $wpdb
     * @return array
     */
    public static function createMemberTable()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'member';
        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            $sql = "CREATE TABLE $tableName (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  UNIQUE KEY `email` (`email`)) ";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

            dbDelta($sql);
        }
    }

    public static function memberTableHook()
    {
        if ('on' === get_option('rmaOnlyGet')) {
            self::createMemberTable();
            self::loadMemberTable();
        }
        if (!wp_next_scheduled('updateMemberTableEvent')) {
            wp_schedule_event(time(), 'daily', 'updateMemberTableEvent');
        }
    }

    /**
     * Updates member table if table is used
     *
     * @global object $wpdb
     * @return int
     */
    public static function loadMemberTable()
    {
        global $wpdb;
        $tableName = $wpdb->prefix . 'member';
        $statusFieldName = get_option('rmaStatusName');
        $statusValue = get_option('rmaStatusValue');
        $rest = new RESTData();
        $members = $rest->getAllMembers();
        $n = 0;
        foreach ($members as $member) {
            //if member email does not match existing record & member is active, insert record
            //if member email matches but is not active, delete record
            $email = $member['email'];
            $match = $wpdb->get_row("SELECT * FROM $tableName WHERE email = '$email'");
            if (null === $match && $member[$statusFieldName] == $statusValue) {
                $n += $wpdb->query("INSERT INTO $tableName (email) VALUES ('$email')");
                continue;
            }
            if ($match && $member[$statusFieldName] != $statusValue) {
                //delete record
                $n += $wpdb->query("DELETE FROM $tableName WHERE email = '$email'");
            }
        }
    }
}

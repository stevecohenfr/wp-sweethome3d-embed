<?php

/**
 * Database manager
 *
 * @link       https://thevagabonds.fr
 * @since      1.0.0
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/includes
 */

/**
 * Database manager.
 *
 * This class defines all code necessary to manage the database.
 *
 * @since      1.0.0
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/includes
 * @author     Steve Cohen <cohensteve@hotmail.fr>
 */
class Sweethome3dembed_Database {

    private static $_INSTANCE = null;
    public $db_version = '1.0';

    private  function __construct()
    {
    }

    public static function getInstance() {
        if (self::$_INSTANCE === null) {
            self::$_INSTANCE = new Sweethome3dembed_Database();
        }
        return self::$_INSTANCE;
    }

    function install() {

        $installed_ver = get_option("sh3dembed_db_version");
        if ($installed_ver != $this->db_version) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'sh3dembed';

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                name varchar(255) NOT NULL,
                fileid bigint(20) NOT NULL,
                url varchar(255) NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            add_option('sh3dembed_db_version', $this->db_version);
        }
    }

    function add($fileId) {
        global $wpdb;

        $file = get_post($fileId);
        $file->post_title;

        $table_name = $wpdb->prefix . 'sh3dembed';

        $wpdb->insert(
            $table_name,
            array(
                'date'      => current_time('mysql'),
                'name'      => $file->post_title,
                'fileid'    => $fileId,
                'url'       => $file->guid
            )
        );
    }

    function getById($id) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . "sh3dembed WHERE id = " . $id;
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        $result = count($result) > 0 ? $result[0] : null;

        return $result;
    }

    function listAll($per_page = 5, $page_number = 1) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . "sh3dembed";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    function delete($id) {
        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix . "sh3dembed",
            [ 'id' => $id ],
            [ '%d' ]
        );
    }

    function count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . "sh3dembed";

        return $wpdb->get_var( $sql );
    }

}

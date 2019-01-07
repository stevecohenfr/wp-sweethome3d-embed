<?php

/**
 * Fired during plugin activation
 *
 * @link       https://thevagabonds.fr
 * @since      1.0.0
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/includes
 * @author     Steve Cohen <cohensteve@hotmail.fr>
 */
class Sweethome3dembed_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        require_once plugin_dir_path( dirname( __FILE__ ) ). 'includes/class-sweethome3dembed-database.php';
        $db = Sweethome3dembed_Database::getInstance();

        $db->install();
	}

}

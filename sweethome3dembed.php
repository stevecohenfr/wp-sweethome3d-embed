<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://thevagabonds.fr
 * @since             1.0.0
 * @package           EmbedSweethome3d
 *
 * @wordpress-plugin
 * Plugin Name:       Embed-SweetHome3D
 * Plugin URI:        https://thevagabonds.fr/Embed-SweetHome3D
 * Description:       This extension allows you to embed your SweetHome3D house in your wordpress articles/pages.
 * Version:           1.0.0
 * Author:            Steve Cohen
 * Author URI:        https://stevecohen.fr
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       embed-sweethome3d
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EMBED-SWEETHOME3D_VERSION', '1.0.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sweethome3dembed-activator.php
 */
function activate_sweethome3dembed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sweethome3dembed-activator.php';
	Sweethome3dembed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sweethome3dembed-deactivator.php
 */
function deactivate_sweethome3dembed() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sweethome3dembed-deactivator.php';
	Sweethome3dembed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sweethome3dembed' );
register_deactivation_hook( __FILE__, 'deactivate_sweethome3dembed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sweethome3dembed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sweethome3dembed() {

	$plugin = new Sweethome3dembed();
	$plugin->run();

}
run_sweethome3dembed();

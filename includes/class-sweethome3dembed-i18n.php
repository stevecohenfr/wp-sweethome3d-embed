<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://thevagabonds.fr
 * @since      1.0.0
 *
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sweethome3dembed
 * @subpackage Sweethome3dembed/includes
 * @author     Steve Cohen <cohensteve@hotmail.fr>
 */
class Sweethome3dembed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sweethome3dembed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

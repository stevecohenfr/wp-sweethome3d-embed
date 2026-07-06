<?php
/**
 * Embed-SweetHome3D
 *
 * @package           EmbedSweetHome3D
 * @author            Steve Cohen
 * @copyright         2019-2026 Steve Cohen
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Embed-SweetHome3D
 * Plugin URI:        https://thevagabonds.fr/Embed-SweetHome3D
 * Description:       Embed your SweetHome3D houses as interactive HTML5/WebGL models in your posts and pages, through a Gutenberg block or the [sh3d] shortcode.
 * Version:           2.0.1
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Author:            Steve Cohen
 * Author URI:        https://stevecohen.fr
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       embed-sweethome3d
 * Domain Path:       /languages
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin constants.
 */
const VERSION    = '2.0.1';
const DB_VERSION = '2.0';

define( __NAMESPACE__ . '\PLUGIN_FILE', __FILE__ );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * PSR-4 style autoloader for the plugin namespace.
 *
 * Maps `EmbedSweetHome3D\Some\Class` to `src/Some/Class.php`.
 */
spl_autoload_register(
	static function ( string $class ): void {
		$prefix = __NAMESPACE__ . '\\';
		if ( ! str_starts_with( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$path     = PLUGIN_DIR . 'src/' . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $path ) ) {
			require $path;
		}
	}
);

// Lifecycle hooks.
register_activation_hook( __FILE__, static fn() => Activator::activate() );
register_deactivation_hook( __FILE__, static fn() => Deactivator::deactivate() );

// Boot the plugin.
add_action(
	'plugins_loaded',
	static function (): void {
		Plugin::instance()->run();
	}
);

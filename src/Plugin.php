<?php
/**
 * Core plugin orchestrator.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D;

use EmbedSweetHome3D\Admin\AdminPage;
use EmbedSweetHome3D\Frontend\AssetManager;
use EmbedSweetHome3D\Frontend\Block;
use EmbedSweetHome3D\Frontend\Shortcode;
use EmbedSweetHome3D\Repository\ModelRepository;

/**
 * Wires every component of the plugin to WordPress hooks.
 */
final class Plugin {

	private static ?Plugin $instance = null;

	private readonly ModelRepository $repository;
	private readonly AssetManager $assets;

	private function __construct() {
		$this->repository = new ModelRepository();
		$this->assets     = new AssetManager();
	}

	/**
	 * Retrieve the shared plugin instance.
	 */
	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function repository(): ModelRepository {
		return $this->repository;
	}

	public function assets(): AssetManager {
		return $this->assets;
	}

	/**
	 * Register every hook the plugin relies on.
	 */
	public function run(): void {
		// Load translations.
		add_action(
			'init',
			static function (): void {
				load_plugin_textdomain(
					'embed-sweethome3d',
					false,
					dirname( plugin_basename( PLUGIN_FILE ) ) . '/languages'
				);
			}
		);

		// Keep the schema up to date (handles upgrades from 1.0.x installs).
		add_action( 'admin_init', array( $this->repository, 'maybe_upgrade' ) );

		// Front-end: shortcode + block, both rendered by the same renderer.
		$shortcode = new Shortcode( $this->repository, $this->assets );
		add_action( 'init', array( $shortcode, 'register' ) );

		$block = new Block( $this->repository, $this->assets );
		add_action( 'init', array( $block, 'register' ) );

		// Front-end assets are registered up front and enqueued on demand.
		add_action( 'wp_enqueue_scripts', array( $this->assets, 'register' ) );

		// Admin screen.
		if ( is_admin() ) {
			$admin = new AdminPage( $this->repository );
			$admin->register();
		}
	}
}

<?php
/**
 * Registers and enqueues front-end assets on demand.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Frontend;

use const EmbedSweetHome3D\PLUGIN_URL;
use const EmbedSweetHome3D\VERSION;

/**
 * The SweetHome3D HTML5 viewer ships as a chain of libraries that must load in
 * order. They are registered once and only enqueued on pages that actually
 * render a model, so the ~500&nbsp;KB payload never loads elsewhere.
 */
final class AssetManager {

	private const VIEWER_HANDLE = 'embed-sweethome3d-viewer';
	private const STYLE_HANDLE  = 'embed-sweethome3d';

	/**
	 * Ordered list of viewer libraries. Each entry depends on the previous one,
	 * which guarantees the loading order WebGL rendering relies on.
	 *
	 * @var array<int, array{handle: string, src: string}>
	 */
	private const LIBS = array(
		array(
			'handle' => 'sh3d-big',
			'src'    => 'assets/lib/big.min.js',
		),
		array(
			'handle' => 'sh3d-gl-matrix',
			'src'    => 'assets/lib/gl-matrix-min.js',
		),
		array(
			'handle' => 'sh3d-jszip',
			'src'    => 'assets/lib/jszip.min.js',
		),
		array(
			'handle' => 'sh3d-core',
			'src'    => 'assets/lib/core.min.js',
		),
		array(
			'handle' => 'sh3d-geom',
			'src'    => 'assets/lib/geom.min.js',
		),
		array(
			'handle' => 'sh3d-triangulator',
			'src'    => 'assets/lib/triangulator.min.js',
		),
		array(
			'handle' => 'sh3d-viewmodel',
			'src'    => 'assets/lib/viewmodel.min.js',
		),
		array(
			'handle' => 'sh3d-viewhome',
			'src'    => 'assets/lib/viewhome.min.js',
		),
	);

	/**
	 * Register (but do not enqueue) all front-end assets.
	 */
	public function register(): void {
		$previous = array();

		foreach ( self::LIBS as $lib ) {
			wp_register_script(
				$lib['handle'],
				PLUGIN_URL . $lib['src'],
				$previous,
				VERSION,
				true
			);
			$previous = array( $lib['handle'] );
		}

		// The viewer glue depends on the last library in the chain.
		wp_register_script(
			self::VIEWER_HANDLE,
			PLUGIN_URL . 'assets/js/viewer.js',
			$previous,
			VERSION,
			true
		);

		wp_register_style(
			self::STYLE_HANDLE,
			PLUGIN_URL . 'assets/css/public.css',
			array(),
			VERSION
		);
	}

	/**
	 * Enqueue the viewer. Safe to call multiple times per request.
	 */
	public function enqueue(): void {
		// In the block editor's server-side render the scripts are registered by
		// the parent request; guard against a missing registration just in case.
		if ( ! wp_script_is( self::VIEWER_HANDLE, 'registered' ) ) {
			$this->register();
		}

		wp_enqueue_script( self::VIEWER_HANDLE );
		wp_enqueue_style( self::STYLE_HANDLE );
	}
}

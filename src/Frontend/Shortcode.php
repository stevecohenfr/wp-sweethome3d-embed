<?php
/**
 * The `[sh3d]` shortcode.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Frontend;

use EmbedSweetHome3D\Repository\ModelRepository;

/**
 * Backwards-compatible shortcode: `[sh3d id=1 width=800 ratio=16:9 rotation=1 nav=default]`.
 */
final class Shortcode {

	private readonly ViewerRenderer $renderer;

	public function __construct( ModelRepository $repository, AssetManager $assets ) {
		$this->renderer = new ViewerRenderer( $repository, $assets );
	}

	public function register(): void {
		add_shortcode( 'sh3d', array( $this, 'render' ) );
	}

	/**
	 * @param array<string, mixed>|string $atts
	 */
	public function render( $atts ): string {
		$atts = shortcode_atts( ViewerRenderer::defaults(), (array) $atts, 'sh3d' );

		return $this->renderer->render( $atts );
	}
}

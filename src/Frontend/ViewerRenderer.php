<?php
/**
 * Turns a set of attributes into the HTML markup of a 3D viewer.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Frontend;

use EmbedSweetHome3D\Repository\ModelRepository;
use const EmbedSweetHome3D\PLUGIN_DIR;

/**
 * Shared rendering used by both the shortcode and the block.
 */
final class ViewerRenderer {

	/**
	 * Allowed aspect ratios, mapped to their CSS `aspect-ratio` value.
	 */
	private const RATIOS = array(
		'4:3'  => '4 / 3',
		'16:9' => '16 / 9',
		'3:2'  => '3 / 2',
		'1:1'  => '1 / 1',
	);

	public function __construct(
		private readonly ModelRepository $repository,
		private readonly AssetManager $assets,
	) {}

	/**
	 * Default attribute set. Also used to declare the block attributes.
	 *
	 * @return array<string, mixed>
	 */
	public static function defaults(): array {
		return array(
			'id'       => 0,
			'width'    => 0,      // 0 = responsive (100% of the container).
			'ratio'    => '4:3',
			'rotation' => 0,      // Rounds per minute; 0 disables auto-rotation.
			'nav'      => 'none', // "none" or "default".
		);
	}

	/**
	 * Render the viewer markup for the given attributes.
	 *
	 * @param array<string, mixed> $atts Raw attributes.
	 */
	public function render( array $atts ): string {
		$atts = $this->sanitize( $atts );

		$model = $this->repository->find( $atts['id'] );

		if ( null === $model || empty( $model['url'] ) ) {
			return $this->error_message();
		}

		$this->assets->enqueue();

		$instance_id = 'sh3d-' . $atts['id'] . '-' . wp_unique_id();

		$data = array(
			'model_url'    => $model['url'],
			'instance_id'  => $instance_id,
			'aspect_ratio' => self::RATIOS[ $atts['ratio'] ],
			'width'        => $atts['width'],
			'rotation'     => $atts['rotation'],
			'nav'          => $atts['nav'],
		);

		ob_start();
		require PLUGIN_DIR . 'views/viewer.php';

		return (string) ob_get_clean();
	}

	/**
	 * Normalise and constrain raw attributes.
	 *
	 * @param array<string, mixed> $atts
	 * @return array{id:int, width:int, ratio:string, rotation:int, nav:string}
	 */
	private function sanitize( array $atts ): array {
		$atts = wp_parse_args( $atts, self::defaults() );

		$ratio = is_string( $atts['ratio'] ) && isset( self::RATIOS[ $atts['ratio'] ] )
			? $atts['ratio']
			: '4:3';

		return array(
			'id'       => absint( $atts['id'] ),
			'width'    => absint( $atts['width'] ),
			'ratio'    => $ratio,
			'rotation' => max( 0, absint( $atts['rotation'] ) ),
			'nav'      => 'default' === $atts['nav'] ? 'default' : 'none',
		);
	}

	/**
	 * Message shown when the referenced model does not exist.
	 */
	private function error_message(): string {
		return sprintf(
			'<span class="sweethome3d-error">%s</span>',
			esc_html__(
				'This SweetHome3D model is invalid. Use the shortcode shown in the SweetHome3D admin screen (e.g. [sh3d id=1]).',
				'embed-sweethome3d'
			)
		);
	}
}

<?php
/**
 * The "SweetHome3D Model" block.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Frontend;

use EmbedSweetHome3D\Repository\ModelRepository;
use const EmbedSweetHome3D\PLUGIN_DIR;
use const EmbedSweetHome3D\PLUGIN_URL;
use const EmbedSweetHome3D\VERSION;

/**
 * A dynamic (server-rendered) block that wraps {@see ViewerRenderer}.
 *
 * The editor UI is a dependency-free script (no JSX build step) that uses the
 * `ServerSideRender` component for a live preview.
 */
final class Block {

	private const EDITOR_HANDLE = 'embed-sweethome3d-block-editor';

	private readonly ViewerRenderer $renderer;

	public function __construct(
		private readonly ModelRepository $repository,
		AssetManager $assets,
	) {
		$this->renderer = new ViewerRenderer( $repository, $assets );
	}

	public function register(): void {
		wp_register_script(
			self::EDITOR_HANDLE,
			PLUGIN_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-server-side-render', 'wp-i18n' ),
			VERSION,
			true
		);

		wp_set_script_translations( self::EDITOR_HANDLE, 'embed-sweethome3d' );

		wp_localize_script(
			self::EDITOR_HANDLE,
			'EmbedSweetHome3D',
			array(
				'models' => $this->model_choices(),
			)
		);

		register_block_type(
			PLUGIN_DIR . 'blocks/embed-sweethome3d',
			array( 'render_callback' => array( $this, 'render' ) )
		);
	}

	/**
	 * Render callback for the dynamic block.
	 *
	 * @param array<string, mixed> $attributes
	 */
	public function render( array $attributes ): string {
		return $this->renderer->render( $attributes );
	}

	/**
	 * Build the list of models offered in the editor's dropdown.
	 *
	 * @return array<int, array{value:int, label:string}>
	 */
	private function model_choices(): array {
		$choices = array();

		foreach ( $this->repository->paginate( 1000, 1, 'name', 'ASC' ) as $model ) {
			$choices[] = array(
				'value' => (int) $model['id'],
				'label' => sprintf( '%s (#%d)', $model['name'], (int) $model['id'] ),
			);
		}

		return $choices;
	}
}

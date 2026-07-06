<?php
/**
 * Front-end viewer markup.
 *
 * Rendered by {@see \EmbedSweetHome3D\Frontend\ViewerRenderer::render()}.
 *
 * @package EmbedSweetHome3D
 *
 * @var array{model_url:string, instance_id:string, aspect_ratio:string, width:int, rotation:int, nav:string} $data
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$style = 'aspect-ratio:' . $data['aspect_ratio'] . ';';
if ( $data['width'] > 0 ) {
	$style .= 'max-width:' . $data['width'] . 'px;';
}
?>
<div
	class="sweethome3d-model"
	id="<?php echo esc_attr( $data['instance_id'] ); ?>"
	style="<?php echo esc_attr( $style ); ?>"
	data-model="<?php echo esc_url( $data['model_url'] ); ?>"
	data-rotation="<?php echo esc_attr( (string) $data['rotation'] ); ?>"
	data-nav="<?php echo esc_attr( $data['nav'] ); ?>"
>
	<canvas
		class="viewerComponent sweethome3d-canvas"
		id="<?php echo esc_attr( $data['instance_id'] . '-canvas' ); ?>"
		tabindex="1"
	></canvas>

	<div class="sweethome3d-progress" id="<?php echo esc_attr( $data['instance_id'] . '-progress' ); ?>">
		<progress class="viewerComponent" value="0" max="200"></progress>
		<label class="viewerComponent"></label>
	</div>

	<div class="sweethome3d-copyright">
		<a href="https://www.sweethome3d.com" target="_blank" rel="noopener noreferrer">Sweet Home 3D</a>
		<?php esc_html_e( 'HTML5 Viewer — distributed under the GNU General Public License', 'embed-sweethome3d' ); ?>
	</div>
</div>

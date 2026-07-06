<?php
/**
 * Admin screen markup.
 *
 * Rendered by {@see \EmbedSweetHome3D\Admin\AdminPage::render()}.
 *
 * @package EmbedSweetHome3D
 *
 * @var array{type:string, text:string}|null                     $notice
 * @var \EmbedSweetHome3D\Admin\ModelListTable|null               $list_table
 * @var string                                                    $upload_nonce
 * @var string                                                    $upload_field
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'SweetHome3D Manager', 'embed-sweethome3d' ); ?></h1>

	<?php if ( $notice ) : ?>
		<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissible">
			<p><?php echo esc_html( $notice['text'] ); ?></p>
		</div>
	<?php endif; ?>

	<div class="sweethome3d-upload card">
		<h2><?php esc_html_e( 'Upload a model', 'embed-sweethome3d' ); ?></h2>
		<p class="description">
			<?php esc_html_e( 'Upload the house ZIP contained inside your SweetHome3D "Export to HTML5" archive.', 'embed-sweethome3d' ); ?>
		</p>
		<form method="post" enctype="multipart/form-data">
			<?php wp_nonce_field( $upload_nonce ); ?>
			<input type="file" name="<?php echo esc_attr( $upload_field ); ?>" accept=".zip" required />
			<?php submit_button( __( 'Upload', 'embed-sweethome3d' ), 'primary', 'submit', false ); ?>
		</form>
	</div>

	<h2><?php esc_html_e( 'Your models', 'embed-sweethome3d' ); ?></h2>
	<form method="post">
		<input type="hidden" name="page" value="sweethome3d" />
		<?php
		if ( $list_table instanceof \EmbedSweetHome3D\Admin\ModelListTable ) {
			$list_table->prepare_items();
			$list_table->display();
		}
		?>
	</form>
</div>

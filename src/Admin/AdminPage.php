<?php
/**
 * The "SweetHome3D" admin screen.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Admin;

use EmbedSweetHome3D\Repository\ModelRepository;
use EmbedSweetHome3D\Support\Sh3dValidator;
use const EmbedSweetHome3D\PLUGIN_DIR;
use const EmbedSweetHome3D\PLUGIN_URL;
use const EmbedSweetHome3D\VERSION;

/**
 * Registers the admin menu, handles uploads and renders the model manager.
 */
final class AdminPage {

	private const MENU_SLUG      = 'sweethome3d';
	private const CAPABILITY     = 'manage_options';
	private const PER_PAGE_OPT   = 'sh3ds_per_page';
	private const UPLOAD_NONCE   = 'sh3d_upload';
	private const UPLOAD_FIELD   = 'sh3d_upload';

	private ?ModelListTable $list_table = null;

	public function __construct( private readonly ModelRepository $repository ) {}

	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_filter( 'set-screen-option', array( $this, 'save_screen_option' ), 10, 3 );
	}

	public function add_menu(): void {
		$hook = add_menu_page(
			__( 'SweetHome3D', 'embed-sweethome3d' ),
			__( 'SweetHome3D', 'embed-sweethome3d' ),
			self::CAPABILITY,
			self::MENU_SLUG,
			array( $this, 'render' ),
			'dashicons-admin-home'
		);

		if ( $hook ) {
			// Runs before any output: the right place to process form actions
			// (so we can redirect) and to declare screen options.
			add_action( "load-{$hook}", array( $this, 'on_load' ) );
		}
	}

	public function on_load(): void {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		$this->handle_upload();

		$this->list_table = new ModelListTable( $this->repository );
		$this->list_table->process_actions();

		add_screen_option(
			'per_page',
			array(
				'label'   => __( 'Models per page', 'embed-sweethome3d' ),
				'default' => 5,
				'option'  => self::PER_PAGE_OPT,
			)
		);
	}

	public function save_screen_option( $status, string $option, $value ) {
		return self::PER_PAGE_OPT === $option ? absint( $value ) : $status;
	}

	public function enqueue( string $hook ): void {
		if ( 'toplevel_page_' . self::MENU_SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'embed-sweethome3d-admin',
			PLUGIN_URL . 'assets/css/admin.css',
			array(),
			VERSION
		);
	}

	/**
	 * Handle the ZIP upload: capability, nonce, content validation, then store.
	 */
	private function handle_upload(): void {
		if ( empty( $_FILES[ self::UPLOAD_FIELD ]['name'] ) ) {
			return;
		}

		check_admin_referer( self::UPLOAD_NONCE );

		// A file was submitted but PHP rejected it (e.g. bigger than
		// upload_max_filesize). Report it instead of failing silently.
		$error = $_FILES[ self::UPLOAD_FIELD ]['error'] ?? UPLOAD_ERR_NO_FILE;
		if ( UPLOAD_ERR_OK !== $error ) {
			$this->redirect( in_array( $error, array( UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE ), true ) ? 'toobig' : 'error' );
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		// Validate the archive before it enters the media library.
		$tmp = $_FILES[ self::UPLOAD_FIELD ]['tmp_name'] ?? '';
		if ( ! is_string( $tmp ) || ! Sh3dValidator::is_valid( $tmp ) ) {
			$this->redirect( 'invalid' );
		}

		$attachment_id = media_handle_upload( self::UPLOAD_FIELD, 0 );

		if ( is_wp_error( $attachment_id ) ) {
			$this->redirect( 'error' );
		}

		$this->repository->insert( (int) $attachment_id );
		$this->redirect( 'uploaded' );
	}

	/**
	 * Redirect back to the admin page with a status message, then stop.
	 */
	private function redirect( string $message ): void {
		wp_safe_redirect(
			add_query_arg(
				array(
					'page'         => self::MENU_SLUG,
					'sh3d_message' => $message,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	public function render(): void {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'You are not allowed to access this page.', 'embed-sweethome3d' ) );
		}

		$notice     = $this->notice();
		$list_table = $this->list_table;
		$upload_nonce = self::UPLOAD_NONCE;
		$upload_field = self::UPLOAD_FIELD;

		require PLUGIN_DIR . 'views/admin-page.php';
	}

	/**
	 * Resolve the current admin notice, if any.
	 *
	 * @return array{type:string, text:string}|null
	 */
	private function notice(): ?array {
		// The whole POST body exceeded post_max_size: PHP discarded it, so
		// $_POST and $_FILES are empty even though a request was sent.
		if (
			'POST' === ( $_SERVER['REQUEST_METHOD'] ?? '' )
			&& empty( $_POST )
			&& (int) ( $_SERVER['CONTENT_LENGTH'] ?? 0 ) > 0
		) {
			return array(
				'type' => 'error',
				'text' => sprintf(
					/* translators: %s: server upload size limit. */
					__( 'The file is larger than the server allows (limit: %s). Ask your host to raise upload_max_filesize / post_max_size, then try again.', 'embed-sweethome3d' ),
					size_format( wp_max_upload_size() )
				),
			);
		}

		$message = isset( $_GET['sh3d_message'] ) ? sanitize_key( wp_unslash( $_GET['sh3d_message'] ) ) : '';

		return match ( $message ) {
			'uploaded' => array( 'type' => 'success', 'text' => __( 'Model uploaded successfully.', 'embed-sweethome3d' ) ),
			'deleted'  => array( 'type' => 'success', 'text' => __( 'Model deleted.', 'embed-sweethome3d' ) ),
			'invalid'  => array( 'type' => 'error', 'text' => __( 'This file is not a valid SweetHome3D model (no Home.xml entry found). Please upload the house ZIP contained inside the HTML5 export.', 'embed-sweethome3d' ) ),
			'toobig'   => array( 'type' => 'error', 'text' => sprintf(
				/* translators: %s: server upload size limit. */
				__( 'The file is larger than the server allows (limit: %s). Ask your host to raise upload_max_filesize, then try again.', 'embed-sweethome3d' ),
				size_format( wp_max_upload_size() )
			) ),
			'error'    => array( 'type' => 'error', 'text' => __( 'Upload failed. Please try again.', 'embed-sweethome3d' ) ),
			default    => null,
		};
	}
}

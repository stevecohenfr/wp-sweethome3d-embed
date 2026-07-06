<?php
/**
 * The models list table shown on the admin screen.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Admin;

use EmbedSweetHome3D\Repository\ModelRepository;
use WP_List_Table;

if ( ! class_exists( WP_List_Table::class ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Lists stored models with sorting, pagination and (bulk) deletion.
 */
final class ModelListTable extends WP_List_Table {

	private const CAPABILITY     = 'manage_options';
	private const DELETE_NONCE   = 'sh3d_delete_model';
	private const MENU_SLUG      = 'sweethome3d';
	private const PER_PAGE_OPT   = 'sh3ds_per_page';

	public function __construct( private readonly ModelRepository $repository ) {
		parent::__construct(
			array(
				'singular' => 'model',
				'plural'   => 'models',
				'ajax'     => false,
			)
		);
	}

	/**
	 * @return array<string, string>
	 */
	public function get_columns(): array {
		return array(
			'cb'        => '<input type="checkbox" />',
			'name'      => __( 'Name', 'embed-sweethome3d' ),
			'shortcode' => __( 'Shortcode', 'embed-sweethome3d' ),
			'date'      => __( 'Date', 'embed-sweethome3d' ),
		);
	}

	/**
	 * @return array<string, array{0:string,1:bool}>
	 */
	protected function get_sortable_columns(): array {
		return array(
			'name' => array( 'name', true ),
			'date' => array( 'date', false ),
		);
	}

	/**
	 * @return array<string, string>
	 */
	protected function get_bulk_actions(): array {
		return array( 'bulk-delete' => __( 'Delete', 'embed-sweethome3d' ) );
	}

	public function no_items(): void {
		esc_html_e( 'No model available yet. Upload a SweetHome3D house ZIP above.', 'embed-sweethome3d' );
	}

	/**
	 * @param array<string, mixed> $item
	 */
	protected function column_cb( $item ): string {
		return sprintf( '<input type="checkbox" name="ids[]" value="%d" />', (int) $item['id'] );
	}

	/**
	 * @param array<string, mixed> $item
	 */
	protected function column_name( $item ): string {
		$delete_url = wp_nonce_url(
			add_query_arg(
				array(
					'page'   => self::MENU_SLUG,
					'action' => 'delete',
					'id'     => (int) $item['id'],
				),
				admin_url( 'admin.php' )
			),
			self::DELETE_NONCE
		);

		$actions = array(
			'delete' => sprintf(
				'<a href="%s" onclick="return confirm(\'%s\');">%s</a>',
				esc_url( $delete_url ),
				esc_js( __( 'Delete this model?', 'embed-sweethome3d' ) ),
				esc_html__( 'Delete', 'embed-sweethome3d' )
			),
		);

		return sprintf( '<strong>%s</strong>%s', esc_html( $item['name'] ), $this->row_actions( $actions ) );
	}

	/**
	 * @param array<string, mixed> $item
	 */
	protected function column_shortcode( $item ): string {
		return sprintf( '<code>[sh3d id=%d]</code>', (int) $item['id'] );
	}

	/**
	 * @param array<string, mixed> $item
	 * @param string               $column_name
	 */
	protected function column_default( $item, $column_name ): string {
		return isset( $item[ $column_name ] ) ? esc_html( (string) $item[ $column_name ] ) : '';
	}

	public function prepare_items(): void {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		$per_page = $this->get_items_per_page( self::PER_PAGE_OPT, 5 );
		$page     = $this->get_pagenum();

		$orderby = isset( $_REQUEST['orderby'] ) ? sanitize_key( wp_unslash( $_REQUEST['orderby'] ) ) : 'id';
		$order   = isset( $_REQUEST['order'] ) ? sanitize_key( wp_unslash( $_REQUEST['order'] ) ) : 'asc';

		$total = $this->repository->count();

		$this->set_pagination_args(
			array(
				'total_items' => $total,
				'per_page'    => $per_page,
			)
		);

		$this->items = $this->repository->paginate( $per_page, $page, $orderby, $order );
	}

	/**
	 * Process delete / bulk-delete actions. Called before any output so we can
	 * safely redirect. Performs capability and nonce checks.
	 */
	public function process_actions(): void {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		$action  = $this->current_action();
		$deleted = false;

		if ( 'delete' === $action && isset( $_GET['id'] ) ) {
			check_admin_referer( self::DELETE_NONCE );
			$this->repository->delete( absint( $_GET['id'] ) );
			$deleted = true;
		} elseif ( 'bulk-delete' === $action && ! empty( $_REQUEST['ids'] ) ) {
			check_admin_referer( 'bulk-' . $this->_args['plural'] );

			$ids = array_map( 'absint', (array) wp_unslash( $_REQUEST['ids'] ) );
			foreach ( array_filter( $ids ) as $id ) {
				$this->repository->delete( $id );
			}
			$deleted = true;
		}

		if ( $deleted ) {
			wp_safe_redirect(
				add_query_arg(
					array(
						'page'         => self::MENU_SLUG,
						'sh3d_message' => 'deleted',
					),
					admin_url( 'admin.php' )
				)
			);
			exit;
		}
	}
}

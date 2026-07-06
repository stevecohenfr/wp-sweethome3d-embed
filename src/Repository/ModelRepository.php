<?php
/**
 * Data access layer for embedded SweetHome3D models.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Repository;

use const EmbedSweetHome3D\DB_VERSION;

/**
 * Reads and writes the custom `{prefix}sh3dembed` table.
 *
 * The table name and columns are kept identical to the 1.0.x releases so that
 * existing installations upgrade transparently.
 */
final class ModelRepository {

	private const DB_VERSION_OPTION = 'sh3dembed_db_version';

	/**
	 * Columns that are safe to use in an ORDER BY clause.
	 *
	 * @var string[]
	 */
	private const SORTABLE_COLUMNS = array( 'id', 'name', 'date' );

	/**
	 * Fully-qualified table name (including the WordPress prefix).
	 */
	public function table(): string {
		global $wpdb;

		return $wpdb->prefix . 'sh3dembed';
	}

	/**
	 * Create or update the database schema.
	 */
	public function install(): void {
		global $wpdb;

		$table           = $this->table();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			name varchar(255) NOT NULL,
			fileid bigint(20) NOT NULL,
			url varchar(255) NOT NULL,
			PRIMARY KEY  (id)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( self::DB_VERSION_OPTION, DB_VERSION );
	}

	/**
	 * Run the schema installer when the stored version is out of date.
	 */
	public function maybe_upgrade(): void {
		if ( get_option( self::DB_VERSION_OPTION ) !== DB_VERSION ) {
			$this->install();
		}
	}

	/**
	 * Insert a model row from a media attachment ID.
	 *
	 * @return int The inserted row ID, or 0 on failure.
	 */
	public function insert( int $attachment_id ): int {
		global $wpdb;

		$attachment = get_post( $attachment_id );
		if ( null === $attachment ) {
			return 0;
		}

		$inserted = $wpdb->insert(
			$this->table(),
			array(
				'date'   => current_time( 'mysql' ),
				'name'   => $attachment->post_title,
				'fileid' => $attachment_id,
				'url'    => wp_get_attachment_url( $attachment_id ),
			),
			array( '%s', '%s', '%d', '%s' )
		);

		return $inserted ? (int) $wpdb->insert_id : 0;
	}

	/**
	 * Fetch a single model by ID.
	 *
	 * @return array<string, mixed>|null
	 */
	public function find( int $id ): ?array {
		global $wpdb;

		$row = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$this->table()} WHERE id = %d", $id ),
			ARRAY_A
		);

		return $row ?: null;
	}

	/**
	 * Fetch a page of models.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function paginate( int $per_page = 5, int $page = 1, string $orderby = 'id', string $order = 'ASC' ): array {
		global $wpdb;

		$orderby = in_array( $orderby, self::SORTABLE_COLUMNS, true ) ? $orderby : 'id';
		$order   = 'ASC' === strtoupper( $order ) ? 'ASC' : 'DESC';
		$per_page = max( 1, $per_page );
		$offset   = max( 0, ( $page - 1 ) * $per_page );

		// $orderby/$order are validated against a whitelist above, so they are
		// safe to interpolate; the LIMIT/OFFSET values are bound as integers.
		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$this->table()} ORDER BY {$orderby} {$order} LIMIT %d OFFSET %d",
				$per_page,
				$offset
			),
			ARRAY_A
		);
	}

	/**
	 * Delete a model row.
	 */
	public function delete( int $id ): void {
		global $wpdb;

		$wpdb->delete( $this->table(), array( 'id' => $id ), array( '%d' ) );
	}

	/**
	 * Total number of stored models.
	 */
	public function count(): int {
		global $wpdb;

		return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$this->table()}" );
	}

	/**
	 * Drop the table and delete related options. Used on uninstall.
	 */
	public function drop(): void {
		global $wpdb;

		$table = $this->table();
		$wpdb->query( "DROP TABLE IF EXISTS {$table}" );
		delete_option( self::DB_VERSION_OPTION );
	}
}

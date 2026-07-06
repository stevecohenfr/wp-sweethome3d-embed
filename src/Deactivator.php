<?php
/**
 * Runs on plugin deactivation.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D;

final class Deactivator {

	/**
	 * Nothing to clean up on deactivation; data is only removed on uninstall.
	 */
	public static function deactivate(): void {}
}

<?php
/**
 * Runs on plugin activation.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D;

use EmbedSweetHome3D\Repository\ModelRepository;

final class Activator {

	public static function activate(): void {
		( new ModelRepository() )->install();
	}
}

<?php
/**
 * Validates that an uploaded archive is a SweetHome3D HTML5 model.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

namespace EmbedSweetHome3D\Support;

use ZipArchive;

/**
 * A SweetHome3D "home" archive is a ZIP that contains a `Home.xml` entry.
 *
 * This is exactly the file the plugin needs, and validating it up front avoids
 * the classic "No Home.xml entry" runtime error described in the FAQ.
 */
final class Sh3dValidator {

	/**
	 * Whether ZIP inspection is available on this server.
	 */
	public static function can_validate(): bool {
		return class_exists( ZipArchive::class );
	}

	/**
	 * Check that the file at the given path is a valid SweetHome3D model.
	 */
	public static function is_valid( string $path ): bool {
		// If we cannot inspect archives, don't block the upload.
		if ( ! self::can_validate() ) {
			return true;
		}

		$zip = new ZipArchive();
		if ( true !== $zip->open( $path ) ) {
			return false;
		}

		$found = false !== $zip->locateName( 'Home.xml', ZipArchive::FL_NOCASE | ZipArchive::FL_NODIR );
		$zip->close();

		return $found;
	}
}

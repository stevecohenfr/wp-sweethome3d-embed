<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * Removes the custom table and options created by the plugin. Uploaded media
 * (the house ZIPs) are intentionally left in the Media Library.
 *
 * @package EmbedSweetHome3D
 */

declare( strict_types=1 );

// If uninstall is not called from WordPress, abort.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once __DIR__ . '/src/Repository/ModelRepository.php';

( new EmbedSweetHome3D\Repository\ModelRepository() )->drop();

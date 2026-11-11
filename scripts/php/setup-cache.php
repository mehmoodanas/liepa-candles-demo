<?php
/**
 * Turns on WP Super Cache in "simple" (PHP) mode, which works on Local's nginx server.
 * Logged-in users and the cart/checkout/account pages are never cached by the plugin.
 *
 * Run: scripts\wp.ps1 eval-file scripts\php\setup-cache.php
 */

if ( ! function_exists( 'wp_cache_enable' ) ) {
	echo "WP Super Cache is not active\n";
	return;
}

global $cache_enabled, $super_cache_enabled;
wp_cache_setting( 'cache_enabled', true );
wp_cache_setting( 'super_cache_enabled', true );
wp_cache_setting( 'cache_rebuild_files', 1 );   // serve a fresh copy while rebuilding
wp_cache_setting( 'cache_compression', 0 );     // the web server handles compression
wp_cache_enable();

echo 'cache_enabled: ' . var_export( (bool) $GLOBALS['cache_enabled'], true ) . "\n";
echo 'advanced-cache.php present: ' . var_export( file_exists( WP_CONTENT_DIR . '/advanced-cache.php' ), true ) . "\n";
echo 'WP_CACHE in wp-config: ' . ( false !== strpos( (string) file_get_contents( ABSPATH . 'wp-config.php' ), "'WP_CACHE'" ) ? 'yes' : 'no' ) . "\n";

<?php
/**
 * Plugin Name:       Liepa 3D Viewer
 * Description:       Shows an optional 3D model (GLB) with an AR button on WooCommerce product pages.
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Requires Plugins:  woocommerce
 * Text Domain:       liepa-3d-viewer
 *
 * Entry point only: it defines constants and wires the classes together.
 * Each class lives in its own file under /includes.
 */

defined( 'ABSPATH' ) || exit;

define( 'LIEPA_3D_VERSION', '0.1.0' );
define( 'LIEPA_3D_PATH', plugin_dir_path( __FILE__ ) );
define( 'LIEPA_3D_URL', plugin_dir_url( __FILE__ ) );

require_once LIEPA_3D_PATH . 'includes/class-model-meta.php';
require_once LIEPA_3D_PATH . 'includes/class-assets.php';
require_once LIEPA_3D_PATH . 'includes/class-viewer-renderer.php';

add_action(
	'plugins_loaded',
	static function () {
		( new Liepa_Model_Meta() )->register();
		( new Liepa_Assets() )->register();
		( new Liepa_Viewer_Renderer() )->register();
	}
);

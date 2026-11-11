<?php
/**
 * Loads the 3D viewer assets only on product pages that have a 3D model, and loads the heavy
 * model-viewer library only when the viewer scrolls near the screen (see assets/js/viewer-loader.js).
 * Everywhere else the store pays no performance cost.
 */

defined( 'ABSPATH' ) || exit;

class Liepa_Assets {

	private const LOADER_HANDLE = 'liepa-viewer-loader';
	private const LIBRARY_SRC   = 'https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js';

	public function register(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	public function enqueue(): void {
		if ( ! is_product() || '' === Liepa_Model_Meta::get_model_url( get_the_ID() ) ) {
			return;
		}

		wp_enqueue_style( 'liepa-viewer', LIEPA_3D_URL . 'assets/css/viewer.css', array(), LIEPA_3D_VERSION );
		wp_enqueue_script(
			self::LOADER_HANDLE,
			LIEPA_3D_URL . 'assets/js/viewer-loader.js',
			array(),
			LIEPA_3D_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_add_inline_script(
			self::LOADER_HANDLE,
			'window.liepaViewer = ' . wp_json_encode( array( 'src' => self::LIBRARY_SRC ) ) . ';',
			'before'
		);
	}
}

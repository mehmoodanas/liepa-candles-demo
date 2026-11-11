<?php
/**
 * Stores and reads the GLB model URL of a product (product edit screen field).
 */

defined( 'ABSPATH' ) || exit;

class Liepa_Model_Meta {

	public const META_KEY = '_liepa_glb_url';

	public function register(): void {
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'render_field' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_field' ) );
	}

	/** Returns the GLB URL for a product, or an empty string when none is set. */
	public static function get_model_url( int $product_id ): string {
		return (string) get_post_meta( $product_id, self::META_KEY, true );
	}

	public function render_field(): void {
		woocommerce_wp_text_input(
			array(
				'id'          => self::META_KEY,
				'label'       => __( '3D model (GLB URL)', 'liepa-3d-viewer' ),
				'description' => __( 'Keep the file under about 2 MB for fast loading.', 'liepa-3d-viewer' ),
				'desc_tip'    => true,
				'type'        => 'url',
			)
		);
	}

	public function save_field( int $product_id ): void {
		// WooCommerce verifies the nonce and capabilities before this hook fires.
		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$url = isset( $_POST[ self::META_KEY ] ) ? esc_url_raw( wp_unslash( $_POST[ self::META_KEY ] ) ) : '';
		update_post_meta( $product_id, self::META_KEY, $url );
	}
}

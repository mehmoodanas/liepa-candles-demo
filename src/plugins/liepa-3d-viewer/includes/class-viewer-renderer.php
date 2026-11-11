<?php
/**
 * Prints the <model-viewer> element on the single product page.
 */

defined( 'ABSPATH' ) || exit;

class Liepa_Viewer_Renderer {

	public function register(): void {
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'render' ), 5 );
	}

	public function render(): void {
		$model_url = Liepa_Model_Meta::get_model_url( get_the_ID() );
		if ( '' === $model_url ) {
			return;
		}
		// Use the same scheme (http/https) as the current page, so the model never loads as mixed content.
		$model_url = set_url_scheme( $model_url );

		printf(
			'<section class="liepa-viewer"><h2>%1$s</h2>
			<model-viewer src="%2$s" alt="%3$s" camera-controls ar ar-modes="webxr scene-viewer quick-look" reveal="auto" loading="lazy" shadow-intensity="1"></model-viewer></section>',
			esc_html__( 'View in 3D', 'liepa-3d-viewer' ),
			esc_url( $model_url ),
			esc_attr( get_the_title() )
		);
	}
}

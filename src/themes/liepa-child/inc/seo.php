<?php
/**
 * Prints a meta description (the text search engines show under the page title).
 * Product pages use the product's short description; other pages use the site tagline.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_head',
	static function () {
		$text = '';
		if ( function_exists( 'is_product' ) && is_product() ) {
			$product = wc_get_product( get_the_ID() );
			$text    = $product ? $product->get_short_description() : '';
		}
		if ( '' === $text ) {
			$text = get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
		}
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_trim_words( wp_strip_all_tags( $text ), 30 ) ) );
	},
	1
);

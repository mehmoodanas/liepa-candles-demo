<?php
/**
 * Makes the [products] shortcode (used on the home page) list only products in the current language.
 * Polylang filters normal page queries by itself, but not WooCommerce shortcode queries.
 */

defined( 'ABSPATH' ) || exit;

add_filter(
	'woocommerce_shortcode_products_query',
	static function ( array $query_args ): array {
		if ( function_exists( 'pll_current_language' ) && pll_current_language() ) {
			$query_args['tax_query'][] = array(
				'taxonomy' => 'language',
				'field'    => 'slug',
				'terms'    => pll_current_language(),
			);
		}
		return $query_args;
	}
);

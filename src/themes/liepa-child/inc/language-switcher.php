<?php
/**
 * Shows the Polylang language switcher (EN | LV) in the header, if Polylang is active.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'storefront_header',
	static function () {
		if ( ! function_exists( 'pll_the_languages' ) ) {
			return;
		}
		echo '<ul class="liepa-lang" aria-label="' . esc_attr__( 'Language', 'liepa-child' ) . '">';
		pll_the_languages(
			array(
				'show_flags'    => 0,
				'show_names'    => 1,
				'display_names_as' => 'slug',
			)
		);
		echo '</ul>';
	},
	45
);

<?php
/**
 * Loads theme styles. The hero animation is pure CSS, so no extra JavaScript is shipped,
 * and the hero stylesheet is only requested on the home page.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_enqueue_style( 'liepa-lang', get_stylesheet_directory_uri() . '/assets/css/language-switcher.css', array(), LIEPA_CHILD_VERSION );

		if ( ! is_front_page() ) {
			return;
		}
		wp_enqueue_style( 'liepa-hero', get_stylesheet_directory_uri() . '/assets/css/hero.css', array(), LIEPA_CHILD_VERSION );
	}
);

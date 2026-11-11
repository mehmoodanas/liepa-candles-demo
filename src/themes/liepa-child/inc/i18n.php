<?php
/**
 * Loads translations (Latvian / English) for the child theme.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function () {
		load_child_theme_textdomain( 'liepa-child', get_stylesheet_directory() . '/languages' );
	}
);

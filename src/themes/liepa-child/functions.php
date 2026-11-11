<?php
/**
 * Liepa Child theme entry point: loads the feature files from /inc.
 */

defined( 'ABSPATH' ) || exit;

define( 'LIEPA_CHILD_VERSION', '0.1.0' );

require_once get_stylesheet_directory() . '/inc/enqueue.php';
require_once get_stylesheet_directory() . '/inc/i18n.php';
require_once get_stylesheet_directory() . '/inc/language-switcher.php';
require_once get_stylesheet_directory() . '/inc/language-products.php';
require_once get_stylesheet_directory() . '/inc/seo.php';

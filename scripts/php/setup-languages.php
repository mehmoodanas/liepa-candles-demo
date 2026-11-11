<?php
/**
 * Sets up Polylang with English (default) and Latvian, and makes products and
 * product categories translatable. Safe to re-run.
 *
 * Run: scripts\wp.ps1 eval-file scripts\php\setup-languages.php
 */

if ( ! function_exists( 'PLL' ) ) {
	echo "Polylang is not active\n";
	return;
}

$languages = array(
	'en' => array( 'name' => 'English', 'slug' => 'en', 'locale' => 'en_US', 'rtl' => 0, 'flag' => 'gb', 'term_group' => 0 ),
	'lv' => array( 'name' => 'Latviešu', 'slug' => 'lv', 'locale' => 'lv', 'rtl' => 0, 'flag' => 'lv', 'term_group' => 1 ),
);

$model = new PLL_Admin_Model( PLL()->options );
foreach ( $languages as $slug => $args ) {
	if ( $model->get_language( $slug ) ) {
		echo "language {$slug} already exists\n";
		continue;
	}
	$result = $model->add_language( $args );
	echo is_wp_error( $result ) ? "{$slug}: " . $result->get_error_message() . "\n" : "added language {$slug}\n";
}

// Use Polylang's own options object: a plain update_option() would be overwritten when it saves.
$options                 = PLL()->options;
$options['default_lang'] = 'en';
$options['post_types']   = array_values( array_unique( array_merge( (array) $options['post_types'], array( 'product' ) ) ) );
$options['taxonomies']   = array_values( array_unique( array_merge( (array) $options['taxonomies'], array( 'product_cat' ) ) ) );
$options->save();
PLL()->model->clean_languages_cache();
echo "default language en; translatable: " . implode( ', ', array_merge( $options['post_types'], $options['taxonomies'] ) ) . "\n";

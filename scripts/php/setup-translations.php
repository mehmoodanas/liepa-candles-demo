<?php
/**
 * Creates the Latvian content with Polylang: category names, a Latvian copy of every product
 * and a Latvian home page, all linked to their English originals. Safe to re-run.
 *
 * Needs setup-languages.php first.
 * Run: scripts\wp.ps1 eval-file scripts\php\setup-translations.php
 *
 * Limit: Polylang free does not sync stock between languages (that is a paid add-on).
 */

const LIEPA_ROOT = 'E:/Projects/WD-Market-Demo-Store';

require_once WC_ABSPATH . 'includes/admin/class-wc-admin-duplicate-product.php';

/** Gives existing English content its language so Polylang can link translations to it. */
function liepa_mark_english(): void {
	$products = get_posts( array( 'post_type' => 'product', 'numberposts' => -1, 'fields' => 'ids', 'post_status' => 'any' ) );
	$pages    = get_posts( array( 'post_type' => 'page', 'numberposts' => -1, 'fields' => 'ids', 'post_status' => 'any' ) );
	foreach ( array_merge( $products, $pages ) as $id ) {
		if ( ! pll_get_post_language( $id ) ) {
			pll_set_post_language( $id, 'en' );
		}
	}
	foreach ( get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) ) as $term ) {
		if ( ! pll_get_term_language( $term->term_id ) ) {
			pll_set_term_language( $term->term_id, 'en' );
		}
	}
}

/** Returns the term id of the Latvian category for an English category name. */
function liepa_latvian_category( string $english_name ): int {
	$names = array( 'Soy candles' => 'Sojas sveces', 'Gift sets' => 'Dāvanu komplekti' );
	$en    = get_term_by( 'name', $english_name, 'product_cat' );
	$lv_id = pll_get_term( $en->term_id, 'lv' );
	if ( $lv_id ) {
		return (int) $lv_id;
	}
	$created = wp_insert_term( $names[ $english_name ], 'product_cat', array( 'slug' => sanitize_title( $names[ $english_name ] ) ) );
	$lv_id   = (int) $created['term_id'];
	pll_set_term_language( $lv_id, 'lv' );
	pll_save_term_translations( array( 'en' => $en->term_id, 'lv' => $lv_id ) );
	return $lv_id;
}

function liepa_translate_product( array $seed ): void {
	$en_id = wc_get_product_id_by_sku( $seed['sku'] );
	if ( ! $en_id || pll_get_post( $en_id, 'lv' ) ) {
		echo "skip product {$seed['sku']}\n";
		return;
	}
	$copy = ( new WC_Admin_Duplicate_Product() )->product_duplicate( wc_get_product( $en_id ) );
	$copy->set_name( $seed['name_lv'] );
	$copy->set_slug( sanitize_title( $seed['name_lv'] ) );
	$copy->set_status( 'publish' );
	$copy->set_short_description( 'Rokām liets sojas vaska svece, deg apmēram 40 stundas.' );
	$copy->set_description( 'Izdomāts demonstrācijas produkts veikalam Liepa Candles. ' . $seed['name_lv'] . '.' );
	$copy->save();

	$lv_id = $copy->get_id();
	pll_set_post_language( $lv_id, 'lv' );
	pll_save_post_translations( array( 'en' => $en_id, 'lv' => $lv_id ) );
	wp_set_object_terms( $lv_id, array( liepa_latvian_category( $seed['category'] ) ), 'product_cat' );
	echo "created Latvian product {$seed['sku']} -> {$lv_id}\n";
}

function liepa_translate_home( int $shop_url_term ): void {
	$en = get_page_by_path( 'home' );
	if ( ! $en || pll_get_post( $en->ID, 'lv' ) ) {
		echo "skip home page\n";
		return;
	}
	$html    = file_get_contents( LIEPA_ROOT . '/src/content/home.lv.html' );
	$content = str_replace( '{{SHOP_URL}}', esc_url( get_term_link( $shop_url_term, 'product_cat' ) ), $html );
	$lv_id   = wp_insert_post( array( 'post_type' => 'page', 'post_status' => 'publish', 'post_title' => 'Sākums', 'post_name' => 'sakums', 'post_content' => $content ) );
	pll_set_post_language( $lv_id, 'lv' );
	pll_save_post_translations( array( 'en' => $en->ID, 'lv' => $lv_id ) );
	echo "created Latvian home page {$lv_id}\n";
}

liepa_mark_english();
$seeds = json_decode( file_get_contents( LIEPA_ROOT . '/scripts/seed/products.json' ), true );
foreach ( $seeds as $seed ) {
	liepa_translate_product( $seed );
}
liepa_translate_home( liepa_latvian_category( 'Soy candles' ) );
// Polylang caches each language's front page; clear it so /lv/ finds the new Latvian home page.
PLL()->model->clean_languages_cache();
flush_rewrite_rules();
echo "done\n";

<?php
/**
 * Turns "Linden Blossom Soy Candle" (SKU LIE-001) into a variable product with two sizes.
 * Safe to re-run: does nothing if the product is already variable.
 *
 * Run: scripts\wp.ps1 eval-file scripts\php\setup-variable-product.php
 */

const LIEPA_PARENT_SKU = 'LIE-001';

$product_id = wc_get_product_id_by_sku( LIEPA_PARENT_SKU );
if ( ! $product_id ) {
	echo "product " . LIEPA_PARENT_SKU . " not found\n";
	return;
}
if ( wc_get_product( $product_id )->is_type( 'variable' ) ) {
	echo "product {$product_id} is already variable\n";
	return;
}

$sizes = array(
	array( 'label' => 'Small (150 g)', 'sku' => 'LIE-001-S', 'price' => '14.90', 'stock' => 25 ),
	array( 'label' => 'Large (250 g)', 'sku' => 'LIE-001-L', 'price' => '22.90', 'stock' => 15 ),
);

$attribute = new WC_Product_Attribute();
$attribute->set_name( 'Size' );
$attribute->set_options( wp_list_pluck( $sizes, 'label' ) );
$attribute->set_visible( true );
$attribute->set_variation( true );

$product = new WC_Product_Variable( $product_id );
$product->set_attributes( array( $attribute ) );
$product->set_manage_stock( false );
$product->save();

foreach ( $sizes as $size ) {
	$variation = new WC_Product_Variation();
	$variation->set_parent_id( $product_id );
	$variation->set_attributes( array( 'size' => $size['label'] ) );
	$variation->set_sku( $size['sku'] );
	$variation->set_regular_price( $size['price'] );
	$variation->set_manage_stock( true );
	$variation->set_stock_quantity( $size['stock'] );
	$variation->save();
	echo "added variation {$size['label']}\n";
}

WC_Product_Variable::sync( $product_id );

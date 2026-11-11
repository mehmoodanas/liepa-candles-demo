<?php
/**
 * Creates the WELCOME10 coupon: 10 percent off the cart. Safe to re-run.
 *
 * Run: scripts\wp.ps1 eval-file scripts\php\setup-coupon.php
 */

if ( wc_get_coupon_id_by_code( 'WELCOME10' ) ) {
	echo "coupon WELCOME10 already exists\n";
	return;
}

$coupon = new WC_Coupon();
$coupon->set_code( 'WELCOME10' );
$coupon->set_discount_type( 'percent' );
$coupon->set_amount( 10 );
$coupon->set_description( 'Demo coupon: 10 percent off.' );
$coupon->save();

echo "created coupon WELCOME10\n";

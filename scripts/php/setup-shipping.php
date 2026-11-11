<?php
/**
 * Creates the "Latvia" shipping zone with three demo methods:
 * parcel locker, courier, and free shipping over 35 EUR.
 *
 * Run: scripts\wp.ps1 eval-file scripts\php\setup-shipping.php
 * Safe to re-run: the zone's methods are rebuilt each time.
 */

$zone = null;
foreach ( WC_Shipping_Zones::get_zones() as $existing ) {
	if ( 'Latvia' === $existing['zone_name'] ) {
		$zone = new WC_Shipping_Zone( $existing['id'] );
	}
}
if ( ! $zone ) {
	$zone = new WC_Shipping_Zone();
	$zone->set_zone_name( 'Latvia' );
}
$zone->clear_locations( array( 'country' ) );
$zone->add_location( 'LV', 'country' );
$zone->save();

foreach ( $zone->get_shipping_methods() as $method ) {
	$zone->delete_shipping_method( $method->get_instance_id() );
}

$methods = array(
	array( 'flat_rate', array( 'title' => 'Parcel locker (demo rate)', 'cost' => '2.99', 'tax_status' => 'none' ) ),
	array( 'flat_rate', array( 'title' => 'Courier (demo rate)', 'cost' => '4.99', 'tax_status' => 'none' ) ),
	array( 'free_shipping', array( 'title' => 'Free shipping over 35 EUR', 'requires' => 'min_amount', 'min_amount' => '35' ) ),
);

foreach ( $methods as list( $type, $settings ) ) {
	$instance_id = $zone->add_shipping_method( $type );
	$method      = WC_Shipping_Zones::get_shipping_method( $instance_id );
	$method->init_instance_settings();
	$method->instance_settings = array_merge( $method->instance_settings, $settings );
	update_option( $method->get_instance_option_key(), $method->instance_settings );
	echo "added {$type}: {$settings['title']}\n";
}

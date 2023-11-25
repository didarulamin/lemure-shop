<?php
/**
 * File for setting up custom WooCommerce session data.
 *
 * Author:          Uriahs Victor
 * Created on:      06/05/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.8.0
 * @package Controllers
 */

namespace Lpac\Pro\Controllers\Shipping;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac\Helpers\Functions;
use Lpac\Controllers\Checkout_Page\Controller as CheckoutPageController;

/**
 * Class for adding our customer data to the WC session.
 *
 * @package Lpac\Pro\Controllers\Shipping
 * @since 1.7.5
 */
class SetupSession {

	/**
	 * Set the Shipping session data.
	 *
	 * @param string $post_data
	 * @return void
	 * @since 1.7.5
	 */
	public function setShippingSessionData( string $post_data ): void {

		// Always clear these session variables before setting
		WC()->session->set( 'lpac_customer_coordinates', false );
		WC()->session->set( 'lpac_order__origin_store', false );
		WC()->session->set( 'lpac_customer_distance', false );
		WC()->session->set( 'lpac_customer_distance_unit', false );
		WC()->session->set( 'lpac_customer_distance_duration', false );
		WC()->session->set( 'lpac_customer_region', false );

		// --------------------------
		// Setup customer location based data.
		// --------------------------
		$data   = array();
		$fields = explode( '&', $post_data );

		foreach ( $fields as $key => $value ) {
			$parts = explode( '=', sanitize_text_field( $value ) );
			if ( empty( $parts[1] ) ) {
				 // This is important as in a rare case it was noticed that the array was later overriden with blank lat and long values.
				continue;
			}
			$data[ $parts[0] ] = $parts[1];
		}

		$latitude = $data['lpac_latitude'] ?? '';
		$latitude = Functions::normalize_coordinates( $latitude );

		$longitude = $data['lpac_longitude'] ?? '';
		$longitude = Functions::normalize_coordinates( $longitude );

		if ( empty( $latitude ) || empty( $longitude ) ) {

			/*
			 * If we have no current coordinates then this is most likely the initial page load
			 * So lets pull the last order coordinates so that we can have a price showing for the respective shipping method
			*/
			$last_location = ( new CheckoutPageController() )->get_last_order_details();

			$last_latitude  = $last_location['latitude'] ?? '';
			$last_longitude = $last_location['longitude'] ?? '';

			$latitude  = $last_latitude;
			$longitude = $last_longitude;
		}

		WC()->session->set(
			'lpac_customer_coordinates',
			array(
				'latitude'  => $latitude,
				'longitude' => $longitude,
			)
		);

		// --------------------------
		// Setup store based data.
		// --------------------------
		$store_origin_id = isset( $data['lpac_order__origin_store'] ) ? sanitize_text_field( $data['lpac_order__origin_store'] ) : null;

		if ( $store_origin_id === '' ) {
				add_filter(
					'woocommerce_shipping_may_be_available_html',
					function() {
						return __( 'Please select a store location to deliver from', 'map-location-picker-at-checkout-for-woocommerce' );
					},
					11
				);

				add_filter(
					'woocommerce_no_shipping_available_html',
					function() {
						return __( 'Please select a store location to deliver from', 'map-location-picker-at-checkout-for-woocommerce' );
					},
					11
				);

		}

		WC()->session->set( 'lpac_order__origin_store', $store_origin_id );

		foreach ( WC()->cart->get_shipping_packages() as $package_key => $package ) {
			// This is needed for us to remove the session set for the shipping cost. Without this, we can't set it on the checkout page.
			WC()->session->set( 'shipping_for_package_' . $package_key, false );
		}
	}

}

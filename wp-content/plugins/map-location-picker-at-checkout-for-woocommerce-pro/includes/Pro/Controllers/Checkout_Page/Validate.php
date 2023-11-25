<?php

/**
 * Handles checkout page related logic for Pro plugin.
 *
 * Author:          Uriahs Victor
 * Created on:      29/07/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.6.0
 * @package Lpac
 */

namespace Lpac\Pro\Controllers\Checkout_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Lpac\Models\Plugin_Settings\Store_Locations;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;

/**
 * Class responsible for checkout page validation of PRO plugin.
 *
 * @package Lpac\Pro\Controllers\Checkout_Page
 * @since 1.0.0
 */
class Validate {

	/**
	 * Check if the origin store dropdown has a selected value-- for the cost by store distance feature.
	 *
	 * @since    1.6.0
	 * @param array  $fields The fields array.
	 * @param object $errors The errors object.
	 *
	 * @return void
	 */
	public function validate_cost_by_store_distance_origin_store_dropdown( array $fields, object $errors ) : void {

		/**
		 * The store dropdown visibility might be changed via JS or other conditions
		 * So we need to check if its actually shown before trying to validate
		 *
		 * see changeMapVisibility() in checkout-page-map.js
		 */
		$map_shown = (bool) $_POST['lpac_is_map_shown'] ?? '';

		if ( $map_shown === false ) {
			return;
		}

		$enable_cost_by_store_distance = get_option( 'lpac_enable_cost_by_store_distance' );
		$enable_cost_by_store_distance = filter_var( $enable_cost_by_store_distance, FILTER_VALIDATE_BOOLEAN );

		$enable_cost_by_distance = get_option( 'lpac_enable_shipping_cost_by_distance_feature' );
		$enable_cost_by_distance = filter_var( $enable_cost_by_distance, FILTER_VALIDATE_BOOLEAN );

		if ( $enable_cost_by_distance === false || $enable_cost_by_store_distance === false ) {
			return;
		}

		if ( Store_Locations::showStoreSelectorInCheckoutEnabled() ) { // Prevent multiple of this same validation error
			return;
		}

		$origin_store = $_POST['lpac_order__origin_store'] ?? '';

		$error_msg = '<strong>' . __( 'Please select the store location you would like to order from.', 'map-location-picker-at-checkout-for-woocommerce' ) . '</strong>';

		$error_msg = apply_filters( 'lpac_checkout_empty_origin_store_msg', $error_msg );
		if ( empty( $origin_store ) ) {
			$errors->add( 'validation', $error_msg );
		}

	}

	/**
	 * Check if the origin store dropdown has a selected value-- for the cost by store location feature.
	 *
	 * @since    1.6.0
	 * @param array  $fields The fields array.
	 * @param object $errors The errors object.
	 *
	 * @return void
	 */
	public function validate_cost_by_store_location_origin_store_dropdown( array $fields, object $errors ) : void {

		/**
		 * The store dropdown visibility might be changed via JS or other conditions
		 * So we need to check if its actually shown before trying to validate
		 *
		 * see changeMapVisibility() in checkout-page-map.js
		 */
		$map_shown = (bool) $_POST['lpac_is_map_shown'] ?? '';

		if ( $map_shown === false ) {
			return;
		}

		$enable_cost_by_store_location = get_option( 'lpac_enable_cost_by_store_location' );
		$enable_cost_by_store_location = filter_var( $enable_cost_by_store_location, FILTER_VALIDATE_BOOLEAN );

		if ( empty( $enable_cost_by_store_location ) ) {
			return;
		}

		if ( Store_Locations::showStoreSelectorInCheckoutEnabled() ) { // Prevent multiple of this same validation error
			return;
		}

		$origin_store = $_POST['lpac_order__origin_store'] ?? '';

		$error_msg = '<strong>' . __( 'Please select the store location you would like to order from.', 'map-location-picker-at-checkout-for-woocommerce' ) . '</strong>';

		$error_msg = apply_filters( 'lpac_checkout_empty_origin_store_msg', $error_msg );

		if ( empty( $origin_store ) ) {
			$errors->add( 'validation', $error_msg );
		}

	}

	/**
	 * Check if the customer's cart totals satisfy what admin has set for min and max in Kikote Shipping settings.
	 *
	 * @since    1.6.8
	 * @param array  $fields The fields array.
	 * @param object $errors The errors object.
	 *
	 * @return void
	 */
	public function validate_region_order_total_details( array $fields, object $errors ) : void {

		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		$shipping_restrictions_enabled = get_option( 'lpac_enable_shipping_restrictions' );
		$shipping_restrictions_enabled = filter_var( $shipping_restrictions_enabled, FILTER_VALIDATE_BOOLEAN );

		if ( false === $shipping_restrictions_enabled ) {
			return;
		}

		// Don't run logic for local pickup methods.
		$chosen_shipping_method = WC()->session->get( 'chosen_shipping_methods' );
		$chosen_shipping_method = $chosen_shipping_method[0] ?? '';
		if ( ( strpos( $chosen_shipping_method, 'local_pickup' ) !== false ) && Shipping_Settings::enableShippingRestrictionsLocalPickup() === false ) {
			return;
		}

		$customer_region_data = WC()->session->get( 'lpac_customer_region' );

		if ( empty( $customer_region_data ) ) {
			return;
		}

		$use_highest_region_cost = apply_filters( 'lpac_use_highest_region_cost', true );

		if ( count( $customer_region_data ) > 1 && $use_highest_region_cost ) {
			$costs              = array_column( $customer_region_data, 'cost' );
			$keys               = array_keys( $costs, max( $costs ), true );
			$key                = $keys[0];
			$customer_region_id = $customer_region_data[ $key ]['region_id'];
		} else {
			$customer_region_id = $customer_region_data[0]['region_id'];
		}

		$order_total                      = apply_filters( 'lpac_region_restrictions_order_total_value', WC()->cart->total, WC() );
		$regions_order_total_restrictions = get_option( 'lpac_regions_min_max_order_total' );

		if ( empty( $regions_order_total_restrictions ) ) {
			return;
		}

		$min = 0;
		$max = 0;

		foreach ( $regions_order_total_restrictions as $key => $region_order_total_restrictions ) {
			$region_id = $region_order_total_restrictions['region_id_select'] ?? '';
			if ( empty( $region_id ) ) {
				continue;
			}
			if ( $region_id === $customer_region_id ) {
				$min = $region_order_total_restrictions['region_min_order_total_text'];
				$max = $region_order_total_restrictions['region_max_order_total_text'];
				break;
			}
		}

		if ( ! empty( $min ) && $order_total < $min ) {
			$default_error_msg = __( 'Order total for your selected region needs to be at least', 'map-location-picker-at-checkout-for-woocommerce' );
			$error_msg         = get_option( 'lpac_order_total_insufficient_text', $default_error_msg );
			$error_msg         = '<strong>' . $error_msg . ' ' . wc_price( $min ) . '</strong>';
			$error_msg         = apply_filters( 'lpac_order_total_insufficient_text', $error_msg, $order_total, $min );
			$errors->add( 'validation', $error_msg );
		}

		if ( ! empty( $max ) && $order_total > $max ) {
			$default_error_message = __( 'Order total for your selected region needs to be at most', 'map-location-picker-at-checkout-for-woocommerce' );
			$error_msg             = get_option( 'lpac_order_total_limit_passed_text', $default_error_message );
			$error_msg             = '<strong>' . $error_msg . ' ' . wc_price( $max ) . '</strong>';
			$error_msg             = apply_filters( 'lpac_order_total_limit_passed_text', $error_msg, $order_total, $max );
			$errors->add( 'validation', $error_msg );
		}

	}

}

<?php
/**
 * File responsible for cost by region methods.
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
use Location\Coordinate;
use Location\Polygon;
use Lpac\Pro\Helpers\Functions as FunctionsHelperPro;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;

/**
 * Class responsible for cost by region methods.
 *
 * @package Lpac\Pro\Controllers\Shipping
 * @since 1.7.5
 */
class CostByRegion {

	/**
	 * Check if a customer is eligible for free shipping based on the rules created in "Free Shipping for Regions" feature.
	 *
	 * @return bool
	 * @since 1.8.2
	 */
	private function checkIfRegionFreeShippingApplies( $customer_region ) : bool {

		$free_shipping_rules = Shipping_Settings::getCostByRegionFreeShippingRules();

		$use_highest_region_cost = apply_filters( 'lpac_use_highest_region_cost', true );

		$region_id = '';

		if ( count( $customer_region ) > 1 ) { // If the customer falls in two or more regions on the map.
			$region_costs = array_column( $customer_region, 'cost' );

			if ( $use_highest_region_cost ) {
				arsort( $region_costs );
			} else {
				asort( $region_costs );
			}

			$key       = array_key_first( array_slice( $region_costs, 0, 1, true ) );
			$region_id = $customer_region[ $key ]['region_id'];
		} else {
			$region_id = $customer_region[0]['region_id'];
		}

		if ( empty( $region_id ) ) {
			return false;
		}

		$region_rules = array();
		foreach ( $free_shipping_rules as $key => $value ) {
			if ( $region_id === $value['region_id_select'] ) {
				$region_rules = $free_shipping_rules[ $key ];
				break;
			}
		}

		if ( empty( $region_rules ) ) {
			return false;
		}

		$minimum_order_total = (float) $region_rules['region_min_order_total_text'];
		$maximum_order_total = (float) $region_rules['region_max_order_total_text'];

		// Without discounts shipping applied yet
		$cart_subtotal = (float) apply_filters( 'lpac_pro_cost_by_region_free_shipping_cart_total', WC()->cart->get_subtotal() );

		if ( empty( $maximum_order_total ) && $cart_subtotal >= $minimum_order_total ) {
			return true;
		}

		if ( $cart_subtotal >= $minimum_order_total && $cart_subtotal <= $maximum_order_total ) {
			return true;
		}

		return false;
	}

	/**
	 * Set the rates which the customer should pay based on their detected region.
	 *
	 * @param array $rates
	 * @return array
	 * @since 1.8.2
	 */
	private function setCustomerRegionCost( array $rates ): array {

		$regions = get_option( 'lpac_shipping_regions', array() );

		// Bail if no regions are drawn yet.
		if ( empty( $regions ) ) {
			return $rates;
		}

		$saved_regions_polygons = array_column( $regions, 'polygon' );

		$customer_coordinates = WC()->session->get( 'lpac_customer_coordinates' );

		if ( empty( $customer_coordinates['latitude'] ) || empty( $customer_coordinates['longitude'] ) ) {
			return $rates;
		}

		$customer_coordinates = new Coordinate( $customer_coordinates['latitude'], $customer_coordinates['longitude'] );

		$customer_detected_region_costs = array();
		$customer_detected_regions      = array();

		foreach ( $saved_regions_polygons as $key => $polygon_points ) {
			$geofence = new Polygon();

			foreach ( $polygon_points as $polygon_point ) {
				$geofence->addPoint( new Coordinate( $polygon_point['lat'], $polygon_point['lng'] ) );
			}

			// Check the costs for the regions that the customer current location falls within.
			if ( $geofence->contains( $customer_coordinates ) ) {
				array_push( $customer_detected_region_costs, $regions[ $key ]['cost'] );

				$customer_detected_regions[] = array(
					'region_id' => $regions[ $key ]['id'],
					'cost'      => $regions[ $key ]['cost'],
				);

				// Set a session variable with the customer region for later processing. See Location_Details::save_customer_region()
				WC()->session->set( 'lpac_customer_region', false );
				WC()->session->set( 'lpac_customer_region', $customer_detected_regions );
			}
		}

		$ship_only_to_drawn_regions = get_option( 'lpac_ship_only_to_drawn_regions' );

		// If the selected location does not fall within any drawn region and the option to only ship to drawn regions is turned on
		// Return an empty array for rates to prevent checkout
		if ( empty( $customer_detected_region_costs ) && $ship_only_to_drawn_regions === 'yes' ) {

			add_filter(
				'woocommerce_shipping_may_be_available_html',
				function() {
					$text = get_option( 'lpac_no_shipping_method_available_text' );
					return empty( $text ) ? __( 'Unfortunately, we do not currently ship to your region.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
				}
			);

			add_filter(
				'woocommerce_no_shipping_available_html',
				function() {
					$text = get_option( 'lpac_no_shipping_method_available_text' );
					return empty( $text ) ? __( 'Unfortunately, we do not currently ship to your region.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
				}
			);

			// If we have local pickup rates available, then return only them, if not then return no rates at all (which prevents checkout)
			return FunctionsHelperPro::getUnselectedRates( $rates ) ?: array();
		}

		if ( empty( $customer_detected_region_costs ) ) {
			return $rates;
		}

		$use_highest_region_cost = apply_filters( 'lpac_use_highest_region_cost', true );

		$region_cost = ( $use_highest_region_cost ) ? max( $customer_detected_region_costs ) : min( $customer_detected_region_costs );
		$region_cost = apply_filters( 'kikote_region_shipping_cost', (float) $region_cost, $customer_detected_region_costs, WC() );

		$allowed_shipping_method_ids = Shipping_Settings::costByRegionAllowedShippingMethods();

		if ( Shipping_Settings::enableFreeShippingForRegionsOption() ) {
			// Set region cost to 0 if customer cart total qualifies for free shipping.
			if ( $this->checkIfRegionFreeShippingApplies( $customer_detected_regions ) ) {
				$region_cost = 0;
			}
		}

		foreach ( $rates as $rate ) {

			$shipping_method_id = $rate->instance_id;

			if ( in_array( $shipping_method_id, $allowed_shipping_method_ids ) ) {
				/*
				* Adds the base shipping cost set in WooCommerce by the admin to the shipping cost calculated from the distance matrix API
				* Then sets the shipping cost at checkout to the new value calculated.
				*/
				$rate->cost = $rate->cost + $region_cost;

				// When shipping cost is $0, set shipping cost as free.
				if ( empty( $rate->cost ) ) {
					$rate->label = __( 'Free Shipping', 'woocommerce' );
				}

				if ( Shipping_Settings::costByRegionTaxable() ) {
					$taxes       = FunctionsHelperPro::calculateShippingCostTaxes( (float) $region_cost );
					$rate->taxes = $taxes;
				}
			}
		}

		return $rates;
	}

	/**
	 * Adjust the shipping rate based on the region.
	 *
	 * @param array $rates
	 * @return array
	 * @since 1.8.2
	 */
	public function getCustomerRegionCost( array $rates ) : array {
		$rates = $this->setCustomerRegionCost( $rates );
		return FunctionsHelperPro::removeEmptyKikoteShippingMethodsFromRates( $rates, 'cbr' );
	}

}

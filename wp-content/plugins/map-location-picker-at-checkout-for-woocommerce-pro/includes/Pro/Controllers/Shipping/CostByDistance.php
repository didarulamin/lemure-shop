<?php
/**
 * Class to hold cost by distance shipping workflow methods.
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
use Lpac\Helpers\Utilities;
use Lpac\Pro\Helpers\Functions as FunctionsHelperPro;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;
use Lpac\Pro\Models\Plugin_Settings\StoreLocations;

/**
 * Class with methods that handle cost by distance feature.
 *
 * @package Lpac\Pro\Controllers
 * @since 1.7.5
 */
class CostByDistance {

	/**
	 * The unit of measurement for distance. Whether Kilometer or Mile.
	 *
	 * @var string
	 * @since 1.6.3
	 */
	private string $distance_unit = '';

	/**
	 * The detected range that the customer falls in.
	 *
	 * @var string
	 * @since 1.6.6
	 */
	private string $customer_detected_range = '';

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.7.5
	 */
	public function __construct() {
		$this->distance_unit = get_option( 'lpac_distance_matrix_distance_unit', 'km' );
	}

	/**
	 * Get the customer coordinates selected on the map at checkout.
	 *
	 * @since 1.6.0
	 * @return array
	 */
	private function getCustomerCoordinates(): array {
		$cords = WC()->session->get( 'lpac_customer_coordinates' ) ?? array();
		return (array) $cords;
	}

	/**
	 * Get the store id that the user would like the order to be shipped from.
	 *
	 * @since 1.6.0
	 * @return string
	 */
	private function getSelectedStoreID(): string {
		return WC()->session->get( 'lpac_order__origin_store' ) ?: '';
	}

	/**
	 * Get the distance in Kilometers from the Google Distance Matrix API.
	 *
	 * @param array $destination_cords
	 * @param array $origin_cords Set origin coordinates that should be used.
	 * @return mixed
	 */
	private function getMatrixAPIValues( array $destination_cords, string $origin_cords = '' ) {

		$api_key = get_option( 'lpac_distance_matrix_api_key' );

		/**
		 * If the store selector is present on the checkout page, then use it as what sets the origin.
		 */
		if ( StoreLocations::originFromStoreSelectorEnabled() && empty( $origin_cords ) ) {
			if ( ! empty( $this->getSelectedStoreID() ) ) {
				$store_details = Utilities::getStoreDetailsFromID( $this->getSelectedStoreID() );
				$origin_cords  = $store_details['store_cords_text'] ?? '';
			}
		} else {
			$origin_cords = $origin_cords ?: get_option( 'lpac_distance_matrix_store_origin_cords' );
		}

		if ( empty( $origin_cords ) ) {
			return;
		}

		$latitude                    = $destination_cords['latitude'];
		$longitude                   = $destination_cords['longitude'];
		$formatted_destination_cords = $latitude . ',' . $longitude;
		$travel_mode                 = get_option( 'lpac_distance_matrix_travel_mode', 'driving' );

		$base_url = 'https://maps.googleapis.com/maps/api/distancematrix/json';

		$url = add_query_arg(
			array(
				'key'          => $api_key,
				'origins'      => $origin_cords,
				'destinations' => $formatted_destination_cords,
				'mode'         => $travel_mode,
			),
			$base_url
		);

		$response = wp_safe_remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return;
		}

		$response_body = wp_remote_retrieve_body( $response );

		if ( is_wp_error( $response_body ) ) {
			return;
		}

		$response_decoded = json_decode( $response_body, true );

		$status = $response_decoded['status'];

		if ( $status !== 'OK' ) {
			return;
		}

		$distance_m = $response_decoded['rows'][0]['elements'][0]['distance']['value'] ?? '';
		$duration   = $response_decoded['rows'][0]['elements'][0]['duration']['text'] ?? '';

		if ( empty( $distance_m ) ) {
			return;
		}

		$distance = Utilities::getKilometers( $distance_m );

		// Convert Kilometers to Miles if needed
		if ( $this->distance_unit === 'mile' ) {
			$distance = Utilities::getMilesFromKilometers( $distance );
		}

		return array(
			'distance' => (float) $distance,
			'duration' => $duration,
		);
	}

	/**
	 * Edit the Shipping method label when using the cost by distance feature.
	 *
	 * @param string $label
	 * @return string
	 */
	private function getCostByDistanceLabel( float $distance, string $label ) : string {

		$enabled = get_option( 'lpac_show_distance_unit_cost_in_checkout' );

		if ( 'yes' !== $enabled ) {
			return $label;
		}

		$debug_distance = get_option( 'lpac_debug_shipping_distance' );

		$unit = $this->distance_unit;

		$cost_by_distance_standard_rate = wc_price( Shipping_Settings::get_cost_by_distance_standard_rate() );

		if ( Shipping_Settings::costByDistanceStandardEnabled() ) {
			$label = $label . " ($cost_by_distance_standard_rate/$unit)";
		} elseif ( Shipping_Settings::costByDistanceRangeEnabled() ) {
			$label = $label . ' (' . $this->customer_detected_range . ')';
		} else { // Cost by Store Distance
			$from_store_text = apply_filters( 'kikote_cost_by_distance_from_store_text', __( 'Origin', 'map-location-picker-at-checkout-for-woocommerce' ) );
			$store_name      = Utilities::getStoreDetailsFromID( $this->getSelectedStoreID() )['store_name_text'] ?? '';
			$label           = $label . ' (' . $from_store_text . ': ' . $store_name . ')';
		}

		if ( $debug_distance === 'yes' && current_user_can( 'manage_options' ) ) {
			$free_shipping_debug_label    = '';
			$selected_store_distance_cost = '';

			if ( Shipping_Settings::costByDistanceStandardEnabled() ) {
				$free_shipping_debug_label = ', ' . __( 'Free Shipping Distance', 'map-location-picker-at-checkout-for-woocommerce' ) . ': ' . Shipping_Settings::get_free_shipping_distance() . $unit;
			}

			if ( Shipping_Settings::costByStoreDistanceEnabled() ) {
				$store_location_pricing = Shipping_Settings::getCostByStoreDistancePricing();
				$store_location_ids     = array_column( $store_location_pricing, 'store_location_id_select' );
				$key                    = array_search( $this->getSelectedStoreID(), $store_location_ids, true );

				if ( '' !== $key ) {
					$store_shipping_price         = $store_location_pricing[ $key ]['store_price_text'] ?? '';
					$selected_store_distance_cost = ', ' . __( 'Store Distance Unit Cost', 'map-location-picker-at-checkout-for-woocommerce' ) . ': ' . $store_shipping_price;
				}
			}

			$label = $label . ' [' . __( 'Distance', 'map-location-picker-at-checkout-for-woocommerce' ) . ': ' . $distance . $unit . $selected_store_distance_cost . $free_shipping_debug_label . ']';
		}

		return $label;
	}

	/**
	 * Orchestrate which Cost by Distance feature to use.
	 *
	 * @param array $rates
	 * @param mixed $package
	 * @return array $rates
	 * @since 1.8.0
	 */
	public function orchestrateCostByDistance( array $rates, $package ) {

		$allowed_shipping_method_ids = Shipping_Settings::costByDistanceAllowedShippingMethods();

		if ( empty( $allowed_shipping_method_ids ) ) {
			return $rates;
		}

		if ( Shipping_Settings::costByDistanceStandardEnabled() ) {
			$rates = $this->setCostByDistanceStandard( $rates, $package );
		} elseif ( Shipping_Settings::costByDistanceRangeEnabled() ) {
			$rates = $this->setCostByDistanceRange( $rates, $package );
		} else {
			$rates = $this->setCostByStoreDistance( $rates, $package );
		}

		return FunctionsHelperPro::removeEmptyKikoteShippingMethodsFromRates( $rates, 'cbd' );
	}

	/**
	 * Adjust the shipping rate based on the distance.
	 *
	 * @param array $rates
	 * @param array $package
	 * @return array $rates
	 * @since 1.8.0
	 */
	private function setCostByDistanceStandard( array $rates, $package ) : array {

		$coordinates = $this->getCustomerCoordinates();

		if ( empty( $coordinates['latitude'] ) || empty( $coordinates['longitude'] ) ) {
			return $rates;
		}

		$original_distance = $this->getMatrixAPIValues( $coordinates )['distance'] ?? '';
		$duration          = $this->getMatrixAPIValues( $coordinates )['duration'] ?? '';

		if ( empty( $original_distance ) ) {
			return $rates;
		}

		$max_shipping_distance = Shipping_Settings::get_max_shipping_distance();

		// Max shipping distance should not apply to the cost by distance range feature because it has it's own limit workflow
		if ( Shipping_Settings::limit_shipping_distance() && ! empty( $max_shipping_distance ) && 'yes' !== get_option( 'lpac_enable_cost_by_distance_range' ) ) {

			// If the selected location falls beyond maximum allowed shipping distance then return an empty array for rates to prevent checkout
			if ( $original_distance > $max_shipping_distance ) {

				add_filter(
					'woocommerce_shipping_may_be_available_html',
					function() {
						$text = get_option( 'lpac_distance_cost_no_shipping_method_available_text' );
						return empty( $text ) ? __( 'Unfortunately, we do not currently ship this far.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
					},
				);

				add_filter(
					'woocommerce_no_shipping_available_html',
					function() {
						$text = get_option( 'lpac_distance_cost_no_shipping_method_available_text' );
						return empty( $text ) ? __( 'Unfortunately, we do not currently ship this far.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
					},
				);

				return FunctionsHelperPro::getUnselectedRates( $rates ) ?: array();
			}
		}

		$distance_cost = Shipping_Settings::get_cost_by_distance_standard_rate();
		$distance      = $original_distance;

		// Subtract free shipping distance from total distance if option enabled.
		if ( Shipping_Settings::substract_free_shipping_distance() ) {
			$free_shipping_distance = Shipping_Settings::get_free_shipping_distance();
			$distance               = max( ( $original_distance - $free_shipping_distance ), 0 );
		}

		$shipping_cost = $distance * $distance_cost;
		$distance_data = array(
			'shipping_cost' => $shipping_cost,
			'distance'      => $distance,
			'distance_cost' => $distance_cost,
			'duration'      => $duration,
		);
		$shipping_cost = apply_filters( 'kikote_distance_shipping_cost', $shipping_cost, $package, WC(), $distance_data );

		$allowed_shipping_method_ids = Shipping_Settings::costByDistanceAllowedShippingMethods();

		// Update the shipping charge based on our calculated shipping cost from the distance matrix API
		foreach ( $rates as $rate ) {

			$shipping_method_id = $rate->instance_id;

			if ( in_array( $shipping_method_id, $allowed_shipping_method_ids ) ) {
				/*
				* Adds the base shipping cost set in WooCommerce by the admin to the shipping cost calculated from the distance matrix API
				* Then sets the shipping cost at checkout to the new value calculated.
				*/
				$rate->cost = $rate->cost + $shipping_cost;

				$rate->label = $this->getCostByDistanceLabel( $original_distance, $rate->label );

				// We need to check if this option is enabled or else we'd always get free shipping for distances that fall below the free shipping allowance after the subtraction has taken place.
				if ( false === Shipping_Settings::substract_free_shipping_distance() ) {
					// If the user is within the free shipping range, set their shipping cost as free.
					if ( $distance <= Shipping_Settings::get_free_shipping_distance() ) {
						$rate->cost = 0.00;
					}
				}

				// When shipping cost is 0, set shipping cost as free.
				if ( empty( $rate->cost ) ) {
					$rate->label = __( 'Free Shipping', 'woocommerce' );
				}

				if ( Shipping_Settings::costByDistanceTaxable() ) {
					$taxes       = FunctionsHelperPro::calculateShippingCostTaxes( (float) $shipping_cost );
					$rate->taxes = $taxes;
				}

				WC()->session->set( 'lpac_customer_distance', false );
				WC()->session->set( 'lpac_customer_distance', $distance );

				WC()->session->set( 'lpac_customer_distance_unit', false );
				WC()->session->set( 'lpac_customer_distance_unit', $this->distance_unit );

				WC()->session->set( 'lpac_customer_distance_duration', false );
				WC()->session->set( 'lpac_customer_distance_duration', $duration );
			}
		}

		return $rates;
	}


	/**
	 * Get the cost for shipping based on the range the customer falls inside.
	 *
	 * @since 1.6.3
	 * @param float $distance
	 * @param array $rates
	 * @return mixed
	 */
	private function getCostByDistanceRange( float $distance, array $rates ) {

		// We need to do this or else it would basically be impossible to get a match in the ranges.
		$customer_distance = number_format( $distance, 1 );
		$customer_distance = str_replace( '.0', '', $customer_distance ); // Our range would not have .0 values, so here we're dropping any .0 example 1.0, 2.0 etc

		$ranges = get_option( 'lpac_cost_by_distance_range_rangelist', array() );

		if ( empty( $ranges ) ) {
			return null;
		}

		$shipping_cost = null;
		$last_key      = array_key_last( $ranges );
		$last_range    = $ranges[ $last_key ];

		$last_start_range                              = $last_range['start_range_text'] ?? '';
		$last_end_range                                = $last_range['end_range_text'] ?? '';
		$last_range_price                              = $last_range['range_price_text'] ?? '';
		$last_range_should_calculate_per_distance_unit = (bool) $last_range['should_calculate_per_distance_unit_checkbox'] ?? '';
		$customer_detected_range_array                 = array();

		/**
		 * Return early with the shipping price if the customer falls beyond our ranges and the store
		 * owner hasn't set an end range.
		 */
		if ( $last_end_range === '' && $customer_distance >= $last_start_range ) {
			$this->customer_detected_range = apply_filters( 'lpac_distance_range_exceeded_prefix', '>= ' ) . $last_start_range . ' ' . $this->distance_unit;

			if ( $last_range_should_calculate_per_distance_unit ) {
				$shipping_cost = $distance * $last_range_price;
			} else {
				$shipping_cost = $last_range_price;
			}
			$customer_detected_range_array = array(
				'start_range'   => $last_start_range,
				'end_range'     => null,
				'shipping_cost' => (float) $shipping_cost,
			);

			return $customer_detected_range_array;
		}

		if ( $last_end_range === '' ) {
			unset( $ranges[ $last_key ] );
		}

		foreach ( $ranges as $key => $range_data ) {

			$start = number_format( $range_data['start_range_text'], 1 );
			$end   = number_format( $range_data['end_range_text'], 1 );
			$price = $range_data['range_price_text'];

			$range = range( $start, $end, .1 );
			// This is needed so we don't run into floating point precision issues
			$range = array_map( 'strval', $range );

			if ( in_array( $customer_distance, $range, true ) ) {
				$should_calculate_per_distance_unit = $range_data['should_calculate_per_distance_unit_checkbox'] ?? '';

				if ( $should_calculate_per_distance_unit ) { // In this case we calculate the distance by the set price
					$shipping_cost = $distance * $price;
				} else { // In this case we simply return the fixed price
					$shipping_cost = $price;
				}

				$this->customer_detected_range = $range_data['start_range_text'] . '-' . $range_data['end_range_text'] . ' ' . $this->distance_unit;
				$customer_detected_range_array = array(
					'start_range'   => $range_data['start_range_text'],
					'end_range'     => $range_data['end_range_text'],
					'shipping_cost' => (float) $shipping_cost,
				);
				break;
			}
		}

		return $customer_detected_range_array;
	}

	/**
	 * Set the price that the customer should pay based on the distance range they fall into.
	 *
	 * @param array $rates
	 * @param array $package
	 * @return array $rates
	 * @since 1.8.0
	 */
	private function setCostByDistanceRange( array $rates, $package ): array {

		$coordinates = $this->getCustomerCoordinates();

		if ( empty( $coordinates['latitude'] ) || empty( $coordinates['longitude'] ) ) {
			return $rates;
		}

		$distance = (float) ( $this->getMatrixAPIValues( $coordinates )['distance'] ?? '' );
		$duration = $this->getMatrixAPIValues( $coordinates )['duration'] ?? '';

		if ( empty( $distance ) ) {
			return $rates;
		}

		$distance_range_data             = $this->getCostByDistanceRange( $distance, $rates );
		$distance_range_data['distance'] = $distance;
		$distance_range_data['duration'] = $duration;
		$shipping_cost                   = $distance_range_data['shipping_cost'];

		if ( null === $shipping_cost ) {

			$unselected_shipping_methods = FunctionsHelperPro::getUnselectedRates( $rates );

			// If we have shipping methods that have not been attached to Kikote available, then return only them, if not then return no rates at all (which prevents checkout)
			if ( empty( $unselected_shipping_methods ) ) {

				add_filter(
					'woocommerce_shipping_may_be_available_html',
					function() {
						$text = get_option( 'lpac_distance_cost_no_shipping_method_available_text' );
						return empty( $text ) ? __( 'Unfortunately, we do not currently ship this far.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
					},
					11
				);

				add_filter(
					'woocommerce_no_shipping_available_html',
					function() {
						$text = get_option( 'lpac_distance_cost_no_shipping_method_available_text' );
						return empty( $text ) ? __( 'Unfortunately, we do not currently ship this far.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
					},
					11
				);

				return array();
			} else {
				return $unselected_shipping_methods;
			}
		}

		$shipping_cost               = apply_filters( 'kikote_distance_range_shipping_cost', $shipping_cost, $package, WC(), $distance_range_data );
		$allowed_shipping_method_ids = Shipping_Settings::costByDistanceAllowedShippingMethods();

		// Update the shipping charge based on our calculated shipping cost from the distance matrix API
		foreach ( $rates as $rate ) {

			$shipping_method_id = $rate->instance_id;

			if ( in_array( $shipping_method_id, $allowed_shipping_method_ids ) ) {
				/*
				* Adds the base shipping cost set in WooCommerce by the admin to the shipping cost calculated from the distance matrix API
				* Then sets the shipping cost at checkout to the new value calculated.
				*/
				$rate->cost = $rate->cost + $shipping_cost;

				$rate->label = $this->getCostByDistanceLabel( $distance, $rate->label );

				// When shipping cost is 0, set shipping cost as free.
				if ( empty( $rate->cost ) ) {
					$rate->label = __( 'Free Shipping', 'woocommerce' );
				}

				if ( Shipping_Settings::costByDistanceTaxable() ) {
					$taxes       = FunctionsHelperPro::calculateShippingCostTaxes( (float) $shipping_cost );
					$rate->taxes = $taxes;
				}

				WC()->session->set( 'lpac_customer_distance', false );
				WC()->session->set( 'lpac_customer_distance', $distance );

				WC()->session->set( 'lpac_customer_distance_unit', false );
				WC()->session->set( 'lpac_customer_distance_unit', $this->distance_unit );

				WC()->session->set( 'lpac_customer_distance_duration', false );
				WC()->session->set( 'lpac_customer_distance_duration', $duration );

			}
		}

		return $rates;
	}

	/**
	 * Adjust the shipping rate based on the distance.
	 *
	 * @param array $rates
	 * @param array $package
	 * @return array $rates
	 * @since 1.8.0
	 */
	private function setCostByStoreDistance( array $rates, $package ) : array {

		$coordinates = $this->getCustomerCoordinates();

		if ( empty( $coordinates['latitude'] ) || empty( $coordinates['longitude'] ) ) {
			return $rates;
		}

		$selected_store_details = Utilities::getStoreDetailsFromID( $this->getSelectedStoreID() );

		if ( empty( $selected_store_details ) ) {
			return $rates;
		}

		$origin_store_cords = $selected_store_details['store_cords_text'];
		$distance_matrix    = $this->getMatrixAPIValues( $coordinates, $origin_store_cords );

		$distance = $distance_matrix['distance'] ?? '';
		$duration = $distance_matrix['duration'] ?? '';

		if ( empty( $distance ) ) {
			return $rates;
		}

		$store_location_pricing = Shipping_Settings::getCostByStoreDistancePricing();

		if ( empty( $store_location_pricing ) ) {
			return $rates;
		}

		$selected_store_pricing_details = '';
		foreach ( $store_location_pricing as $key => $store_pricing_details ) {
			if ( $store_pricing_details['store_location_id_select'] === $this->getSelectedStoreID() ) {
				$selected_store_pricing_details = $store_pricing_details;
				break;
			}
		}

		if ( empty( $selected_store_pricing_details ) ) {
			return $rates;
		}

		$distance_cost       = $selected_store_pricing_details['store_price_text'] ?? '';
		$shipping_cost       = $distance * $distance_cost;
		$store_distance_data = array(
			'shipping_cost' => $shipping_cost,
			'distance'      => $distance,
			'distance_cost' => $distance_cost,
			'duration'      => $duration,
		);
		$shipping_cost       = apply_filters( 'kikote_store_distance_shipping_cost', $shipping_cost, $package, WC(), $store_distance_data );

		$allowed_shipping_method_ids = Shipping_Settings::costByDistanceAllowedShippingMethods();

		// Update the shipping charge based on our calculated shipping cost from the distance matrix API
		foreach ( $rates as $rate ) {

			$shipping_method_id = $rate->instance_id;

			if ( in_array( $shipping_method_id, $allowed_shipping_method_ids ) ) {
				/*
				* Adds the base shipping cost set in WooCommerce by the admin to the shipping cost calculated from the distance matrix API
				* Then sets the shipping cost at checkout to the new value calculated.
				*/
				$rate->cost = $rate->cost + $shipping_cost;

				$rate->label = $this->getCostByDistanceLabel( $distance, $rate->label );

				// When shipping cost is 0, set shipping cost as free.
				if ( empty( $rate->cost ) ) {
					$rate->label = __( 'Free Shipping', 'woocommerce' );
				}

				if ( Shipping_Settings::costByDistanceTaxable() ) {
					$taxes       = FunctionsHelperPro::calculateShippingCostTaxes( (float) $shipping_cost );
					$rate->taxes = $taxes;
				}

				WC()->session->set( 'lpac_customer_distance', false );
				WC()->session->set( 'lpac_customer_distance', $distance );

				WC()->session->set( 'lpac_customer_distance_unit', false );
				WC()->session->set( 'lpac_customer_distance_unit', $this->distance_unit );

				WC()->session->set( 'lpac_customer_distance_duration', false );
				WC()->session->set( 'lpac_customer_distance_duration', $duration );
			}
		}

		return $rates;
	}

}

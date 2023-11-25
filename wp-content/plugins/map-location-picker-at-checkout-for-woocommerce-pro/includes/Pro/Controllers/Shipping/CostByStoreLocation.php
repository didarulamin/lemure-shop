<?php
/**
 * File responsible for Cost by store location class and methods.
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

use Lpac\Pro\Helpers\Functions as FunctionsHelperPro;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;

/**
 * Class responsible for defining cost by store location methods.
 *
 * @package Lpac\Pro\Controllers\Shipping
 * @since 1.7.5
 */
class CostByStoreLocation {

	/**
	 * Get the store id that the user would like the order to be shipped from.
	 *
	 * @since 1.6.0
	 * @return string
	 */
	private function getSelectedStoreID() {
		return WC()->session->get( 'lpac_order__origin_store' );
	}

	/**
	 * Set the rates which the customer should pay based on the store location they select.
	 *
	 * @param array $rates
	 * @return array
	 * @since 1.8.2
	 */
	private function setStoreLocationCost( array $rates ) : array {
		$store_location_pricing = Shipping_Settings::getCostByStoreLocationPricing();

		if ( empty( $store_location_pricing ) ) {
			return $rates;
		}

		$selected_store_id = $this->getSelectedStoreID();

		if ( empty( $selected_store_id ) ) {
			return $rates;
		}

		$currently_selected_store_details = array();
		foreach ( $store_location_pricing as $key => $store_details ) {
			if ( $selected_store_id === $store_details['store_location_id_select'] ) {
				$currently_selected_store_details = $store_details;
			}
		}

		$store_shipping_cost = $currently_selected_store_details['store_price_text'] ?? 0;
		$store_shipping_cost = apply_filters( 'kikote_store_shipping_cost', (float) $store_shipping_cost, $currently_selected_store_details, WC() );

		$allowed_shipping_method_ids = Shipping_Settings::costByStoreLocationAllowedShippingMethods();

		foreach ( $rates as $rate ) {

			$shipping_method_id = $rate->instance_id;

			if ( in_array( $shipping_method_id, $allowed_shipping_method_ids ) ) {
				/*
				* Adds the base shipping cost set in WooCommerce by the admin to the shipping cost calculated.
				* Then sets the shipping cost at checkout to the new value calculated.
				*/
				$rate->cost = $rate->cost + $store_shipping_cost;

				// When cost is 0, set shipping cost as free.
				if ( empty( $rate->cost ) ) {
					$rate->label = __( 'Free Shipping', 'woocommerce' );
				}

				if ( Shipping_Settings::costByStoreLocationTaxable() ) {
					$taxes       = FunctionsHelperPro::calculateShippingCostTaxes( (float) $store_shipping_cost );
					$rate->taxes = $taxes;
				}
			}
		}

		return $rates;
	}

	/**
	 * Adjust the shipping rate based on the selected origin store location.
	 *
	 * @param array $rates
	 * @return array
	 * @since 1.8.2
	 */
	public function getStoreLocationCost( array $rates ) : array {
		$rates = $this->setStoreLocationCost( $rates );
		return FunctionsHelperPro::removeEmptyKikoteShippingMethodsFromRates( $rates, 'cbsl' );
	}

}

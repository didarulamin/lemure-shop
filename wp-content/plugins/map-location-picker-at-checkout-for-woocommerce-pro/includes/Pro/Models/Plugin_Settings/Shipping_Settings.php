<?php
/**
 * Get the shipping settings of the plugin..
 *
 * Author:          Uriahs Victor
 * Created on:      03/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.7.0
 * @package Models
 */

namespace Lpac\Pro\Models\Plugin_Settings;

use Lpac\Models\Base_Model;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Shipping_Settings extends Base_Model {

	/**
	 * Get saved Shipping Regions.
	 *
	 * @return array
	 * @since 1.7.0
	 */
	public static function getShippingRegions(): array {
		return get_option( 'lpac_shipping_regions', array() );
	}

	/**
	 * Get the "Cost by Region" option (to know if its activated or not).
	 *
	 * @return bool
	 * @since 1.8.9
	 */
	public static function costByRegionEnabled(): bool {
		$value = get_option( 'lpac_shipping_cost_by_region_enabled' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Cost by Distance Standard" option (to know if its activated or not).
	 *
	 * @return bool
	 * @since 1.8.2
	 */
	public static function costByDistanceEnabled(): bool {
		$value = get_option( 'lpac_enable_shipping_cost_by_distance_feature' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Cost by Distance Standard" option (to know if its activated or not).
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public static function costByDistanceStandardEnabled(): bool {
		$value = get_option( 'lpac_enable_cost_by_distance_standard' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Cost by Distance Range" option (to know if its activated or not).
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public static function costByDistanceRangeEnabled(): bool {
		$value = get_option( 'lpac_enable_cost_by_distance_range' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Cost by Store Distance" option (to know if its activated or not).
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public static function costByStoreDistanceEnabled(): bool {
		$value = get_option( 'lpac_enable_cost_by_store_distance' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Cost by Store Location" option (to know if its activated or not).
	 *
	 * @return bool
	 * @since 1.8.6
	 */
	public static function costByStoreLocationEnabled(): bool {
		$value = get_option( 'lpac_enable_cost_by_store_location' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Shipping Methods" option.
	 *
	 * The methods that cost by region should be applied to.
	 *
	 * @return array
	 * @since 1.8.2
	 */
	public static function costByRegionAllowedShippingMethods(): array {
		return get_option( 'lpac_shipping_regions_shipping_methods', array() );
	}

	/**
	 * Get the "Shipping Methods" option.
	 *
	 * The methods that cost by distance should be applied to.
	 *
	 * @return array
	 * @since 1.8.0
	 */
	public static function costByDistanceAllowedShippingMethods(): array {
		return get_option( 'lpac_distance_matrix_shipping_methods', array() );
	}

	/**
	 * Get the "Shipping Methods" option.
	 *
	 * The methods that cost by store location should be applied to.
	 *
	 * @return array
	 * @since 1.8.2
	 */
	public static function costByStoreLocationAllowedShippingMethods(): array {
		return get_option( 'lpac_cost_by_store_location_shipping_methods', array() );
	}

	/**
	 * Get the "Free Shipping for Distance" option value.
	 *
	 * @return float
	 * @since 1.8.0
	 */
	public static function get_cost_by_distance_standard_rate() {
		return (float) get_option( 'lpac_distance_matrix_cost_per_unit', 0 );
	}

	/**
	 * Get the "Limit free shipping distance" option value.
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public static function limit_shipping_distance() {
		$value = get_option( 'lpac_limit_shipping_distance' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Get the "Maximum Distance" option value.
	 *
	 * @return mixed
	 * @since 1.8.0
	 */
	public static function get_max_shipping_distance() {
		return get_option( 'lpac_max_shipping_distance' );
	}

	/**
	 * Get the "Free Shipping for Distance" option value.
	 *
	 * @return float
	 * @since 1.8.0
	 */
	public static function get_free_shipping_distance() {
		return (float) get_option( 'lpac_max_free_shipping_distance', '0.00' );
	}

	/**
	 * Get the "Subtract Free Shipping Distance From Total Distance" option value.
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public static function substract_free_shipping_distance(): bool {
		$value = get_option( 'lpac_subtract_free_shipping_distance' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Check if "Shipping Restriction" setting should apply to Local Pickup shipping methods.
	 *
	 * @return bool
	 * @since 1.8.8
	 */
	public static function enableShippingRestrictionsLocalPickup(): bool {
		$value = get_option( 'lpac_enable_shipping_restrictions_local_pickup' );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Check  if the "Free Shipping for Regions" feature is enabled.
	 *
	 * @return array
	 * @since 1.8.2
	 */
	public static function enableFreeShippingForRegionsOption(): bool {
		$value = get_option( 'lpac_enable_free_shipping_for_regions', false );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );

	}

	/**
	 * Get the Free Shipping for region rules that have been created.
	 *
	 * @return array
	 * @since 1.8.2
	 */
	public static function getCostByRegionFreeShippingRules(): array {
		return get_option( 'lpac_regions_free_shipping', array() );
	}

	/**
	 * Get the store location pricing settings from the Cost by Store Distance feature.
	 *
	 * @since 1.6.0
	 * @since 1.8.6 Renamed method and added to settings class.
	 * @return array
	 */
	public static function getCostByStoreDistancePricing(): array {
		return get_option( 'lpac_cost_by_store_distance_delivery_prices', array() );
	}

	/**
	 * Get the store location pricing settings from the Cost by Store Location feature.
	 *
	 * @since 1.6.0
	 * @since 1.8.6 Renamed method and added to settings class.
	 * @return array
	 */
	public static function getCostByStoreLocationPricing(): array {
		return get_option( 'lpac_cost_by_store_location_delivery_prices', array() );
	}

	/**
	 * Check if the Cost by Distance shipping fees should be taxable.
	 *
	 * @return bool
	 * @since 1.8.6
	 */
	public static function costByDistanceTaxable(): bool {
		$value = get_option( 'lpac_cost_by_distance_taxable', false );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Check if the Cost by Store Location shipping fees should be taxable.
	 *
	 * @return bool
	 * @since 1.8.6
	 */
	public static function costByStoreLocationTaxable(): bool {
		$value = get_option( 'lpac_cost_by_store_location_taxable', false );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Check if the Cost by Region shipping fees should be taxable.
	 *
	 * @return bool
	 * @since 1.8.6
	 */
	public static function costByRegionTaxable(): bool {
		$value = get_option( 'lpac_cost_by_region_taxable', false );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}

}

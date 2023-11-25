<?php

/**
 * Orchestrates the Pro admin settings operations.
 *
 * Author:          Uriahs Victor
 * Created on:      17/10/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.0
 * @package Lpac
 */

namespace Lpac\Pro\Controllers;

use Lpac\Controllers\Admin_Settings_Controller as Lite_Admin_Settings_Controller;
use Lpac\Pro\Controllers\Draw_Shipping_Region;
use Lpac\Helpers\Functions as Functions_Helper;

/**
 * The Admin_Settings_Controller class.
 */
class Admin_Settings_Controller extends Lite_Admin_Settings_Controller {

	/**
	 * Sanitize the map anchor points option before saving.
	 *
	 * @param string $value
	 * @param array  $option
	 * @param string $raw_value
	 * @return string
	 */
	public function sanitize_map_anchor_points( $value, $option, $raw_value ) {

		// Remove letters from input, allow commas
		$value = preg_replace( '/[^0-9,]/', '', $value );
		$value = sanitize_text_field( $value );
		$value = trim( $value );

		// If our value doesn't have the separator it's most likely invalid.
		if ( strpos( $value, ',' ) === false ) {

			$marker_icon_image = get_option( 'lpac_map_marker_icon', false );

			if ( empty( $marker_icon_image ) ) {
				return '';
			}

			$attachment_id   = attachment_url_to_postid( $marker_icon_image );
			$attachment_meta = wp_get_attachment_metadata( $attachment_id, true );

			if ( empty( $attachment_meta ) ) {
				return '';
			}

			$width  = (int) $attachment_meta['width'] / 2;
			$height = (int) $attachment_meta['height'] + 3;
			$value  = $width . ',' . $height;

		}

		return $value;
	}

	/**
	 * Sanitize the map anchor points option before saving.
	 *
	 * @param string $value
	 * @param array  $option
	 * @param string $raw_value
	 * @return string
	 */
	public function sanitize_distance_matrix_origin_coordinates( $value, $option, $raw_value ) {

		$value = preg_replace( '/[^0-9,.-]/', '', $value );
		$value = sanitize_text_field( $value );
		$value = trim( $value, ' ,' ); // Remove spaces or commas infront and after value

		return $value;

	}

	/**
	 * Update shipping regions when edited on map.
	 *
	 * @param mixed  $value
	 * @param string $option
	 * @param mixed  $raw_value
	 * @return null
	 */
	public function update_saved_shipping_regions( $value, $option, $raw_value ) {
		( new Draw_Shipping_Region() )->update_drawn_shipping_region_setting_handler( $value );
		return null; // Return null so the setting isnt actually saved.
	}

	/*
	* Creates the JS options needed by the base-map JS file to render the google map.
	*
	* @return void
	*/
	public function setup_export_settings_mapOptions() {

		// TODO create a method in PRO Functions_Helper to get options for PRO map settings
		$options = Functions_Helper::set_map_options();

		$data = array(
			'lpac_map_default_latitude'  => $options['latitude'],
			'lpac_map_default_longitude' => $options['longitude'],
			'lpac_map_zoom_level'        => $options['zoom_level'],
			'lpac_map_clickable_icons'   => $options['clickable_icons'] === 'yes' ? true : false,
			'lpac_map_background_color'  => $options['background_color'],
		);

		$map_options = json_encode( $data );

		$global_variables = <<<JAVASCRIPT
		// Lpac Map Settings
		var mapOptions = $map_options;
JAVASCRIPT;

		// Expose JS variables for usage
		wp_add_inline_script( LPAC_PLUGIN_NAME . '-base-map', $global_variables, 'before' );

	}

	/**
	 * Creates the JS options needed by the base-map JS file to render the google map.
	 *
	 * @return void
	 */
	public function setup_shipping_settings_mapOptions():void {

		// TODO create a method in PRO Functions_Helper to get options for PRO map settings
		$options = Functions_Helper::set_map_options();

		$data = array(
			'lpac_map_default_latitude'  => $options['latitude'],
			'lpac_map_default_longitude' => $options['longitude'],
			'lpac_map_zoom_level'        => $options['zoom_level'],
			'lpac_map_clickable_icons'   => $options['clickable_icons'] === 'yes' ? true : false,
			'lpac_map_background_color'  => $options['background_color'],
		);

		$drawing_strings = array(
			'regionName'       => __( 'Name this region.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'regionCost'       => __( 'Give region a price.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'regionCostUpdate' => __( 'Would you like to change the cost of this region?', 'map-location-picker-at-checkout-for-woocommerce' ),
			'regionColor'      => __( 'Set a Hexadecimal color code for this region (leaving blank will use default region background color).', 'map-location-picker-at-checkout-for-woocommerce' ),
		);

		$drawing_strings = json_encode( $drawing_strings );

		$map_options                      = json_encode( $data );
		$currency_symbol                  = json_encode( html_entity_decode( get_woocommerce_currency_symbol() ) );
		$regions_default_background_color = json_encode( get_option( 'lpac_shipping_regions_default_background_color', '#ff0000' ) );

		$global_variables = <<<JAVASCRIPT
		// Lpac Map Settings
		var mapOptions = $map_options;
		var shopCurrency = $currency_symbol;
		var defaultRegionsBgColor = $regions_default_background_color;
		var drawingLocalizedStrings = $drawing_strings;
JAVASCRIPT;

		// Expose JS variables for usage
		wp_add_inline_script( LPAC_PLUGIN_NAME . '-base-map', $global_variables, 'before' );

	}

	/**
	 * Sanitize price input to drop letters and only accept numbers and fullstops.
	 *
	 * @param array $values
	 * @param array $option
	 * @param array $raw_value
	 * @return array
	 */
	public function sanitize_pricing_inputs( array $values, array $option, array $raw_value ) : array {
		foreach ( $values as $key => &$store_details ) {
			$store_details['store_price_text'] = sanitize_text_field( preg_replace( '/[^0-9.]/', '', $store_details['store_price_text'] ) );
		}
		unset( $store_details );

		return $values;
	}

	/**
	 * Sanitize price input to drop letters and only accept numbers and fullstops.
	 *
	 * @param array $values
	 * @param array $option
	 * @param array $raw_value
	 * @return array
	 */
	public function sanitize_cost_by_region_pricing_inputs( array $values, array $option, array $raw_value ) : array {
		foreach ( $values as $key => &$region_restrictions_data ) {
			$region_restrictions_data['region_min_order_total_text'] = sanitize_text_field( preg_replace( '/[^0-9.]/', '', $region_restrictions_data['region_min_order_total_text'] ) );
			$region_restrictions_data['region_max_order_total_text'] = sanitize_text_field( preg_replace( '/[^0-9.]/', '', $region_restrictions_data['region_max_order_total_text'] ) );
		}
		unset( $region_restrictions_data );

		return $values;
	}

}

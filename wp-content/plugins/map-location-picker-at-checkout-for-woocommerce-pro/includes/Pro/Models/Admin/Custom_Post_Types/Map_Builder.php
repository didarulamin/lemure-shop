<?php
/**
 * Class responsible for saving Map Builder's custom post type data to DB.
 *
 * Author:          Uriahs Victor
 * Created on:      02/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.7.0
 * @package Models
 */

namespace Lpac\Pro\Models\Admin\Custom_Post_Types;

use Lpac\Models\Plugin_Settings\Store_Locations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Model responsible for saving Map Builder maps.
 *
 * @package Lpac\Pro\Models\Admin\Custom_Post_Types
 * @since 1.7.0
 */
class Map_Builder {

	/**
	 * Set our different keys that exist as the 'name' attribute for our settings.
	 *
	 * Whenever we add a new setting/option, we need to add the value that was entered for the 'name' attribute in this array
	 * So that we can pull it from $_POST.
	 *
	 * @return array
	 * @since 1.7.0
	 */
	private function settings_keys() : array {
		return array(
			'shipping_settings' => array(
				'shipping_regions_settings',
				'store_locations',
				'shipping_regions',
			),
			'display_settings'  => array(
				'background_color',
				'clickable_icons',
				'default_coordinates',
				'google_map_id',
				'map_type',
				'height',
				'width',
				'streetview_control',
				'zoom',
			),
		);
	}

	/**
	 * Setup our store locations array for the map shortcode based on the store locations that were selected.
	 *
	 * @param mixed $selected_store_locations
	 * @return array
	 * @since 1.7.0
	 */
	private function setup_store_locations( $selected_store_locations ): array {

		if ( empty( $selected_store_locations ) ) {
			return array();
		}

		$all_store_locations = Store_Locations::getStoreLocations();

		$store_locations = array_filter(
			$all_store_locations,
			function( $value ) use ( $selected_store_locations ) {
				$store_location_id = $value['store_location_id'];
				if ( in_array( $store_location_id, $selected_store_locations ) ) {
					return true;
				}
			}
		);

		return array_values( $store_locations );
	}

	/**
	 * Prepare the settings saved from the builder so that we can save to the Post Meta.
	 *
	 * @return array
	 * @since 1.7.0
	 */
	private function prepare_map_settings(): array {
		$settings = array();
		foreach ( $this->settings_keys() as $key => $setting_key ) {

			if ( is_array( $setting_key ) ) {

				foreach ( $setting_key as $subsetting ) {

					if ( $subsetting === 'store_locations' ) {
						$store_locations                 = $this->setup_store_locations( $_POST[ $subsetting ] ?? array() );
						$settings[ $key ][ $subsetting ] = $store_locations;
						continue;
					}

					// Prevents JS error when no lat and long is entered by the user
					if ( $subsetting === 'default_coordinates' ) {

						$latitude  = $_POST[ $subsetting ]['latitude'] ?? '';
						$longitude = $_POST[ $subsetting ]['longitude'] ?? '';

						if ( empty( $latitude ) ) {
							$latitude = '13.854971186771195';
						}

						if ( empty( $longitude ) ) {
							$longitude = '-60.989275245109134';
						}
						$settings[ $key ][ $subsetting ] = array(
							'latitude'  => $latitude,
							'longitude' => $longitude,
						);

						continue;
					}

					$setting = wp_unslash( $_POST[ $subsetting ] ?? '' );
					if ( empty( $setting ) ) {
						continue;
					}
					$setting = ( is_array( $setting ) ) ? array_map( 'sanitize_text_field', $setting ) : sanitize_text_field( $setting );

					$settings[ $key ][ $subsetting ] = $setting;

				}
			} else {
				$setting                  = wp_unslash( $_POST[ $setting_key ] ?? '' );
				$setting                  = ( is_array( $setting ) ) ? array_map( 'sanitize_text_field', $setting ) : sanitize_text_field( $setting );
				$settings[ $setting_key ] = $setting;
			}
		}
		$settings['shop_currency'] = html_entity_decode( get_woocommerce_currency_symbol() );
		return $settings;
	}

	/**
	 * Save the map details to the DB.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function save_map(): void {
		if ( empty( get_the_ID() ) ) {
			return;
		}
		$settings = $this->prepare_map_settings();
		update_post_meta( get_the_ID(), 'kikote_map_builder_shortcode_settings', $settings );
	}

}

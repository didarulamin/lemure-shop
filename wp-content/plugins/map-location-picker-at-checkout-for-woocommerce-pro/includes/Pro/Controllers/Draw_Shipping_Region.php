<?php
/**
 * Houses controller methods for shipping region drawing.
 *
 * Author:          Uriahs Victor
 * Created on:      16/01/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.4
 * @package Lpac\Pro\Controllers
 */

namespace Lpac\Pro\Controllers;

use Lpac\Pro\Models\Draw_Shipping_Region as Draw_Shipping_Region_Model;
use Lpac\Pro\Models\Shortcodes\Map_Builder\Map_Settings;

/**
 * Class Draw Shipping Zone.
 */
class Draw_Shipping_Region {

	/**
	 * @var Draw_Shipping_Region_Model Class
	 */
	private $shipping_region_model;

	/**
	 * Construction method.
	 *
	 * @return void
	 */
	public function __construct() {
		 $this->shipping_region_model = new Draw_Shipping_Region_Model();
	}

	/**
	 * Get the drawn shipping regions that should show on our custom map.
	 *
	 * @return mixed
	 * @since 1.7.0
	 */
	public function get_shipping_regions_for_shortcode_ajax_handler() {

		if ( is_admin() ) {
			$map_id = $_REQUEST['mapID'];
		} else {
			$map_id = get_the_ID();
		}

		$saved_regions = $this->shipping_region_model->get_saved_regions();

		if ( empty( $saved_regions ) ) {
			wp_send_json_success( 'LPAC: No saved regions found.' );
		}

		$selected_regions = ( new Map_Settings( $map_id ) )->get_selected_drawn_regions();

		$regions_to_plot = array_filter(
			$saved_regions,
			function( $region_array, $key ) use ( $selected_regions ) {
				$region_id = $region_array['id'];
				if ( in_array( $region_id, $selected_regions ) ) {
					return true;
				}
			},
			ARRAY_FILTER_USE_BOTH
		);

		$regions_to_plot         = array_values( $regions_to_plot );
		$default_regions_bgcolor = get_option( 'lpac_shipping_regions_default_background_color', '#ff0000' );

		// TODO add the below logic into its own util function.
		foreach ( $regions_to_plot as $key => &$region ) {
			foreach ( $region as $key => &$details ) {
				if ( $key === 'polygon' ) {
					foreach ( $details as $poly_key => &$poly_detail ) {
						foreach ( $poly_detail as $loc_key => &$loc_val ) {
							$loc_val = (float) $loc_val;
						}
						unset( $loc_val );
					}
					unset( $poly_detail );

				}
				unset( $details );

			}
			// Use the default region color when none is present.
			if ( empty( $region['bgColor'] ) ) {
				$region['bgColor'] = $default_regions_bgcolor;
			}
		}
		unset( $region );

		wp_send_json_success( $regions_to_plot );
	}


	/**
	 * Get saved shipping regions.
	 *
	 * @return mixed
	 */
	public function get_saved_shipping_regions_ajax_handler() {

		$saved_regions = $this->shipping_region_model->get_saved_regions();

		if ( empty( $saved_regions ) ) {
			wp_send_json_success( 'LPAC: No saved regions found.' );
		}

		$default_regions_bgcolor = get_option( 'lpac_shipping_regions_default_background_color', '#ff0000' );

		foreach ( $saved_regions as $key => &$region ) {
			foreach ( $region as $key => &$details ) {
				if ( $key === 'polygon' ) {
					foreach ( $details as $poly_key => &$poly_detail ) {
						foreach ( $poly_detail as $loc_key => &$loc_val ) {
							$loc_val = (float) $loc_val;
						}
						unset( $loc_val );
					}
					unset( $poly_detail );

				}
				unset( $details );

			}
			// Use the default region color when none is present.
			if ( empty( $region['bgColor'] ) ) {
				$region['bgColor'] = $default_regions_bgcolor;
			}
		}
		unset( $region );

		wp_send_json_success( $saved_regions );
	}



	/**
	 * Ajax handler for saving shipping region.
	 *
	 * @return mixed
	 */
	public function save_drawn_shipping_region_ajax_handler() {

			$region_details     = $this->shipping_region_model->get_saved_regions();
			$new_region_details = $_REQUEST['regionDetails'];
			// Sanitize user inputs
			$new_region_details['name'] = sanitize_text_field( $new_region_details['name'] );
			// Create a region ID from the name
			// Eg: region_id_3125
			$new_region_details['id'] = strtolower( str_replace( ' ', '_', $new_region_details['name'] ) ) . '_' . substr( time(), -4 );

			$new_region_details['cost'] = sanitize_text_field( $new_region_details['cost'] );
			$bgColor                    = sanitize_text_field( $new_region_details['bgColor'] );

		if ( ! empty( $bgColor ) ) {
			// If hashtags wasn't added for HEX code, add it.
			if ( strpos( $new_region_details['bgColor'], '#' ) !== 0 ) {
				$new_region_details['bgColor'] = '#' . $bgColor;
			}
		} else {
			$new_region_details['bgColor'] = $bgColor;
		}

		$region_details[] = $new_region_details;
		$saved            = $this->shipping_region_model->save_shipping_regions( $region_details );

		if ( $saved ) {
			wp_send_json_success( true );
		} else {
			wp_send_json_error( 'LPAC: Saving drawn region details might have failed.', 500 );
		}

	}

	/**
	 *
	 * Update drawn shipping regions;
	 *
	 * @param string $updated_regions
	 * @return null|void
	 */
	public function update_drawn_shipping_region_setting_handler( string $updated_regions ) {

		if ( empty( $updated_regions ) ) {
			return null;
		}

		$updated_shipping_regions = json_decode( $updated_regions, true );

		if ( ! is_array( $updated_shipping_regions ) ) {
			return null;
		}

		$shipping_regions = $this->shipping_region_model->get_saved_regions();

		if ( empty( $shipping_regions ) ) {
			return null;
		}

		$saved_shipping_region_ids = array_column( $shipping_regions, 'id' );

		foreach ( $updated_shipping_regions as $region_id => $updated_cords ) {
			$key_to_update                                 = array_search( $region_id, $saved_shipping_region_ids );
			$shipping_regions[ $key_to_update ]['polygon'] = $updated_cords['polygonCords'];
		}

		$this->shipping_region_model->save_shipping_regions( $shipping_regions );

	}

	/**
	 * Ajax handler for deleting a shipping region.
	 *
	 * @return mixed
	 */
	public function delete_drawn_shipping_region_ajax_handler() {

			$saved_regions        = $this->shipping_region_model->get_saved_regions();
			$saved_regions_column = array_column( $saved_regions, 'id' );
			$region_id            = sanitize_text_field( $_REQUEST['regionID'] );
			$key                  = array_search( $region_id, $saved_regions_column );

			unset( $saved_regions[ $key ] );
			$saved_regions = array_values( $saved_regions ); // Reset keys

			$updated = $this->shipping_region_model->save_shipping_regions( $saved_regions );

		if ( $updated ) {
			wp_send_json_success( true );
		} else {
			wp_send_json_error( 'LPAC: There was an issue while trying to delete the region.', 500 );
		}

	}

	/**
	 * Ajax handler for updating a shipping region price.
	 *
	 * @return mixed
	 */
	public function update_drawn_shipping_region_cost_ajax_handler() {

			$shipping_regions = $this->shipping_region_model->get_saved_regions();
			$region_details   = $_REQUEST['regionDetails'];
			$region_to_update = sanitize_text_field( $region_details['id'] );
			$region_new_price = sanitize_text_field( $region_details['cost'] );

			$saved_region_ids           = array_column( $shipping_regions, 'id' );
			$saved_region_key_to_update = array_search( $region_to_update, $saved_region_ids );

			$shipping_regions[ $saved_region_key_to_update ]['cost'] = $region_new_price;
			$updated = $this->shipping_region_model->save_shipping_regions( $shipping_regions );

		if ( $updated ) {
			wp_send_json_success( true );
		} else {
			wp_send_json_error( 'LPAC: Updating drawn region price might have failed.', 500 );
		}

	}

}

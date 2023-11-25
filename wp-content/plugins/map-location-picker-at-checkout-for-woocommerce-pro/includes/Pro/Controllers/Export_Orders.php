<?php

/**
 * Handles Lpac csv export related logic.
 *
 * Author:          Uriahs Victor
 * Created on:      28/11/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.0
 * @package Lpac
 */

namespace Lpac\Pro\Controllers;

use Lpac\Pro\Models\Export_Orders as Export_Orders_Model;

/**
 * Class emails.
 *
 * Adds map location details to customer and admin emails.
 */
class Export_Orders {

	/**
	 * Handler for lpac_export_order_records ajax call.
	 *
	 * @since 1.4.0
	 * @return void
	 */
	public function get_order_records_ajax_handler() {

		try {

			$date_range = $_REQUEST['dateRange'] ?? '';

			if ( empty( $date_range ) ) {
				wp_send_json_error( false );
			}

			$link = ( new Export_Orders_Model() )->get_csv_link( $date_range );

			if ( empty( $link ) ) {
				throw new \Exception( 'Received an empty link', 1 );
			}

			wp_send_json_success( $link );

		} catch ( \Throwable $th ) {
			if ( $link === null ) {
				$message = esc_html__( 'No records found for your date range.', 'map-location-picker-at-checkout-for-woocommerce' );
			} else {
				$message = esc_html__( 'An error occurred trying to retrieve the CSV link', 'map-location-picker-at-checkout-for-woocommerce' );
			}
			wp_send_json_error( $message );
		}

	}

	/**
	 * Handler for lpac_get_orders_by_range ajax call.
	 *
	 * @since 1.6.0
	 * @return void
	 */
	public function get_orders_by_range_ajax_handler() {

		try {

			$date_range = $_REQUEST['dateRange'] ?? '';

			if ( empty( $date_range ) ) {
				wp_send_json_error( false );
			}

			$orders = ( new Export_Orders_Model() )->get_orders_by_range( $date_range );

			if ( empty( $orders ) ) {
				throw new \Exception( 'Received an empty array', 1 );
			}

			wp_send_json_success( $orders );

		} catch ( \Throwable $th ) {
			if ( empty( $orders ) ) {
				$message = esc_html__( 'No records found for your date range.', 'map-location-picker-at-checkout-for-woocommerce' );
			} else {
				$message = esc_html__( 'An error occurred trying to retrieve the orders.', 'map-location-picker-at-checkout-for-woocommerce' );
			}
			wp_send_json_error( $message );
		}

	}


}

<?php

/**
 * Handles saving of location details to the database.
 *
 * Author:          Uriahs Victor
 * Created on:      23/07/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.6.0
 * @package Lpac/Pro/Models
 */

namespace Lpac\Pro\Models;

/**
 * Location_Details class.
 */
class Location_Details {

	/**
	 * Add customer region to order meta.
	 *
	 * This value is used for store analytic purposes.
	 *
	 * @param int   $order_id
	 * @param array $data
	 * @since 1.6.0
	 * @return void
	 */
	public function save_customer_region( int $order_id, array $data ) : void {

		$customer_region_data = isset( WC()->session ) ? WC()->session->get( 'lpac_customer_region' ) : '';
		$customer_region      = '';

		if ( empty( $customer_region_data ) ) {
			return;
		}

		$use_highest_region_cost = apply_filters( 'lpac_use_highest_region_cost', true );

		if ( count( $customer_region_data ) > 1 && $use_highest_region_cost ) {
			$costs           = array_column( $customer_region_data, 'cost' );
			$keys            = array_keys( $costs, max( $costs ), true );
			$key             = $keys[0];
			$customer_region = $customer_region_data[ $key ]['region_id'];
		} else {
			$customer_region = $customer_region_data[0]['region_id'];
		}

		update_post_meta( $order_id, 'lpac_customer_region', $customer_region );
	}

	/**
	 * Add customer distance and distance unit to order meta.
	 *
	 * This value is used for store analytic purposes.
	 *
	 * @param int   $order_id
	 * @param array $data
	 * @since 1.6.6
	 * @return void
	 */
	public function save_customer_distance_data( int $order_id, array $data ) : void {

		$customer_distance          = isset( WC()->session ) ? WC()->session->get( 'lpac_customer_distance' ) : '';
		$customer_distance_unit     = isset( WC()->session ) ? WC()->session->get( 'lpac_customer_distance_unit' ) : '';
		$customer_distance_duration = isset( WC()->session ) ? WC()->session->get( 'lpac_customer_distance_duration' ) : '';

		if ( empty( $customer_distance ) || empty( $customer_distance_unit ) ) {
			return;
		}

		update_post_meta( $order_id, 'lpac_customer_distance', $customer_distance );
		update_post_meta( $order_id, 'lpac_customer_distance_unit', $customer_distance_unit );
		update_post_meta( $order_id, 'lpac_customer_distance_duration', $customer_distance_duration );
	}

}

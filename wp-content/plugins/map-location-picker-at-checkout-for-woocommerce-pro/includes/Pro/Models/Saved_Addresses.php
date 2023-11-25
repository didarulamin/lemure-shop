<?php

/**
 *
 * Handle shipping address saving and updating.
 *
 * Author:          Uriahs Victor
 * Created on:      03/10/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since    1.4.0
 * @package Lpac
 */

namespace Lpac\Pro\Models;

class Saved_Addresses {

	/**
	 * Save shipping address to the database.
	 *
	 * @since    1.2.0
	 * @param array $order_id The order id.
	 *
	 * @return void
	 */
	public function lpac_save_shipping_address_to_db( $order_id, $data ) {

		// We're checking $_POST and not $data because this field data is stored in $_POST because of how we created it ( using woocommerce_form_field() )
		if ( empty( $_POST['lpac_save_address_checkbox'] ) ) {
			return;
		}

		$address_name            = ( $_POST['lpac_saved_address_name'] ) ?: 'Address_' . $order_id;
		$address_name            = sanitize_text_field( $address_name );
		$address_name_normalized = strtolower( preg_replace( '/[^A-Za-z0-9]/', '', $address_name ) ); // Strip special characters and spaces to form array index
		$address_name_normalized = $address_name_normalized . '_' . $order_id;

		// Use $_POST instead of $data because there are rare cases where $data might not have our coordinates.
		$latitude  = sanitize_text_field( $_POST['lpac_latitude'] ?? '' );
		$longitude = sanitize_text_field( $_POST['lpac_longitude'] ?? '' );

		if ( empty( $latitude ) || empty( $longitude ) ) {
			return;
		}

		$billing_phone = ( isset( $data['billing_phone'] ) ) ? preg_replace( '/[^0-9]/', '', $data['billing_phone'] ) : '';

		$billing_first_name = sanitize_text_field( $data['billing_first_name'] ?? '' );
		$billing_last_name  = sanitize_text_field( $data['billing_last_name'] ?? '' );
		$billing_company    = sanitize_text_field( $data['billing_company'] ?? '' );
		$billing_country    = sanitize_text_field( $data['billing_country'] ?? '' );
		$billing_address_1  = sanitize_text_field( $data['billing_address_1'] ?? '' );
		$billing_address_2  = sanitize_text_field( $data['billing_address_2'] ?? '' );
		$billing_city       = sanitize_text_field( $data['billing_city'] ?? '' );
		$billing_state      = sanitize_text_field( $data['billing_state'] ?? '' );
		$billing_postcode   = sanitize_text_field( $data['billing_postcode'] ?? '' );
		$billing_phone      = sanitize_text_field( $billing_phone );

		$address_to_save = array(
			$address_name_normalized => array(
				'address_id'         => $order_id,
				'address_name'       => $address_name,
				'latitude'           => $latitude,
				'longitude'          => $longitude,
				'billing_first_name' => $billing_first_name,
				'billing_last_name'  => $billing_last_name,
				'billing_company'    => $billing_company,
				'billing_country'    => $billing_country,
				'billing_address_1'  => $billing_address_1,
				'billing_address_2'  => $billing_address_2,
				'billing_city'       => $billing_city,
				'billing_state'      => $billing_state,
				'billing_postcode'   => $billing_postcode,
				'billing_phone'      => $billing_phone,
			),
		);

		$ship_to_different_address = $data['ship_to_different_address'] ?? false;

		/**
		 * If user shipping to a different address, save that address instead.
		 * We're not currently allowing users to save billing address details as well...maybe we should?
		 */
		if ( ! empty( $ship_to_different_address ) ) {

			$shipping_first_name = sanitize_text_field( $data['shipping_first_name'] ?? '' );
			$shipping_last_name  = sanitize_text_field( $data['shipping_last_name'] ?? '' );
			$shipping_company    = sanitize_text_field( $data['shipping_company'] ?? '' );
			$shipping_country    = sanitize_text_field( $data['shipping_country'] ?? '' );
			$shipping_address_1  = sanitize_text_field( $data['shipping_address_1'] ?? '' );
			$shipping_address_2  = sanitize_text_field( $data['shipping_address_2'] ?? '' );
			$shipping_city       = sanitize_text_field( $data['shipping_city'] ?? '' );
			$shipping_state      = sanitize_text_field( $data['shipping_state'] ?? '' );
			$shipping_postcode   = sanitize_text_field( $data['shipping_postcode'] ?? '' );

			$address_to_save = array(
				$address_name_normalized => array(
					'address_id'          => $order_id,
					'address_name'        => $address_name,
					'latitude'            => $latitude,
					'longitude'           => $longitude,
					'shipping_first_name' => $shipping_first_name,
					'shipping_last_name'  => $shipping_last_name,
					'shipping_company'    => $shipping_company,
					'shipping_country'    => $shipping_country,
					'shipping_address_1'  => $shipping_address_1,
					'shipping_address_2'  => $shipping_address_2,
					'shipping_city'       => $shipping_city,
					'shipping_state'      => $shipping_state,
					'shipping_postcode'   => $shipping_postcode,
				),

			);

		}

		// array_walk( $address_to_save, function( &$value ){
		// $value = wpslash( $value );
		// });

		$user_id = get_current_user_id();

		if ( empty( $user_id ) ) {
			return;
		}

		/* Allow filtering */
		$address_to_save = apply_filters( 'lpac_address_to_save', $address_to_save, $user_id );

		$saved_addresses = get_user_meta( $user_id, 'lpac_saved_addresses', true );

		if ( empty( $saved_addresses ) ) {
			$saved_addresses = array();
		}

		$new_addresses_to_save = array_merge( $saved_addresses, $address_to_save );

		update_user_meta( $user_id, 'lpac_saved_addresses', $new_addresses_to_save );

	}


}

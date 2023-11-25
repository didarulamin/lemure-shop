<?php
/**
 * Handles WooCommerce My Account model related logic.
 *
 * Author:          Uriahs Victor
 * Created on:      03/10/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.0
 * @package Lpac
 */

namespace Lpac\Pro\Models;

/**
 * Class WooCommerce_Account.
 *
 * Holds all methods related to WooCommerce account area.
 */
class WooCommerce_Account {

	/**
	 * Sanitize an input value from $_POST.
	 *
	 * @return string
	 */
	private function sanitize( $value ) {
		$sanitized = sanitize_text_field( $value );
		return $sanitized;
	}

	/**
	 * Update a saved Address.
	 *
	 * @param array The $_POST data.
	 * @return void
	 */
	protected function update_saved_address( $address_details ) {

		$address_id = $address_details['addresses'] ?? '';
		$address_id = $this->sanitize( $address_id );

		if ( empty( $address_id ) ) {
			return;
		}

		$address_name = $address_details['address_name'] ?? $address_id;
		$address_name = $this->sanitize( $address_name );

		$user_id = get_current_user_id();

		$saved_addresses                                = get_user_meta( $user_id, 'lpac_saved_addresses', true );
		$saved_addresses[ $address_id ]['address_name'] = $address_name;

		update_user_meta( $user_id, 'lpac_saved_addresses', $saved_addresses );

	}

	/**
	 * Delete an saved Address.
	 *
	 * @param array The $_POST data.
	 * @return void
	 */
	protected function delete_saved_address( $address_details ) {

		$address_id = $address_details['addresses'] ?? '';
		$address_id = $this->sanitize( $address_id );

		if ( empty( $address_id ) ) {
			return;
		}

		$user_id = get_current_user_id();

		$saved_addresses = get_user_meta( $user_id, 'lpac_saved_addresses', true );
		unset( $saved_addresses[ $address_id ] );

		update_user_meta( $user_id, 'lpac_saved_addresses', $saved_addresses );

	}

}

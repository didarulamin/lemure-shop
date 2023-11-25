<?php

/**
 * Handles WooCommerce My Account related control logic.
 *
 * Author:          Uriahs Victor
 * Created on:      03/10/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.0
 * @package Lpac
 */

namespace Lpac\Pro\Controllers;

use \Lpac\Pro\Models\WooCommerce_Account as WooCommerce_Account_Model;

/**
 * Class WooCommerce_Account.
 *
 * Holds all methods related to WooCommerce account area.
 */
class WooCommerce_Account extends WooCommerce_Account_Model {

	/**
	 * Handle the $_POST request.
	 *
	 * @return never
	 */
	public function handler() {

		$update_delete = $_POST['update_delete'] ?? '';
		$nonce         = $_POST['_wpnonce'];

		if ( empty( wp_verify_nonce( $nonce, 'lpac_update_saved_addresses' ) ) ) {
			wp_die( esc_html__( 'Invalid Nonce', 'map-location-picker-at-checkout-for-woocommerce' ), esc_html__( 'Error', 'map-location-picker-at-checkout-for-woocommerce' ) );
		}

		if ( empty( $update_delete ) ) {
			exit;
		}

		if ( $update_delete === 'delete' ) {
			$this->delete_saved_address( $_POST );
		}

		if ( $update_delete === 'update' ) {
			$this->update_saved_address( $_POST );
		}

		wp_redirect( $_POST['url'] );

		exit;
	}

}

<?php

/**
 * Handles WooCommerce My Account view related logic.
 *
 * Author:          Uriahs Victor
 * Created on:      03/10/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.0
 * @package Lpac
 */

namespace Lpac\Pro\Views\Frontend;

/**
 * Class WooCommerce_Account.
 *
 * Holds all methods related to WooCommerce account area.
 */
class WooCommerce_Account {

	/**
	 * Output the customer saved addresses
	 *
	 * @return void
	 */
	public function output_customer_addresses() {

		global $wp;
		$post_url  = esc_attr( get_site_url( '', '/wp-admin/admin-post.php' ) );
		$site_url  = esc_attr( get_site_url( '', $wp->request ) );
		$user_id   = get_current_user_id();
		$addresses = get_user_meta( $user_id, 'lpac_saved_addresses', true );

		if ( empty( $addresses ) ) {
			_e( 'No addresses have been saved yet.', 'map-location-picker-at-checkout-for-woocommerce' );
			return;
		}

		$options = '<option value="">' . esc_html__( 'Select', 'default' ) . '</option>';
		foreach ( $addresses as $key => $details ) {
			// Remove some unneeded fields
			unset( $details['address_id'] );
			unset( $details['shipping_country'] );
			unset( $details['billing_country'] );
			unset( $details['latitude'] );
			unset( $details['longitude'] );
			array_walk(
				$details,
				function( &$value ) {
					$value = esc_html( $value );
				}
			);

			$details_json = json_encode( $details );
			$details_json = esc_attr( $details_json );
			$address_name = esc_html( $details['address_name'] );
			$key          = esc_attr( $key );
			$options     .= "<option value='$key' data-address-details='$details_json' >" . $address_name . '</option>';
		}

		$update_text  = esc_attr__( 'Update', 'woocommerce' );
		$delete_text  = esc_attr__( 'Delete', 'woocommerce' );
		$feature_text = esc_html__( 'Saved Addresses', 'map-location-picker-at-checkout-for-woocommerce' );
		$feature_text = apply_filters( 'lpac_saved_addresses_text', $feature_text );
		$nounce_field = wp_nonce_field( 'lpac_update_saved_addresses' );

		?>
			<div id="lpac-addresses-containter">
				<form id="lpac-address-details-form" action="<?php echo esc_attr( $post_url ); ?>" method="post">
					<div id="lpac-saved-addresses-dropdown-wrap">
						<p><strong><?php echo esc_html( $feature_text ); ?></strong></p>
						<select name="addresses" id="lpac-saved-addresses-dropdown">
							<?php echo $options; // phpcs:ignore -- We are already escaping each individual option above. ?>
						</select>
					</div>
					<input id="lpac-update-delete" type="hidden" name="update_delete" value="0">
					<input type="hidden" name="url" value="<?php echo esc_attr( $site_url ); ?>">
					<input type="hidden" name="action" value="lpac_handle_saved_addresses">
					<div class="lpac-address-action-buttons">
						<input id="lpac-update-saved-address" type="submit" value="<?php echo esc_attr( $update_text ); ?>">
						<input id="lpac-delete-saved-address" type="submit" value="<?php echo esc_attr( $delete_text ); ?>">
					</div>
					<?php echo $nounce_field; // phpcs:ignore -- This is a nounce field.?>
				</form>
			</div>
		<?php

	}

	/**
	 * Localize our address fields labels to be used by Javascript.
	 *
	 * @return void
	 */
	public function get_translated_strings() {

		$fields = array(
			'address_name'        => __( 'Address Name', 'map-location-picker-at-checkout-for-woocommerce' ),
			'latitude'            => __( 'Latitude', 'map-location-picker-at-checkout-for-woocommerce' ),
			'longitude'           => __( 'Longitude', 'map-location-picker-at-checkout-for-woocommerce' ),
			'shipping_first_name' => __( 'First name', 'woocommerce' ),
			'shipping_last_name'  => __( 'Last name', 'woocommerce' ),
			'shipping_company'    => __( 'Company name', 'woocommerce' ),
			'shipping_country'    => __( 'Country / Region', 'woocommerce' ),
			'shipping_address_1'  => __( 'Street address', 'woocommerce' ),
			'shipping_address_2'  => __( 'Apartment, suite, unit, etc.', 'woocommerce' ),
			'shipping_city'       => __( 'Town / City', 'woocommerce' ),
			'shipping_state'      => __( 'State / County', 'woocommerce' ),
			'shipping_postcode'   => __( 'Postcode / ZIP', 'woocommerce' ),
			'billing_first_name'  => __( 'First name', 'woocommerce' ),
			'billing_last_name'   => __( 'Last name', 'woocommerce' ),
			'billing_company'     => __( 'Company name', 'woocommerce' ),
			'billing_country'     => __( 'Country / Region', 'woocommerce' ),
			'billing_address_1'   => __( 'Street address', 'woocommerce' ),
			'billing_address_2'   => __( 'Apartment, suite, unit, etc.', 'woocommerce' ),
			'billing_city'        => __( 'Town / City', 'woocommerce' ),
			'billing_state'       => __( 'State / County', 'woocommerce' ),
			'billing_postcode'    => __( 'Postcode / ZIP', 'woocommerce' ),
			'billing_phone'       => __( 'Phone', 'woocommerce' ),
		);

		wp_localize_script( 'lpac-public-pro', 'lpacTranslatedWCAddressFields', $fields );

	}
}

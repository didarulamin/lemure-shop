<?php

/**
 * House the public facing methods for the pro plugin.
 *
 * Pro methods needed by plugin.
 *
 * @link    https://uriahsvictor.com
 * @since    1.1.0
 *
 * @package    Lpac
 */
namespace Lpac\Pro\Views\Frontend;

use Lpac\Views\Frontend\Frontend as Lite_Frontend;

class Frontend extends Lite_Frontend {

	/**
	 * Output the saved customer addresses on checkout page
	 *
	 * @param string|null $empty
	 * @param int         $user_id
	 * @return void
	 */
	public function output_saved_addresses( ?string $empty, int $user_id ) : void {

		// This function would bail if guest checkout or logged out.
		if ( empty( $user_id ) ) {
			return;
		}

		$user_address_details = get_user_meta( $user_id, 'lpac_saved_addresses', true );

		if ( ! is_array( $user_address_details ) || empty( $user_address_details ) ) {
			return;
		}

		$markup = '';

		foreach ( $user_address_details as $id => $address_values ) {
			?>
			<li>
				<button class='lpac-saved-address-btn button btn' data-address-details='<?php echo wp_json_encode( $address_values ); ?>'><?php echo esc_html( $address_values['address_name'] ); ?></button>
			</li>
			<?php
		}

	}

	/**
	 * Output link to account addresses area.
	 *
	 * @param string|null $empty
	 * @param int         $user_id
	 * @return void
	 * @since 1.6.8 The logic was moved into its own function, previously it was outputted in main map output code.
	 */
	public function output_saved_addresses_edit_link( ?string $empty, int $user_id ): void {
		// This function would bail if guest checkout or logged out.
		if ( empty( $user_id ) ) {
			return;
		}

		$user_address_details = get_user_meta( $user_id, 'lpac_saved_addresses', true );

		// Edit link should only show if we have saved addresses.
		if ( ! is_array( $user_address_details ) || empty( $user_address_details ) ) {
			return;
		}

		$account_link         = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . 'edit-address/';
		$edit_saved_addresses = sprintf( esc_html__( '%1$sEdit saved addresses%2$s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='$account_link'>", '</a>' );
		echo "<p id='edit-saved-addresses'>$edit_saved_addresses</p>"; // phpcs:ignore -- Already escaped in above line.
	}

	/**
	 * Change the default error that shows then a customer tries to checkout without choosing a shipping method.
	 *
	 * @param array  $fields The fields array.
	 * @param object $errors The errors object.
	 * @return void
	 */
	public function change_default_no_shipping_methods_selected_text( $fields, $errors ) {
		$text = get_option( 'lpac_no_shipping_method_selected_error' );
		$text = empty( $text ) ? __( 'We do not currently ship to your selected location. Please choose a different location on the map then try placing your order again. Please contact us with any issues.', 'map-location-picker-at-checkout-for-woocommerce' ) : $text;
		if ( ! empty( $errors->errors['shipping'] ) ) {
			$errors->add( 'shipping', $text ); // add our custom error message
		}
	}


	/**
	 * Change default "Enter your address to view shipping options." text that shows on checkout when no shipping options are available.
	 *
	 * @return string
	 */
	public function change_default_no_store_location_selected_text() {
		$text = __( 'Please select a store location to deliver from', 'map-location-picker-at-checkout-for-woocommerce' );
		return apply_filters( 'lpac_store_location_not_selected_text', $text );
	}

	/**
	 * Change the default error that shows then a customer tries to checkout without choosing a shipping method.
	 *
	 * @param array  $fields The fields array.
	 * @param object $errors The errors object.
	 * @return void
	 */
	public function distance_cost_change_default_no_shipping_methods_selected_text( $fields, $errors ) {
		$text = get_option( 'lpac_distance_cost_no_shipping_method_selected_error' );
		$text = empty( $text ) ? '<strong>' . __( 'We do not currently ship to your selected location. Please choose a location closer to our main store then try placing your order again. Please contact us if you need any help.', 'map-location-picker-at-checkout-for-woocommerce' ) . '</strong>' : '<strong>' . $text . '<strong>';
		if ( ! empty( $errors->errors['shipping'] ) ) {
			$errors->add( 'shipping', $text ); // add our custom error message
		}
	}
}

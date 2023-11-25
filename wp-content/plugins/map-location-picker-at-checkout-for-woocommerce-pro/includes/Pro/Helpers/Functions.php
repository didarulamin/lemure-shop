<?php
/**
 * Provide helper static functions.
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Lpac
 */
namespace Lpac\Pro\Helpers;

use Freemius_Exception;
use Lpac\Helpers\Functions as Lite_Functions_Helpers;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;

class Functions extends Lite_Functions_Helpers {

	/**
	 * Detect pro version.
	 *
	 * @since    1.1.0
	 */
	public static function lpac_is_premium() {
		return lpac_fs()->is_paying();
		// return lpac_fs()->can_use_premium_code();
	}

	/**
	 * Output premium bool to JS.
	 *
	 * Detects if the plugin is the PRO version and outputs various PRO related JS.
	 *
	 * @since    1.1.0
	 *
	 * @return string Json encoded string for JS.
	 * // TODO Refactor this method and call in Pro frontend file as necessary instead of having it in this helper class.
	 */
	public static function expose_premium_js() {

		if ( ! self::is_allowed_woocommerce_pages() ) {
			return;
		}

		$google_map_id = '';

		// We have to set the condition for !is_wc_endpoint_url() or else this setting would also apply to the order-received page
		if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			$google_map_id = get_option( 'lpac_checkout_page_map_id', '' );
		}

		if ( is_wc_endpoint_url( 'view-order' ) ) {
			$google_map_id = get_option( 'lpac_view_order_page_map_id', '' );
		}

		if ( is_wc_endpoint_url( 'order-received' ) ) {
			$google_map_id = get_option( 'lpac_order_received_page_map_id', '' );
		}

		if ( is_admin() && get_current_screen()->id === 'shop_order' ) {
			$google_map_id = get_option( 'lpac_admin_view_order_map_id', '' );
		}

		$output           = self::get_premium_js_settings( $google_map_id );
		$global_variables = <<<JAVASCRIPT
		var lpac_pro_js = $output;
JAVASCRIPT;

		wp_add_inline_script( LPAC_PLUGIN_NAME . '-base-map', $global_variables, 'before' );
	}

	/**
	 * Get our Premium JS for outputting to the page.
	 *
	 * @param string $google_map_id
	 * @return string|false
	 * @throws Freemius_Exception
	 * @since 1.7.0
	 */
	public static function get_premium_js_settings( $google_map_id = '' ) {

		$places_autocomplete_restrictions = get_option( 'lpac_places_autocomplete_country_restrictions' );
		// The function added to this filter must return an array.
		$places_autocomplete_type = apply_filters( 'lpac_pro_places_autocomplete_types', array( get_option( 'lpac_places_autocomplete_type', 'address' ) ) );

		$marker_icon_image    = get_option( 'lpac_map_marker_icon', false );
		$attachment_id        = '';
		$attachment_meta      = '';
		$marker_icon_anchor_x = '';
		$marker_icon_anchor_y = '';

		if ( ! empty( $marker_icon_image ) ) {
			$attachment_id   = attachment_url_to_postid( $marker_icon_image );
			$attachment_meta = wp_get_attachment_metadata( $attachment_id, true );

			$marker_icon_anchors = get_option( 'lpac_map_anchor_points', array() );

			$parts = explode( ',', $marker_icon_anchors );

			$marker_icon_anchor_x = $parts[0] ?? '';
			$marker_icon_anchor_y = $parts[1] ?? '';
		}

		$shipping_regions_enabled             = get_option( 'lpac_shipping_cost_by_region_enabled' );
		$shipping_regions_enabled             = filter_var( $shipping_regions_enabled, FILTER_VALIDATE_BOOLEAN );
		$currency_symbol                      = get_woocommerce_currency_symbol();
		$show_shipping_regions                = get_option( 'lpac_show_shipping_regions_on_checkout_map', 'no' );
		$show_shipping_regions                = filter_var( $show_shipping_regions, FILTER_VALIDATE_BOOLEAN );
		$show_shipping_regions_cost           = get_option( 'lpac_show_shipping_regions_cost_on_checkout_map', 'no' );
		$show_shipping_regions_cost           = filter_var( $show_shipping_regions_cost, FILTER_VALIDATE_BOOLEAN );
		$show_shipping_regions_label          = get_option( 'lpac_show_shipping_regions_name_on_checkout_map', 'no' );
		$show_shipping_regions_label          = filter_var( $show_shipping_regions_label, FILTER_VALIDATE_BOOLEAN );
		$regions_default_background_color     = get_option( 'lpac_shipping_regions_default_background_color', '#ff0000' );
		$regions_fill_opacity                 = apply_filters( 'lpac_shipping_regions_bg_fill_opacity', 0.35 );
		$places_autocomplete_enabled          = get_option( 'lpac_enable_places_autocomplete', 'no' );
		$places_autocomplete_enabled          = filter_var( $places_autocomplete_enabled, FILTER_VALIDATE_BOOLEAN );
		$places_autocomplete_searchbox_on_map = get_option( 'lpac_places_autocomplete_searchbox_in_map' );
		$places_autocomplete_searchbox_on_map = filter_var( $places_autocomplete_searchbox_on_map, FILTER_VALIDATE_BOOLEAN );

		$output = array(
			'is_pro'                               => self::lpac_is_premium(),
			'google_map_id'                        => $google_map_id,
			'marker_icon'                          => get_option( 'lpac_map_marker_icon', '' ),
			'marker_icon_width'                    => $attachment_meta['width'] ?? '',
			'marker_icon_height'                   => $attachment_meta['height'] ?? '',
			'marker_icon_anchor_x'                 => (int) $marker_icon_anchor_x,
			'marker_icon_anchor_y'                 => (int) $marker_icon_anchor_y,
			'places_autocomplete_enabled'          => $places_autocomplete_enabled,
			'places_autocomplete_searchbox_on_map' => $places_autocomplete_searchbox_on_map,
			'places_autocomplete_restrictions'     => $places_autocomplete_restrictions,
			'places_autocomplete_type'             => $places_autocomplete_type,
			'shippingRegions'                      => array(
				'enabled'                  => $shipping_regions_enabled,
				'shopCurrency'             => $currency_symbol,
				'showShippingRegions'      => $show_shipping_regions,
				'showShippingRegionsCost'  => $show_shipping_regions_cost,
				'showShippingRegionsLabel' => $show_shipping_regions_label,
				'regionsDefaultBgColor'    => $regions_default_background_color,
				'regionsBgFillOpacity'     => $regions_fill_opacity,
			),
		);

		return json_encode( $output );
	}

	/**
	 * Get current shipping zone at checkout.
	 *
	 * Gets the current shipping zone the customer order falls in (based on the shipping zone settings in WC settings).
	 *
	 * @since    1.2.0
	 *
	 * @return string The Shipping Zone the order falls in.
	 */
	public static function lpac_get_order_shipping_zone() {

		$contents      = WC()->cart->get_shipping_packages();
		$shipping_zone = wc_get_shipping_zone( reset( $contents ) );

		return array(
			'zone_id'   => $shipping_zone->get_id(),
			'zone_name' => $shipping_zone->get_zone_name(),
		);

	}

	/**
	 * Normalize available shipping zones for use.
	 *
	 * Get the list of shipping zones available on the site and get them ready for use in the multiselect settings field of the plugin.
	 *
	 * @since    1.2.0
	 *
	 * @return array Array of available shipping zones.
	 */
	public static function lpac_get_available_shipping_zones() {

		$normalized_shipping_zones = array();

		if ( ! class_exists( 'WC_Shipping_Zones' ) ) {
			error_log( 'Location Picker at Checkout for WooCommerce: WC_Shipping_Zones() class not found.' );
			return $normalized_shipping_zones;
		}

		$lpac_wc_shipping_zones = \WC_Shipping_Zones::get_zones();

		foreach ( $lpac_wc_shipping_zones as $shipping_zone_array ) {

			$iterated_shipping_zone = array(
				$shipping_zone_array['zone_id'] => $shipping_zone_array['zone_name'],
			);

			/**
			 * We need to keep our zone_id as the key so we can later use. array_merge would reset the keys.
			 */
			$normalized_shipping_zones = array_replace( $normalized_shipping_zones, $iterated_shipping_zone );

		}

		return $normalized_shipping_zones;

	}


	/**
	 * Determine whether to show the map based on shipping zone.
	 *
	 * @param array $selected_shipping_zones_ids the shipping zones selected by the admin in settings.
	 *
	 * @return bool whether or not to show the map.
	 */
	public static function lpac_should_show_shipping_zone( $selected_shipping_zones_ids ) {

		/**
		 * Get the current order being checkedout zone ID
		 */
		$order_shipping_zone_id = self::lpac_get_order_shipping_zone()['zone_id'];

		/**
		 * Get the Show/Hide option selected by the admin for shipping zones.
		 */
		$shown_hidden = get_option( 'lpac_wc_shipping_zones_show_hide' );

		$has_match = in_array( $order_shipping_zone_id, $selected_shipping_zones_ids );

		/**
		 * If the order being checked out has a shipping zone ID that exists in our list, and the admin
		 * set the option to "Show" from LPAC settings, then show the map and load it's assets.
		 *
		 * Because the admin chose to show the map only for orders that contain the shipping zone they selected in the plugin settings.
		 */
		if ( ! empty( $has_match ) && $shown_hidden === 'show' ) {
			return true;
		}

		/**
		 * If the order being checked out has a shipping zone ID that doesn't exists in our list, and the admin
		 * set the option to "Hide" from LPAC settings, then show the map and load it's assets.
		 *
		 * Because the admin chose to show the map only for orders that DO NOT contain the shipping zone they selected in the plugin settings.
		 */
		if ( empty( $has_match ) && $shown_hidden === 'hide' ) {
			return true;
		}

		/**
		 * Return false(hide the map) in all other situations.
		 */
		return false;

	}

	/**
	 * Hide or show the map based on the cart subtotal.
	 *
	 * @return bool
	 */
	public static function show_map_by_cart_amount() {

		$min_cart_amount = get_option( 'lpac_map_min_cart_amount' );
		$max_cart_amount = get_option( 'lpac_map_max_cart_amount' );

		$total = WC()->cart->subtotal;

		// Allow users to filter the cart total rules.
		// For example a user might want to do WC()->cart->get_cart_contents_total() instead.
		$total = apply_filters( 'lpac_map_visibility_cart_total', $total, WC() );

		if ( ! empty( $min_cart_amount ) && $total < $min_cart_amount ) {
			return false;
		}

		if ( ! empty( $max_cart_amount ) && $total > $max_cart_amount ) {
			return false;
		}

		return true;

	}

	/**
	 * Remove the attached shipping methods to Kikote from the list of shipping methods provided.
	 *
	 * This helps prevent customers from being able to checkout using an empty shipping method that was attached to the plugin shipping workflows.
	 *
	 * @param array  $rates
	 * @param string $workflow Valid values = cbr (Cost by Region),cbd (Cost by Distance) or cbsl (Cost by Store Location)
	 * @return array
	 * @since 1.8.2
	 */
	public static function removeEmptyKikoteShippingMethodsFromRates( array $rates, string $workflow ): array {

		$cords = WC()->session->get( 'lpac_customer_coordinates' );

		// Don't run if the customer has not selected their location.
		if ( empty( $cords['latitude'] ) || empty( $cords['longitude'] ) ) {
			return $rates;
		}

		$selected_shipping_methods_ids = '';

		if ( 'cbr' === $workflow ) {
			$selected_shipping_methods_ids = Shipping_Settings::costByRegionAllowedShippingMethods();
		}
		if ( 'cbd' === $workflow ) {
			$selected_shipping_methods_ids = Shipping_Settings::costByDistanceAllowedShippingMethods();
		}
		if ( 'cbsl' === $workflow ) {
			$selected_shipping_methods_ids = Shipping_Settings::costByStoreLocationAllowedShippingMethods();
		}

		if ( empty( $selected_shipping_methods_ids ) ) {
			return $rates;
		}

		foreach ( $selected_shipping_methods_ids as $selected_shipping_methods_id ) {
			foreach ( $rates as $rate_key => $rate_class ) {
				$parts = explode( ':', $rate_key );
				if ( $selected_shipping_methods_id === ( $parts[1] ?? '' ) ) {
					// If the delivery method has no cost, and it's not been renamed to Free Shipping, then remove it from list of shipping methods.
					if ( empty( (float) $rate_class->get_cost() ) && __( 'Free Shipping', 'woocommerce' ) !== $rate_class->get_label() ) {
						unset( $rates[ $rate_key ] );
					}
				}
			}
		}

		return (array) $rates;
	}

	/**
	 * Calculate the shipping cost taxes based on the tax rates that should apply for the customer.
	 *
	 * @param float $cost
	 * @return array
	 * @since 1.8.6
	 */
	public static function calculateShippingCostTaxes( float $cost ): array {

		$wc_taxes = \WC_Tax::get_shipping_tax_rates();
		$taxes    = array();
		foreach ( $wc_taxes as $rate_id => $rate_data ) {
			$tax_rate          = $rate_data['rate'];
			$taxes[ $rate_id ] = ( $tax_rate * $cost ) / 100;
		}

		return $taxes;
	}

	/**
	 * Return a list of unselected shipping methods (methods not selected in any Kikote workflow).
	 *
	 * This is used when customers do not meet the criteria for a particular workflow and we need to remove the shipping method attached to that
	 * workflow from the list of available ones to the customer.
	 *
	 * @param array $rates List of all available shipping methods.
	 * @return array The filtered array containing all methods not selected in any Kikote workflow.
	 * @since 1.6.9
	 */
	public static function getUnselectedRates( array $rates ): array {

		$cost_by_region_allowed_shipping_methods         = Shipping_Settings::costByRegionAllowedShippingMethods();
		$cost_by_distance_allowed_shipping_methods       = Shipping_Settings::costByDistanceAllowedShippingMethods();
		$cost_by_store_location_allowed_shipping_methods = Shipping_Settings::costByStoreLocationAllowedShippingMethods();

		$allowed_shipping_method_ids = array();
		if ( Shipping_Settings::costByRegionEnabled() ) {
			$allowed_shipping_method_ids = array_merge( $allowed_shipping_method_ids, $cost_by_region_allowed_shipping_methods );
		}

		if ( Shipping_Settings::costByDistanceEnabled() ) {
			$allowed_shipping_method_ids = array_merge( $allowed_shipping_method_ids, $cost_by_distance_allowed_shipping_methods );
		}

		if ( Shipping_Settings::costByStoreLocationEnabled() ) {
			$allowed_shipping_method_ids = array_merge( $allowed_shipping_method_ids, $cost_by_store_location_allowed_shipping_methods );
		}

		$allowed_shipping_method_ids = array_unique( $allowed_shipping_method_ids );

		return array_filter(
			$rates,
			function( $item ) use ( $allowed_shipping_method_ids ) {
				$parts              = explode( ':', $item );
				$shipping_method_id = $parts[1];

				// return only local pickup rates or the methods not selected in Kikote shipping settings for a respective shipping workflow.
				// See https://github.com/UVLabs/location-picker-at-checkout-for-woocommerce-pro/issues/386
				if ( ! in_array( $shipping_method_id, $allowed_shipping_method_ids, true ) ) {
					return true;
				}
			},
			ARRAY_FILTER_USE_KEY
		);
	}
}

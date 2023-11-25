<?php
/**
 * Pro settings additions
 *
 * Additional settings available in pro version
 *
 * @link    https://uriahsvictor.com
 * @since    1.1.0
 *
 * @package    Lpac
 */
namespace Lpac\Pro\Views\Admin;

use Lpac\Models\Plugin_Settings\Store_Locations;
use Lpac\Pro\Helpers\Functions as Functions_Helper;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;
use Lpac\Views\Admin\Admin_Settings as Lite_Admin_Settings;

/**
 * Class responsible for creating admin settings.
 *
 * @package Lpac\Pro\Views\Admin
 */
class Admin_Settings {

	/**
	 * Learn more text string.
	 *
	 * @var string
	 */
	public static $learn_more;

	/**
	 * Constructor.
	 */
	public function __construct() {
		/* translators: 1: Dashicons outbound link icon */
		self::$learn_more = sprintf( __( 'Learn More %s', 'map-location-picker-at-checkout-for-woocommerce' ), '<span style="text-decoration: none" class="dashicons dashicons-external"></span>' );
	}

	/**
	 * Add extra pro map display settings fields.
	 *
	 * @param mixed $lpac_lite_settings
	 * @return array
	 */
	public static function create_display_settings_fields() {

		$pro_display_settings = array();

		$pro_display_settings[] = array(
			'name'  => __( 'Map ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-star-filled premium-subsection',
			'type'  => 'hr',
			'desc'  => sprintf( __( 'Set the Map ID for the respective Maps. You can create a custom map for each entry. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/display-settings/?utm_source=displaytab&utm_medium=lpacdashboard&utm_campaign=prodocs#map-id' target='_blank'>" . self::$learn_more . '</a>' ),
		);

		$pro_display_settings[] = array(
			'name'        => __( 'Checkout Page Map ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip'    => __( 'The Map ID to use for your Checkout page for styling.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_checkout_page_map_id',
			'placeholder' => 'cfceab16...',
			'type'        => 'text',
		);

		$pro_display_settings[] = array(
			'name'        => __( 'Order Received Page Map ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip'    => __( 'The Map ID to use for your "Order Received" page for styling.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_order_received_page_map_id',
			'placeholder' => 'cfceab16...',
			'type'        => 'text',
		);

		$pro_display_settings[] = array(
			'name'        => __( 'View Order Page Map ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip'    => __( 'The Map ID to use for your "View Order" page for styling.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_view_order_page_map_id',
			'placeholder' => 'cfceab16...',
			'type'        => 'text',
		);

		$pro_display_settings[] = array(
			'name'        => __( 'Admin Dashboard View Order Page Map ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip'    => __( 'The Map ID to use for your the "View Order" page inside the WordPress admin Dashboard.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_admin_view_order_map_id',
			'placeholder' => 'cfceab16...',
			'type'        => 'text',
		);

		$pro_display_settings[] = array(
			'name'  => __( 'Marker Icon', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-star-filled premium-subsection',
			'desc'  => sprintf( __( 'Set a custom icon to be used for the main map marker; the marker that customers move around on the map. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/display-settings/?utm_source=displaytab&utm_medium=lpacdashboard&utm_campaign=prodocs#marker-icon' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'  => 'hr',
		);

		$pro_display_settings[] = array(
			'name'     => __( 'Link to Icon', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'The icon to use as the map marker.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Enter the URL to the icon that should be used as the custom map marker.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_map_marker_icon',
			'type'     => 'url',
		);

		$pro_display_settings[] = array(
			'name'        => __( 'Marker Anchor Points', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'        => __( 'The anchor point for the marker in X,Y values. Used to show customer where exactly they\'re moving the marker to. The X value is usually half of the image width, the Y is usually the height of the image + 3. Be sure to test the map marker after setting these values to ensure the anchor works well.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_map_anchor_points',
			'placeholder' => '15, 33',
			'type'        => 'text',
			'css'         => 'max-width:80px;',
		);

		return $pro_display_settings;
	}

	/**
	 * Add extra pro map visibility settings fields.
	 *
	 * @param mixed $lpac_lite_settings
	 * @return array
	 */
	public static function create_pro_visibility_settings_fields( $lpac_lite_settings ) {

		$lpac_pro_settings = array();

		$lpac_pro_settings[] = array(
			'name'    => __( 'Shipping Zones', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class'   => 'wc-enhanced-select',
			'desc'    => sprintf( __( 'Select the Shipping Zones. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/visibility-rules/?utm_source=visibilityrulestab&utm_medium=lpacdashboard&utm_campaign=prodocs#shipping-zones-pro-feature' target='blank'>" . self::$learn_more . '</a>' ),
			'id'      => 'lpac_wc_shipping_zones',
			'type'    => 'multiselect',
			'options' => Functions_Helper::lpac_get_available_shipping_zones(),
			'css'     => 'min-width:300px;height: 100px',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Show or Hide', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => sprintf(
						/* translators: 1: Line break HTML 2: opening strong tag 3: closing strong tag*/
				esc_html__( 'Should the map be shown or hidden if the order falls within above selected shipping zones? %1$s%1$s Selecting %2$sShow%3$s will display the map %2$sONLY IF%3$s the customer order falls inside the shipping zones selected above. %1$s Selecting %2$sHide%3$s will display the map only if the customer order %2$sDOES NOT%3$s fall inside the shipping zones selected above.', 'map-location-picker-at-checkout-for-woocommerce' ),
				'<br>',
				'<strong>',
				'</strong>'
			),
			'id'      => 'lpac_wc_shipping_zones_show_hide',
			'type'    => 'radio',
			'options' => array(
				'show' => esc_html__( 'Show', 'map-location-picker-at-checkout-for-woocommerce' ),
				'hide' => esc_html__( 'Hide', 'map-location-picker-at-checkout-for-woocommerce' ),
			),
			// 'css'     => 'min-width:300px;height: 100px',
		);

		$lpac_pro_settings[] = array(
			'name' => esc_html__( 'Minimum Cart Subtotal', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => esc_html__( 'The minimum amount the cart total should be before showing the checkout page map. NOTE: Coupons and Shipping Cost are not taken into account when calculating the cart subtotal.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_map_min_cart_amount',
			'type' => 'text',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name' => esc_html__( 'Maximum Cart Subtotal', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => esc_html__( 'The maximum amount the cart total can be before hiding the checkout page map. NOTE: Coupons and Shipping Cost are not taken into account when calculating the cart subtotal.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_map_max_cart_amount',
			'type' => 'text',
			'css'  => 'max-width:80px;',
		);

		return array_merge( $lpac_lite_settings, $lpac_pro_settings );
	}

	/**
	 * Add pro settings.
	 *
	 * Adds pro settings to base free settings.
	 *
	 * @since    1.1.0
	 *
	 * @return array Array of pro settings.
	 */
	public static function create_general_settings_fields() {

		$lpac_pro_settings = array();

		$lpac_pro_settings[] = array(
			'name'     => __( 'Show A Searchbox Inside the Map', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => sprintf( __( 'Enabling this option will add a searchbox inside the checkout page map. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/places-autocomplete-feature/?utm_source=generaltab&utm_medium=lpacdashboard&utm_campaign=prodocs#show-a-searchbox-inside-the-map' target='_blank'>" . self::$learn_more . '</a>' ),
			'id'       => 'lpac_places_autocomplete_searchbox_in_map',
			'type'     => 'checkbox',
			'css'      => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Restrict Places Autocomplete Countries', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( "Select the countries you'd like addresses to be pulled from when using the Places Autocomplete feature. Google will only respect 5 restricted countries, so please only select 5 at max.", 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'Use this feature if you only want to show address results from a specific country or countries.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_places_autocomplete_country_restrictions',
			'type'     => 'multi_select_countries',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Places Autocomplete Type', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => __( 'Select the type of address you would like the Places Autocomplete API to return.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_places_autocomplete_type',
			'type'    => 'select',
			'options' => array(
				'address'       => __( 'Precise Address', 'map-location-picker-at-checkout-for-woocommerce' ),
				'geocode'       => __( 'Geocode', 'map-location-picker-at-checkout-for-woocommerce' ),
				'establishment' => __( 'Establishment', 'map-location-picker-at-checkout-for-woocommerce' ),
				'(regions)'     => __( 'Regions', 'map-location-picker-at-checkout-for-woocommerce' ),
				'(cities)'      => __( 'Cities', 'map-location-picker-at-checkout-for-woocommerce' ),
			),
			'css'     => 'max-width:180px;',
		);

		$lpac_pro_settings[] = array(
			'name'  => __( 'Saved Addresses', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-star-filled premium-subsection',
			'desc'  => sprintf( __( 'Allow customers to save different addresses for later use. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/general-settings/?utm_source=generaltab&utm_medium=lpacdashboard&utm_campaign=prodocs#saved-addresses' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'  => 'hr',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_save_address_feature',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		return $lpac_pro_settings;
	}

	/**
	 * House all the plugin settings to do with order records exporting.
	 *
	 * @return array
	 */
	public function create_export_settings_fields() {

		$lpac_pro_settings = array();

		$lpac_pro_settings[] = array(
			'name' => __( 'LPAC Export', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_display_settings',
			'type' => 'title',
			'desc' => Lite_Admin_Settings::create_plugin_settings_banner(),
		);

		$lpac_pro_settings[] = array(
			'name'      => __( 'Orders Map View', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'      => 'div',
			'class'     => 'lpac-map',
			'desc'      => __( 'This map shows the locations for the orders in your date range. It only shows orders that are marked as "Processing" or "Completed".', 'map-location-picker-at-checkout-for-woocommerce' ),
			'css'       => 'height: 480px;',
			'is_option' => false,
		);

		$lpac_pro_settings[] = array(
			'name'  => __( 'Date From', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'    => 'lpac_export_date_from',
			'type'  => 'date',
			'value' => date( 'Y-m-d', strtotime( '-1 week' ) ), // 1 week ago
			'desc'  => __( 'Set START date from which you want to start exporting orders.', 'map-location-picker-at-checkout-for-woocommerce' ),
		);

		$lpac_pro_settings[] = array(
			'name'  => __( 'Date To', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'    => 'lpac_export_date_to',
			'type'  => 'date',
			'value' => date( 'Y-m-d', strtotime( 'Tomorrow' ) ), // Tomorrow, this is so that we can always get the most recent orders.
			'desc'  => __( 'Set END date from which you want to start exporting orders.', 'map-location-picker-at-checkout-for-woocommerce' ),
		);

		$lpac_pro_settings[] = array(
			'name'      => __( 'Export to CSV', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'        => 'lpac_export_btn',
			'type'      => 'button',
			'value'     => 'Download',
			/* translators: 1: HTML <br> element 2: HTML <code> tag 3: Location of .csv files 4: HTML </code> tag */
			'desc'      => sprintf( __( 'A .CSV file with Order ID, Customer Name, Customer Email, Order Date, Map Link and Phone Number will be downloaded.%1$s Files are saved to: %2$s %3$s %4$s', 'map-location-picker-at-checkout-for-woocommerce' ), '<br>', '<code>', '/wp-content/uploads/lpac/order-exports/', '</code>' ),
			'is_option' => false,
		);

		$lpac_pro_settings[] = array(
			'type' => 'sectionend',
			'id'   => 'lpac_export_section_end',
		);

		return $lpac_pro_settings;
	}

	/**
	 * House all the plugin settings to do with Shipping.
	 *
	 * @return array
	 */
	public static function create_shipping_settings_fields() {

		$lpac_pro_settings = array();

		$lpac_pro_settings[] = array(
			'name' => __( 'LPAC Shipping', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_shipping_settings',
			'type' => 'title',
			'desc' => Lite_Admin_Settings::create_plugin_settings_banner() . '<p style="text-align:center; font-size: 35px; font-weight: bold;">' . __( 'Enabling multiple shipping workflows can lead to unexpected shipping costs. Only enable one of these shipping workflows at a time.', 'map-location-picker-at-checkout-for-woocommerce' ) . '</p>',
		);

		// --------------
		// Cost by Region
		// --------------
		$lpac_pro_settings[] = array(
			'name'  => __( 'Cost by Region', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-star-filled premium-subsection',
			'desc'  => sprintf( __( 'Use the map to draw regions and set shipping costs for customers who fall within those regions.  %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-region' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'  => 'hr',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_shipping_cost_by_region_enabled',
			'type' => 'checkbox',
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'css'  => 'height: 400px;',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Make calculated fees taxable', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'Enabling this option will update your cart taxes with the relevant fee for the calculated shipping cost.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_cost_by_region_taxable',
			'type'     => 'checkbox',
			'css'      => 'max-width:120px;',
		);

		$lpac_pro_settings[] = array(
			'name'        => __( 'Default Region Color (HEX)', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'        => __( 'Default background color for drawn regions on the frontend of the website.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_shipping_regions_default_background_color',
			'type'        => 'color',
			'placeholder' => '#ff0000',
			'default'     => '#FF0000',
			'css'         => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'      => __( 'Draw Shipping Regions', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'      => 'div',
			'class'     => 'lpac-map',
			'desc'      => __( 'Use the controls at the top of the map to draw your shipping regions, set their shipping cost and background color. Click on a shipping region to update its cost. Close the label on a region to delete that region.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'css'       => 'height: 480px;',
			'is_option' => false,
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Shipping Methods', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_shipping_regions_shipping_methods',
			'class'   => 'wc-enhanced-select',
			'desc'    => sprintf( __( 'Select the Shipping Method(s) this feature applies to. If there is a cost already set on the shipping method, then that base cost will be added to the cost set for the region. NOTE: You need to have at least ONE created Shipping Zone with Shipping Methods attached to it. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-region' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'    => 'multiselect',
			'options' => Functions_Helper::getAvailableShippingMethods(),
			'css'     => 'min-width:300px;height: 100px',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Ship Only to Drawn Regions', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'If checked, a customer WILL NOT be able to place an order unless they choose a location that falls within any of your drawn regions. Pair this setting with the "Force Use of Map" general option for best results.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_ship_only_to_drawn_regions',
			'type'     => 'checkbox',
			'css'      => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'No Shipping Method Available Text', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => __( 'Enter the text that displays when a customer\'s location does not fall within any drawn shipping region.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_no_shipping_method_available_text',
			'default' => __( 'Unfortunately, we do not currently ship to your region.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'    => 'textarea',
			'css'     => 'min-width:700px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'No Shipping Method Error', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => __( 'Enter the text that displays when a customer tries to checkout without choosing a location within a drawn region.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_no_shipping_method_selected_error',
			'default' => __( 'We do not currently ship to your selected location. Please choose a different location on the map then try placing your order again. Please contact us if you need any help.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'    => 'textarea',
			'css'     => 'min-width:700px;',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Show Shipping Regions on Checkout Map', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_show_shipping_regions_on_checkout_map',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Display Shipping Regions Name', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_show_shipping_regions_name_on_checkout_map',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Display Shipping Regions Price', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_show_shipping_regions_cost_on_checkout_map',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Shipping Restrictions', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_shipping_restrictions',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$shipping_regions = get_option( 'lpac_shipping_regions', array() );
		if ( empty( $shipping_regions ) ) {

			$lpac_pro_settings[] = array(
				'name'      => __( 'Shipping Restrictions', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'        => 'lpac_regions_min_max_order_total_row_id',
				'text'      => sprintf( esc_html__( '%1$sPlease firstly draw some Shipping Regions to activate this feature.%2$s', 'map-location-picker-at-checkout-for-woocommerce' ), '<strong>', '</strong>' ),
				'type'      => 'info_text',
				'is_option' => false,
			);

		} else {
			$lpac_pro_settings[] = array(
				'name'                          => __( 'Shipping Restrictions', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'                            => 'lpac_regions_min_max_order_total',
				'row_id'                        => 'lpac_regions_min_max_order_total_row_id',
				'desc'                          => sprintf( __( 'Set the minimum and maximum order total a customer needs to have based on the region they are trying to checkout from. If a region only has a minimum order total, then simply leave the maximum order total field blank. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-region' target='_blank'>" . self::$learn_more . '</a>' ),
				'type'                          => 'repeater',
				'current_saved_settings'        => get_option( 'lpac_regions_min_max_order_total', array() ),
				// 'id_field'                      => 'store_location_id',
				'entity_name'                   => __( 'entry', 'map-location-picker-at-checkout-for-woocommerce' ),
				'table_columns'                 => array(
					'region_name_select'          => array(
						'name'     => __( 'Select region', 'map-location-picker-at-checkout-for-woocommerce' ),
						'readonly' => false,
					),
					'region_min_order_total_text' => array(
						'name'     => __( 'Minimum order total', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
						'readonly' => false,
					),
					'region_max_order_total_text' => array(
						'name'     => __( 'Maximum order total', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
						'readonly' => false,
					),
				),
				'select_field_dropdown_options' => get_option( 'lpac_shipping_regions', array() ),
				'option_element_id'             => 'id',
				'option_element_value'          => 'name',
				'select_element_id'             => 'region_id_select',
			);

		}

		$lpac_pro_settings[] = array(
			'name'     => __( 'Enable for Local Pickup', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'Enable this option if you would like the above Shipping Restrictions to also apply to Local Pickup shipping methods.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_enable_shipping_restrictions_local_pickup',
			'type'     => 'checkbox',
			'css'      => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Order Total Insufficient Notice', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => sprintf( __( "Enter the text for the notice that displays when a customer current order total is lower than what you have set for their detected region's %1\$sMinimum order total%2\$s. %3\$sNOTE: The minimum amount needed is automatically appended to the text on the checkout page.%4\$s", 'map-location-picker-at-checkout-for-woocommerce' ), '<strong>', '</strong>', '<code>', '</code>' ),
			'id'      => 'lpac_order_total_insufficient_text',
			'default' => __( 'Order total for your selected region needs to be at least', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'    => 'textarea',
			'css'     => 'min-width:700px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Order Total Beyond Limit Notice', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => sprintf( __( "Enter the text for the notice that displays when a customer current order total is beyond what you have set for their detected region's %1\$sMaximum order total%2\$s. %3\$sNOTE: The maximum allowed order total is automatically appended to the text on the checkout page.%4\$s", 'map-location-picker-at-checkout-for-woocommerce' ), '<strong>', '</strong>', '<code>', '</code>' ),
			'id'      => 'lpac_order_total_limit_passed_text',
			'default' => __( 'Order total for your selected region needs to be at most', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'    => 'textarea',
			'css'     => 'min-width:700px;',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Free Shipping for Regions', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_free_shipping_for_regions',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		if ( empty( $shipping_regions ) ) {

			$lpac_pro_settings[] = array(
				'name'      => __( 'Free Shipping for Regions', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'        => 'lpac_regions_free_shipping_row_id',
				'text'      => sprintf( esc_html__( '%1$sPlease firstly draw some Shipping Regions to activate this feature.%2$s', 'map-location-picker-at-checkout-for-woocommerce' ), '<strong>', '</strong>' ),
				'type'      => 'info_text',
				'is_option' => false,
			);

		} else {
			$lpac_pro_settings[] = array(
				'name'                          => __( 'Free Shipping for Regions', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'                            => 'lpac_regions_free_shipping',
				'row_id'                        => 'lpac_regions_free_shipping_row_id',
				'desc'                          => sprintf( __( 'Set the minimum and maximum order total a customer needs to have to receive Free Shipping. If you do not want to limit how large a customer\'s order can be to receive Free Shipping, then leave the maximum order total field blank. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#free-shipping-for-regions' target='_blank'>" . self::$learn_more . '</a>' ),
				'type'                          => 'repeater',
				'current_saved_settings'        => get_option( 'lpac_regions_free_shipping', array() ),
				// 'id_field'                      => 'store_location_id',
				'entity_name'                   => __( 'entry', 'map-location-picker-at-checkout-for-woocommerce' ),
				'table_columns'                 => array(
					'region_name_select'          => array(
						'name'     => __( 'Select region', 'map-location-picker-at-checkout-for-woocommerce' ),
						'readonly' => false,
					),
					'region_min_order_total_text' => array(
						'name'     => __( 'Minimum order total', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
						'readonly' => false,
					),
					'region_max_order_total_text' => array(
						'name'     => __( 'Maximum order total', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
						'readonly' => false,
					),
				),
				'select_field_dropdown_options' => get_option( 'lpac_shipping_regions', array() ),
				'option_element_id'             => 'id',
				'option_element_value'          => 'name',
				'select_element_id'             => 'region_id_select',
			);

		}

		// Holds the object created by JS when drawing regions.
		$lpac_pro_settings[] = array(
			'id'   => 'lpac_shipping_regions_updated_obj',
			'type' => 'text',
			'css'  => 'display: none;',
		);

		// --------------
		// Cost by Distance
		// --------------
		$lpac_pro_settings[] = array(
			'name'  => __( 'Cost by Distance', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-star-filled premium-subsection',
			'desc'  => sprintf( __( 'Various workflows that let you charge a customer based on their distance. Select either "Standard", "Range" or "Store". %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-distance' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'  => 'hr',
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_shipping_cost_by_distance_feature',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'            => __( 'Configuration', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'              => 'lpac_cost_by_distance_configuration',
			'text'            => __( 'Configure the base settings that will allow you to use this feature.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'            => 'info_text',
			'option_name_css' => 'font-size: 18px; font-weight: 800',
			'css'             => 'font-size: 18px; font-weight: 800',
			'is_option'       => false,
		);

		$lpac_pro_settings[] = array(
			'name'              => __( 'Distance Matrix API Key', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'              => __( 'This is a specific API key created just for usage of Google\'s Distance Matrix API. The key should have no referrer restrictions set on it.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'                => 'lpac_distance_matrix_api_key',
			'placeholder'       => 'AIzaSyD8seU-lym435g...',
			'type'              => ( LPAC_DEBUG ) ? 'text' : 'password',
			'custom_attributes' => array(
				'autocomplete' => 'new-password',
			),
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Shipping Methods', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_distance_matrix_shipping_methods',
			'class'   => 'wc-enhanced-select',
			'desc'    => sprintf( __( 'Select the Shipping Method(s) this feature applies to. If there is a cost already set on the shipping method, then that base cost will be added to the cost set for the region. NOTE: You need to have at least ONE created Shipping Zone with Shipping Methods attached to it. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-distance' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'    => 'multiselect',
			'options' => Functions_Helper::getAvailableShippingMethods(),
		);

		$lpac_pro_settings[] = array(
			'name'        => __( 'Origin Coordinates', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'        => sprintf( __( 'Enter the coordinates of the location from which the delivery/pickup will begin. This might be the coordinates for your physical store or business. If you have multiple origin locations (example multiple stores) then create them in the "Store Locations" tab and enable the option to show store selector on the checkout page. The plugin will use the selected store location by the customer as the origin. You can find the coordinates for a location %1$sHere >>%2$s', 'map-location-picker-at-checkout-for-woocommerce' ), '<a href="https://gps-coordinates.org/" target="_blank">', '</a>' ),
			'id'          => 'lpac_distance_matrix_store_origin_cords',
			'placeholder' => '14.024519,-60.974876',
			'type'        => 'text',
			'css'         => 'max-width:180px;',
		);

		// Option to use store selector as origin location if the "Display Store Selector on Checkout Page" option is turned on.
		if ( Store_Locations::showStoreSelectorInCheckoutEnabled() ) {
			$lpac_pro_settings[] = array(
				'name'     => __( 'Use Store Selector as Origin', 'map-location-picker-at-checkout-for-woocommerce' ),
				'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
				'desc_tip' => sprintf( esc_html__( 'Use the Store selected in the Store Selector dropdown as the "Origin" location when using the Cost by Distance feature. This setting will override the "Origin Coordinates" option set above. %s', 'map-location-picker-at-checkout-for-woocommerce' ), '<a href="https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#use-store-selector-as-origin" target="_blank">' . self::$learn_more . '</a>' ),
				'id'       => 'lpac_enable_use_store_selector_as_origin',
				'type'     => 'checkbox',
				'css'      => 'max-width:200px;',
			);
		}

		$lpac_pro_settings[] = array(
			'name'    => __( 'Distance Unit', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => __( 'Select your preferred unit. By Default the Distance Matrix API returns values in Metric Units. If Miles is selected then Kilometers will be converted into Miles where 1 Kilometer is equivalent to 0.621371 Miles.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_distance_matrix_distance_unit',
			'type'    => 'select',
			'options' => array(
				'km'   => __( 'Kilometers', 'map-location-picker-at-checkout-for-woocommerce' ),
				'mile' => __( 'Miles', 'map-location-picker-at-checkout-for-woocommerce' ),
			),
			'css'     => 'max-width:120px;',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Display Cost & Unit in Shipping Label', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'Enabling this option will append the "Cost Per Unit Distance" and "Distance Unit" to the end of the Shipping Method name. E.g: Flat Rate ($1.50/Km). NOTE: This option does not apply to the "Cost by Distance Range" feature since it uses fixed costs.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_show_distance_unit_cost_in_checkout',
			'type'     => 'checkbox',
			'css'      => 'max-width:120px;',
		);

		$lpac_pro_settings[] = array(
			'name'        => __( 'Travel Mode', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'        => __( 'Enter the travel mode you will be using. Though multiple options are provided, you might always want to use "driving" for best results.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_distance_matrix_travel_mode',
			'placeholder' => '2.50',
			'type'        => 'select',
			'options'     => array(
				'driving'   => __( 'Driving', 'map-location-picker-at-checkout-for-woocommerce' ),
				'bicycling' => __( 'Bicycling', 'map-location-picker-at-checkout-for-woocommerce' ),
				'walking'   => __( 'Walking', 'map-location-picker-at-checkout-for-woocommerce' ),
			),
			'css'         => 'max-width:120px;',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Make calculated fees taxable', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'Enabling this option will update your cart taxes with the relevant fee for the calculated shipping cost.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_cost_by_distance_taxable',
			'type'     => 'checkbox',
			'css'      => 'max-width:120px;',
		);

		// --------------
		// Cost by Distance Standard
		// --------------
		$lpac_pro_settings[] = array(
			'name'  => __( 'Standard', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-editor-break mirror',
			'type'  => 'hr',
			'id'    => 'lpac_cost_by_distance_standard_hr',
			'desc'  => sprintf( __( 'Charge customers based on the distance between your store and their location. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-distance' target='_blank'>" . self::$learn_more . '</a>' ),
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_cost_by_distance_standard',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'        => __( 'Cost Per Unit Distance', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
			'desc'        => __( 'Enter the price you wish to charge per Kilometer/Mile. The default store currency will be used.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_distance_matrix_cost_per_unit',
			'placeholder' => '0.00',
			'type'        => 'text',
			'css'         => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Limit Shipping Distance', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'If checked, you can limit the maximum distance you will ship to. The distance will be calculated in the unit you set above.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_limit_shipping_distance',
			'type'     => 'checkbox',
			'css'      => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'        => __( 'Maximum Distance', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'        => __( 'Set the maximum distance you will ship to. The unit used will be the same as the unit you set above.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'          => 'lpac_max_shipping_distance',
			'placeholder' => '',
			'type'        => 'text',
			'css'         => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'No Shipping Method Available Text', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => __( 'Enter the text that displays when a customer\'s location is beyond your max shipping distance.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_distance_cost_no_shipping_method_available_text',
			'default' => __( 'Unfortunately, we do not currently ship this far.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'    => 'textarea',
			'css'     => 'min-width:700px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'No Shipping Method Error', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'    => __( 'Enter the text that displays when a customer tries to checkout with a location that is beyond your max shipping distance.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_distance_cost_no_shipping_method_selected_error',
			'default' => __( 'We do not currently ship to your selected location. Please choose a location closer to our main store then try placing your order again. Please contact us if you need any help.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'type'    => 'textarea',
			'css'     => 'min-width:700px;',
		);

		$lpac_pro_settings[] = array(
			'name'        => __( 'Free Shipping for Distance', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'        => sprintf( __( 'If a customer distance falls within the range you set above, then their shipping cost will be free. The unit used will be the same as the unit you set above. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#free-shipping-for-distance' target='_blank'>" . self::$learn_more . '</a>' ),
			'id'          => 'lpac_max_free_shipping_distance',
			'placeholder' => '',
			'type'        => 'text',
			'css'         => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Subtract Free Shipping Distance From Total Distance', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => sprintf( __( 'Enabling this option will substract the "Free Shipping for Distance" value from the total distance of the customer. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#subtract-free-shipping-distance-from-total-distance' target='_blank'>" . self::$learn_more . '</a>' ),
			'id'       => 'lpac_subtract_free_shipping_distance',
			'type'     => 'checkbox',
			'css'      => 'max-width:80px;',
		);

		// --------------
		// Cost by Distance Range
		// --------------
		$lpac_pro_settings[] = array(
			'name'  => __( 'Range', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-editor-break mirror',
			'type'  => 'hr',
			'id'    => 'lpac_cost_by_distance_range_hr',
			'desc'  => sprintf( __( 'Create your distance ranges and set the Price. It is highly recommended that you read the official documentation for this feature to fully understand how it works. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-distance-range' target='_blank'>" . self::$learn_more . '</a>' ),
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_cost_by_distance_range',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$distance_unit = get_option( 'lpac_distance_matrix_distance_unit', 'km' );

		$lpac_pro_settings[] = array(
			'name'                   => __( 'Create Ranges', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'                     => 'lpac_cost_by_distance_range_rangelist',
			'row_id'                 => 'lpac_cost_by_distance_range_row_id',
			'type'                   => 'repeater',
			'current_saved_settings' => get_option( 'lpac_cost_by_distance_range_rangelist', array() ),
			'entity_name'            => __( 'range', 'map-location-picker-at-checkout-for-woocommerce' ),
			'table_columns'          => array(
				'start_range_text' => array(
					'name'     => __( 'Start range', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . $distance_unit . ')',
					'readonly' => false,
				),
				'end_range_text'   => array(
					'name'     => __( 'End range', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . $distance_unit . ')',
					'readonly' => false,
				),
				'range_price_text' => array(
					'name'     => __( 'Price', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
					'readonly' => false,
				),
				'should_calculate_per_distance_unit_checkbox' => array(
					'name'     => __( 'Calculate per', 'map-location-picker-at-checkout-for-woocommerce' ) . ' ' . $distance_unit,
					'readonly' => false,
				),
			),
		);

		// --------------
		// Cost by Store Distance
		// --------------
		$lpac_pro_settings[] = array(
			'name'  => __( 'Store ', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-editor-break mirror',
			'type'  => 'hr',
			'id'    => 'lpac_cost_by_store_distance_pricing_hr',
			'desc'  => sprintf( __( 'Select your store location(s) and set the unit price for shipping from that location. The shipping cost for the order will be calculated automagically when a customer selects their desired store at checkout. This setting will override the "Origin Coordinates" and "Cost Per Unit Distance" you set above. If making use of this feature, be sure to set the details for your main store. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-store-distance' target='_blank'>" . self::$learn_more . '</a>' ),
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_cost_by_store_distance',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$store_locations = get_option( 'lpac_store_locations', array() );
		$section         = $_REQUEST['section'] ?? ''; // We need to check this so that the below logic only happens on our shipping page.

		if ( $section === 'shipping' && empty( $store_locations ) ) {

			$lpac_pro_settings[] = array(
				'name'      => __( 'Store Locations', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'        => 'lpac_cost_by_store_distance_delivery_pricing_row_id',
				'text'      => sprintf( esc_html__( '%1$sPlease firstly create some store locations in the "Store Locations" tab %2$sHERE%3$s.%4$s', 'map-location-picker-at-checkout-for-woocommerce' ), '<strong>', '<a href="' . admin_url( '/admin.php?page=wc-settings&tab=lpac_settings&section=store_locations' ) . '">', '</a>', '</strong>' ),
				'type'      => 'info_text',
				'is_option' => false,
			);

		} else {

			$lpac_pro_settings[] = array(
				'name'                          => __( 'Store Locations', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'                            => 'lpac_cost_by_store_distance_delivery_prices',
				'row_id'                        => 'lpac_cost_by_store_distance_delivery_pricing_row_id',
				'type'                          => 'repeater',
				'current_saved_settings'        => Shipping_Settings::getCostByStoreDistancePricing(),
				'id_field'                      => 'store_location_id',
				'entity_name'                   => __( 'entry', 'map-location-picker-at-checkout-for-woocommerce' ),
				'table_columns'                 => array(
					'store_name_select' => array(
						'name'     => __( 'Select store', 'map-location-picker-at-checkout-for-woocommerce' ),
						'readonly' => false,
					),
					'store_price_text'  => array(
						'name'     => __( 'Set cost per unit distance', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
						'readonly' => false,
					),
				),
				'select_field_dropdown_options' => $store_locations,
				'option_element_id'             => 'store_location_id',
				'option_element_value'          => 'store_name_text',
				'select_element_id'             => 'store_location_id_select',
			);

		}

		// --------------
		// Cost by Store Location
		// --------------
		$lpac_pro_settings[] = array(
			'name'  => __( 'Cost by Store Location', 'map-location-picker-at-checkout-for-woocommerce' ),
			'class' => 'dashicons-before dashicons-star-filled premium-subsection',
			'type'  => 'hr',
			'id'    => 'lpac_cost_by_store_location_hr',
			'desc'  => sprintf( __( 'Select your store location(s) and set the price for shipping from that store. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-store-location' target='_blank'>" . self::$learn_more . '</a>' ),
		);

		$lpac_pro_settings[] = array(
			'name' => __( 'Enable Feature', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc' => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'   => 'lpac_enable_cost_by_store_location',
			'type' => 'checkbox',
			'css'  => 'max-width:80px;',
		);

		$lpac_pro_settings[] = array(
			'name'    => __( 'Shipping Methods', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'      => 'lpac_cost_by_store_location_shipping_methods',
			'class'   => 'wc-enhanced-select',
			'desc'    => sprintf( __( 'Select the Shipping Method(s) this feature applies to. If there is a cost already set on the shipping method, then that base cost will be added to the cost set for the region. NOTE: You need to have at least ONE created Shipping Zone with Shipping Methods attached to it. %s', 'map-location-picker-at-checkout-for-woocommerce' ), "<a href='https://lpacwp.com/docs/shipping/?utm_source=shippingtab&utm_medium=lpacdashboard&utm_campaign=prodocs#cost-by-store-location' target='_blank'>" . self::$learn_more . '</a>' ),
			'type'    => 'multiselect',
			'options' => Functions_Helper::getAvailableShippingMethods(),
			'css'     => 'min-width:300px;height: 100px',
		);

		$lpac_pro_settings[] = array(
			'name'     => __( 'Make calculated fees taxable', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc'     => __( 'Yes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'desc_tip' => __( 'Enabling this option will update your cart taxes with the relevant fee for the calculated shipping cost.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'id'       => 'lpac_cost_by_store_location_taxable',
			'type'     => 'checkbox',
			'css'      => 'max-width:120px;',
		);

		if ( $section === 'shipping' && empty( $store_locations ) ) {

			$lpac_pro_settings[] = array(
				'name'      => __( 'Store Locations', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'        => 'lpac_cost_by_store_location_delivery_prices_row_id',
				'text'      => sprintf( esc_html__( '%1$sPlease firstly create some store locations in the "Store Locations" tab %2$sHERE%3$s.%4$s', 'map-location-picker-at-checkout-for-woocommerce' ), '<strong>', '<a href="' . admin_url( '/admin.php?page=wc-settings&tab=lpac_settings&section=store_locations' ) . '">', '</a>', '</strong>' ),
				'type'      => 'info_text',
				'is_option' => false,
			);

		} else {

			$lpac_pro_settings[] = array(
				'name'                          => __( 'Store Locations', 'map-location-picker-at-checkout-for-woocommerce' ),
				'id'                            => 'lpac_cost_by_store_location_delivery_prices',
				'row_id'                        => 'lpac_cost_by_store_location_delivery_prices_row_id',
				'type'                          => 'repeater',
				'current_saved_settings'        => get_option( 'lpac_cost_by_store_location_delivery_prices', array() ),
				'id_field'                      => 'store_location_id',
				'entity_name'                   => __( 'entry', 'map-location-picker-at-checkout-for-woocommerce' ),
				'table_columns'                 => array(
					'store_name_select' => array(
						'name'     => __( 'Select store', 'map-location-picker-at-checkout-for-woocommerce' ),
						'readonly' => false,
					),
					'store_price_text'  => array(
						'name'     => __( 'Location shipping price', 'map-location-picker-at-checkout-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
						'readonly' => false,
					),
				),
				'select_field_dropdown_options' => $store_locations,
				'option_element_id'             => 'store_location_id',
				'option_element_value'          => 'store_name_text',
				'select_element_id'             => 'store_location_id_select',
			);

		}

		$lpac_pro_settings[] = array(
			'type' => 'sectionend',
			'id'   => 'lpac_shipping_settings_section_end',
		);
		return $lpac_pro_settings;
	}

}


<?php
/**
 * Class responsible for creating map builder metaboxes.
 *
 * Author:          Uriahs Victor
 * Created on:      02/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.7.0
 * @package Views
 */

namespace Lpac\Pro\Views\Admin\Custom_Post_Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac\Models\Plugin_Settings\Store_Locations;
use Lpac\Pro\Models\Plugin_Settings\Shipping_Settings;

/**
 * View responsible for creating metaboxes in Map Builder.
 *
 * @package Lpac\Pro\Views\Admin\Metaboxes
 * @since 1.7.0
 */
class Map_Builder {

	/**
	 * Get the saved setting for a particular shortcode.
	 *
	 * @return array
	 * @since 1.7.0
	 */
	private static function get_saved_settings() : array {
		return get_post_meta( get_the_ID(), 'kikote_map_builder_shortcode_settings', true ) ?: array();
	}

	/**
	 * Create our shipping region option.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	private function create_shipping_regions_option() : void {
		$regions = Shipping_Settings::getShippingRegions();

		$saved = self::get_saved_settings()['shipping_settings']['shipping_regions'] ?? array();

		$regions = array_column( $regions, 'name', 'id' );
		?>
		<div>
		<label for='shipping-regions'><?php esc_html_e( 'Show Regions', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
		<select style='width: 400px' name='shipping_regions[]' id='shipping-regions' multiple='multiple'>
		<?php
		if ( empty( $regions ) ) {
			echo "<option value='' disabled>" . '==' . esc_html__( 'No regions created', 'map-location-picker-at-checkout-for-woocommerce' ) . '==' . '</option>';
		} else {
			foreach ( $regions as $region_id => $region_name ) {
				if ( is_array( $saved ) && in_array( $region_id, $saved ) ) {
					echo "<option value='" . esc_attr( $region_id ) . "' selected>" . esc_html( $region_name ) . '</option>';
				} else {
					echo "<option value='" . esc_attr( $region_id ) . "'>" . esc_html( $region_name ) . '</option>';
				}
			}
		}

		?>
		</select>
		</div>
		<?php
	}

	/**
	 * Create our shipping region option.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	private function create_store_locations_option(): void {
		$all_store_locations = Store_Locations::getStoreLocations();

		$all_store_locations      = array_column( $all_store_locations, 'store_name_text', 'store_location_id' );
		$selected_store_locations = self::get_saved_settings()['shipping_settings']['store_locations'] ?? array();
		echo '<div>';
		echo "<label for='store_locations'>" . esc_html__( 'Show Store Locations', 'map-location-picker-at-checkout-for-woocommerce' ) . '</label>';
		echo "<select style='width: 400px' name='store_locations[]' id='store-locations' multiple='multiple'>";
		if ( empty( $all_store_locations ) ) {
			echo "<option value='' disabled>" . '==' . esc_html__( 'No store locations created', 'map-location-picker-at-checkout-for-woocommerce' ) . '==' . '</option>';
		} else {
			foreach ( $all_store_locations as $store_location_id => $store_location_name ) {
				foreach ( $selected_store_locations as $index => $store_details ) {
					if ( $store_location_id === $store_details['store_location_id'] ) {
						echo "<option value='" . esc_attr( $store_location_id ) . "' selected>" . esc_html( $store_location_name ) . '</option>';
						continue 2; // Prevents duplicates by causing top level foreach to advance to next item in array.
					}
				}
				echo "<option value='" . esc_attr( $store_location_id ) . "'>" . esc_html( $store_location_name ) . '</option>';
			}
		}
		echo '</select>';
		echo '</div>';
	}

	/**
	 * Create our shipping settings.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	private function create_shipping_settings_options(): void {
		$saved = self::get_saved_settings()['shipping_settings'] ?? array();
		?>
		<div>
			<label for='display-region-name'><?php esc_html_e( 'Display Shipping Regions Name', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='display-region-name' name='shipping_regions_settings[display_region_name]' type='checkbox' <?php echo esc_attr( ( ! empty( $saved['shipping_regions_settings']['display_region_name'] ) ) ? 'checked' : '' ); ?> />
		</div>
		<div>
			<label for='display-region-price'><?php esc_html_e( 'Display Shipping Regions Price', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='display-region-price' name='shipping_regions_settings[display_region_price]' type='checkbox' <?php echo esc_attr( ( ! empty( $saved['shipping_regions_settings']['display_region_price'] ) ) ? 'checked' : '' ); ?> />
		</div>
		<?php
	}

	/**
	 * Create our metabox with map settings.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	private function create_map_display_options(): void {
		$saved = self::get_saved_settings()['display_settings'] ?? array();
		if ( empty( $saved ) ) {
			$saved['map_type'] = 'roadmap';
		}
		?>
	<div class='grid-container'>
		<div class='grid-item'>
			<label for='map-id'><?php esc_html_e( 'Map ID', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='map-id' name='google_map_id' type='text' value='<?php echo esc_attr( $saved['google_map_id'] ?? '' ); ?>'/>
			<p style='font-size: 10px; margin: 5px 0 0 0'><a href='https://lpacwp.com/docs/display-settings/#12-toc-title' target="_blank"><?php esc_html_e( 'Learn more', 'map-location-picker-at-checkout-for-woocommerce' ); ?></a></p>
		</div>
		<div class='grid-item'>
			<label for='zoom'><?php esc_html_e( 'Zoom Level', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='zoom' name='zoom' type='number' min='1' max="20" value='<?php echo esc_attr( $saved['zoom'] ?? 10 ); ?>'/>
		</div>
		<div class='grid-item'>
			<label for='map-type'><?php esc_html_e( 'Map Type', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<select name="map_type" id="map-type">
				<option value="roadmap" <?php echo esc_attr( ( ( $saved['map_type'] === 'roadmap' ) ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'Road Map', 'map-location-picker-at-checkout-for-woocommerce' ); ?></option>
				<option value="satellite" <?php echo esc_attr( ( ( $saved['map_type'] === 'satellite' ) ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'Satellite', 'map-location-picker-at-checkout-for-woocommerce' ); ?></option>
				<option value="hybrid" <?php echo esc_attr( ( ( $saved['map_type'] === 'hybrid' ) ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'Hybrid', 'map-location-picker-at-checkout-for-woocommerce' ); ?></option>
				<option value="terrain" <?php echo esc_attr( ( ( $saved['map_type'] === 'terrain' ) ) ? 'selected' : '' ); ?> ><?php esc_html_e( 'Terrain', 'map-location-picker-at-checkout-for-woocommerce' ); ?></option>
			</select>
		</div>
		<div class='grid-item'>
			<label for='background-color'><?php esc_html_e( 'Background Color', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='background-color' name='background_color' type='text' size='8' value='<?php echo esc_attr( $saved['background_color'] ?? '' ); ?>' />
		</div>
		<div class='grid-item'>
			<label for='height'><?php esc_html_e( 'Height', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='height' name='height' type='number' min='10' value='<?php echo esc_attr( $saved['height'] ?? 300 ); ?>' />
		</div>
		<div class='grid-item'>
			<label for='width'><?php esc_html_e( 'Width', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='width' name='width' type='number' value='<?php echo esc_attr( $saved['width'] ?? '' ); ?>' />
			<p style='font-size: 10px; margin: 0'><?php esc_html_e( 'Leave empty to make width 100%', 'map-location-picker-at-checkout-for-woocommerce' ); ?></p>
		</div>
		<div class='grid-item'>
			<label for='clickable-icons'><?php esc_html_e( 'Clickable Icons', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='clickable-icons' name='clickable_icons' type='checkbox' <?php echo esc_attr( ( ! empty( $saved['clickable_icons'] ) ) ? 'checked' : '' ); ?> />
		</div>
		<div class='grid-item'>
			<label for='streetview-control'><?php esc_html_e( 'Show Streetview Control', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
			<input id='streetview-control' name='streetview_control' type='checkbox' <?php echo esc_attr( ( ! empty( $saved['streetview_control'] ) ) ? 'checked' : '' ); ?> />
		</div>
	</div>
	<div>
		<label for='default-coordinates'><?php esc_html_e( 'Default Coordinates', 'map-location-picker-at-checkout-for-woocommerce' ); ?></label>
		<input id='default-coordinates' name='default_coordinates[latitude]' type='text' placeholder='<?php esc_attr_e( 'Latitude', 'map-location-picker-at-checkout-for-woocommerce' ); ?>' value='<?php echo esc_attr( $saved['default_coordinates']['latitude'] ?? '' ); ?>' />
		<input id='default-coordinates' name='default_coordinates[longitude]' type='text' placeholder='<?php esc_attr_e( 'Longitude', 'map-location-picker-at-checkout-for-woocommerce' ); ?>' value='<?php echo esc_attr( $saved['default_coordinates']['longitude'] ?? '' ); ?>' />
	</div>
		<?php
	}

	/**
	 * Create our different Metaboxes
	 *
	 * @return void
	 * @since 1.7.0
	 */
	public function create_metaboxes() : void {
		add_meta_box( 'kikote_map_builder_preview', __( 'Preview', 'map-location-picker-at-checkout-for-woocommerce' ), array( $this, 'map_preview_metabox' ), 'kikote-maps', 'normal', 'high' );
		add_meta_box( 'kikote_shipping', __( 'Shipping Settings', 'map-location-picker-at-checkout-for-woocommerce' ), array( $this, 'shipping_metabox' ), 'kikote-maps', 'normal', 'high' );
		add_meta_box( 'kikote_map_display', __( 'Display Settings', 'map-location-picker-at-checkout-for-woocommerce' ), array( $this, 'map_display_settings_metabox' ), 'kikote-maps', 'normal', 'high' );
		add_meta_box( 'kikote_map_shortcode', __( 'Shortcode', 'map-location-picker-at-checkout-for-woocommerce' ), array( $this, 'map_shortcode_metabox' ), 'kikote-maps', 'side', 'default' );
	}

	/**
	 * Add a map preview metabox.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	public function map_preview_metabox(): void {
		?>
		<div id="wrap" style="display: block; text-align: center;">
			<div class="kikote-map-builder-preview" style="display: inline-block; border: 1px solid #eee; width: 100%; height:345px;"></div>
			<p style='font-weight: 700'><?php esc_html_e( 'Save your changes to update the preview', 'map-location-picker-at-checkout-for-woocommerce' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Add a map shortcode metabox.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	public function map_shortcode_metabox(): void {
		echo "<div style='padding: 5px'>";
		echo "<code style='font-size: 18px;'>[kikote_map id='" . get_the_ID() . "']</code>";
		echo '</div>';
	}

	/**
	 * Add a shipping Method Metabox.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	public function shipping_metabox() : void {
		echo "<div class='grid-container'>";
		$this->create_shipping_regions_option();
		$this->create_store_locations_option();
		echo '</div>';
		echo '<div id="shipping-settings">';
		$this->create_shipping_settings_options();
		echo '</div>';
	}

	/**
	 * Add Map Display Metabox.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	public function map_display_settings_metabox() : void {
		$this->create_map_display_options();
	}

}

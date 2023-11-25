<?php
/**
 * Create plugin shortcodes.
 *
 * Author:          Uriahs Victor
 * Created on:      09/09/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.6.4
 * @package Views
 */

namespace Lpac\Pro\Views\Frontend;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcodes Class.
 *
 * Responsible for creating frontend-facing shortcodes for Free plugin.
 *
 * @package Lpac\Pro\Views
 * @since 1.7.0
 */
class Shortcodes {

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	public function __construct() {
		add_shortcode( 'kikote_map', array( $this, 'map_builder_shortcode' ) );
	}

	/**
	 * Shortcode for displaying a map.
	 *
	 * @param mixed $atts
	 * @return string
	 *
	 * @since 1.7.0
	 */
	public function map_builder_shortcode( $atts ): string {
		$default = array(
			'id' => '',
		);

		$attributes = shortcode_atts( $default, $atts );
		$map_id     = $attributes['id'];

		$shortcode_settings = get_post_meta( $map_id, 'kikote_map_builder_shortcode_settings', true );

		if ( ! empty( $shortcode_settings['display_settings']['height'] ) ) {
			$map_height = $shortcode_settings['display_settings']['height'] . 'px';
		} else {
			$map_height = '300px';
		}

		if ( ! empty( $shortcode_settings['display_settings']['width'] ) ) {
			$map_width = $shortcode_settings['display_settings']['width'] . 'px';
		} else {
			$map_width = '100%';
		}

		$map_settings = json_encode( $shortcode_settings );

		$map_markup = "<div style='height: {$map_height}; width: {$map_width};' class='kikote-shortcode-map' data-map-id= '" . esc_attr( $map_id ) . "' data-map-settings='" . esc_attr( $map_settings ) . "'></div>";

		wp_enqueue_script( LPAC_PLUGIN_NAME . '-map-builder' );

		return $map_markup;
	}
}

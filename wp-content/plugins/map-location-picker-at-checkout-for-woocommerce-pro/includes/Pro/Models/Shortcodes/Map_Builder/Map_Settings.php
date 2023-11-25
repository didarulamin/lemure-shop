<?php
/**
 * Class responsible for retrieving map related settings for shortcode display.
 *
 * Author:          Uriahs Victor
 * Created on:      10/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.7.0
 * @package package
 */

namespace Lpac\Pro\Models\Shortcodes\Map_Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Map_Settings {

	/**
	 * Settings saved for a specific shortcode.
	 *
	 * @var array
	 * @since 1.7.0
	 */
	private array $shortcode_settings;

	/**
	 * Class constructor.
	 *
	 * @param mixed $post_id
	 * @return void
	 * @since 1.7.0
	 */
	public function __construct( $post_id ) {
		$this->shortcode_settings = get_post_meta( $post_id, 'kikote_map_builder_shortcode_settings', true );
	}

	/**
	 * Get the selected drawn regions that should be shown on our shortcode map.
	 *
	 * @return array
	 * @since 1.7.0
	 */
	public function get_selected_drawn_regions(): array {
		$regions = $this->shortcode_settings['shipping_settings']['shipping_regions'] ?? array();

		if ( empty( $regions ) ) {
			return array();
		}
		return $regions;
	}
}

<?php
/**
 * Houses model methods for saving shipping region drawing.
 *
 * Author:          Uriahs Victor
 * Created on:      16/01/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.4
 * @package Lpac\Pro\Models
 */

namespace Lpac\Pro\Models;

/**
 * Class Draw Shipping Zones.
 */
class Draw_Shipping_Region {

	/**
	 * Get saved Shipping regions;
	 *
	 * @return mixed
	 */
	public function get_saved_regions() {
		return get_option( 'lpac_shipping_regions', array() );
	}

	/**
	 * Save Shipping Region details to Database.
	 *
	 * @param array $region_details
	 * @return bool
	 */
	public function save_shipping_regions( array $region_details ) {
		$updated = update_option( 'lpac_shipping_regions', $region_details );
		if ( get_option( 'lpac_shipping_cost_by_region_enabled' ) !== 'yes' ) {
			update_option( 'lpac_shipping_cost_by_region_enabled', 'yes' );
		}
		return $updated;
	}

}

<?php
/**
 * PRO Model file used to get the store locations settings of the plugin.
 *
 * Author:          Uriahs Victor
 * Created on:      12/07/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.8.2
 * @package Models
 */
namespace Lpac\Pro\Models\Plugin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Model Class responsible for getting PRO Store Locations settings.
 *
 * @package Lpac\Pro\Models\Plugin_Settings
 * @since 1.8.2
 */
class StoreLocations {

	/**
	 * Get the "Use Store Selector as Origin for Cost by Distance Workflow" option.
	 *
	 * @return bool
	 * @since 1.8.2
	 */
	public static function originFromStoreSelectorEnabled(): bool {
		$value = get_option( 'lpac_enable_use_store_selector_as_origin', false );
		return filter_var( $value, FILTER_VALIDATE_BOOLEAN );
	}
}

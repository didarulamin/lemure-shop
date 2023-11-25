<?php
/**
 * Handles checkout page related logic.
 *
 * Author:          Uriahs Victor
 * Created on:      06/11/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.3.4
 * @package Controllers
 */

namespace Lpac\Pro\Controllers\Checkout_Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class Checkout Page Controller.
 */
class Controller {

	/**
	 * Clear the shipping rate cache.
	 *
	 * @param mixed $packages
	 * @return array
	 * @since 1.8.6
	 */
	public function clearShippingRateCache( $packages ): array {
		foreach ( $packages as &$package ) {
			$package['rate_cache'] = wp_rand();
		}
		unset( $package );
		return $packages;
	}

}

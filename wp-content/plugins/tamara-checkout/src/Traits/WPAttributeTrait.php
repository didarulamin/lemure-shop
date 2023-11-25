<?php


namespace Tamara\Wp\Plugin\Traits;

use Tamara\Wp\Plugin\Helpers\MoneyHelper;
use WP_Query;

trait WPAttributeTrait {
	/**
	 * @var string Text domain to be used with WordPress translation function __()
	 */
	public $textDomain = 'tamara';

	public function getCurrentUserRegisterDate() {
		if ( ! is_user_logged_in() ) {
			return null;
		}

		$currentUserId = get_current_user_id() ?? null;

		return Date( 'd-m-Y',
				strtotime( get_the_author_meta( 'user_registered', $currentUserId ) ) )
				?? Date( 'd-m-Y', time() );
	}

	public function currentUserHasDeliveredOrder() {
		if ( ! is_user_logged_in() ) {
			return null;
		}

		$currentUserId  = get_current_user_id() ?? null;
		$args           = [
			'customer_id' => $currentUserId,
			'post_status' => [ 'shipped', 'completed', 'wc-shipped', 'wc-completed' ],
			'post_type'   => 'shop_order',
			'return'      => 'ids',
			'limit' => 1,
			'numberposts' => 1,
		];
		$orderCompleted = count( wc_get_orders( $args ) );

		return ! ! ($orderCompleted > 0);
	}

	public function currentUserIsEmailVerified() {
		return null;
	}

	public function getCurrentUserTotalOrderCount() {
		if ( ! is_user_logged_in() ) {
			return null;
		}

		$currentUserId = get_current_user_id() ?? null;
		$args          = [
			'customer_id' => $currentUserId,
			'post_status' => [ 'wc-pending', 'wc-processing', 'wc-on-hold', 'wc-completed', 'wc-shipped', 'wc-delivered', 'wc-refunded' ],
			'post_type'   => 'shop_order',
			'numberposts' => -1,
			'limit' => -1,
			'return'      => 'ids',
		];

		return count( wc_get_orders( $args ) );
	}

	public function getCurrentUserDateOfFirstTransaction() {
		if ( ! is_user_logged_in() ) {
			return null;
		}

		$currentUserId = get_current_user_id() ?? null;
		$args          = [
			'customer_id' => $currentUserId,
			'post_type'   => 'shop_order',
			'limit' => 1,
			'numberposts' => 1,
			'orderby'     => 'date',
			'order'       => 'ASC'
		];
		$orders        = wc_get_orders( $args );
		if ( !empty($orders) ) {
			return Date( 'd-m-Y', strtotime( $orders[0]->get_date_created() ) ) ?? Date( 'd-m-Y', time() );
		}
	}

	public function isExistingCustomer() {
		/** @var \WP_User @currentUser */
		$currentUser = wp_get_current_user();

		if (empty($currentUser)) {
			return null;
		}

		$timeDiff = abs(strtotime($currentUser->data->user_registered) - time());
		return ($timeDiff > 3600*24);
	}

	public function getCurrentUserOrdersLast3Months() {
		if ( ! is_user_logged_in() ) {
			return null;
		}

		$totalAmount = 0;
		$currentUserId = get_current_user_id() ?? null;
		$args          = [
			'customer_id' => $currentUserId,
			'author' => $currentUserId,
			'post_status' => array( 'wc-completed', 'wc-shipped', 'wc-pending', 'wc-processing' ),
			'post_type'   => 'shop_order',
			'limit' => -1,
			'numberposts' => -1,
			'posts_per_page' => -1,
			'date_query'  =>
				array(
					array(
						'after' => date( 'Y-m-d', strtotime( '3 months ago' ) )
					)
				),
			'meta_query'  =>
				array(
					array(
						'key' => 'payment_method',
						'value' => 'tamara-gateway',
						'compare' => 'NOT LIKE',
					)
				),
			'inclusive'   => true,
		];

		$query = new WP_Query( $args );

		$orders = $query->posts;
		if ( !empty($orders) ) {
			foreach ( $orders as $order ) {
				$order = wc_get_order($order);
				$totalAmount += $order->get_total();
			}
		}

		return [$query->found_posts, (float) $totalAmount];
	}
}

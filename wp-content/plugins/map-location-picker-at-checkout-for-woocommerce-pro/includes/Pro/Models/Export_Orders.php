<?php

/**
 * Handles exporting of orders from the database.
 *
 * Author:          Uriahs Victor
 * Created on:      28/11/2021 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.4.0
 * @package Lpac/Models
 */
namespace Lpac\Pro\Models;

use Lpac\Traits\Upload_Folders;
use Lpac\Helpers\Functions;
/**
 * Location_Details class.
 *
 * Handles saving of latitude and longitude.
 */
class Export_Orders {

	use Upload_Folders;

	/**
	 * The name of the export file.
	 *
	 * @var string
	 */
	private string $filename;

	/**
	 * The folder to store the exported orders.
	 *
	 * @var string
	 */
	private string $folder_name;

	/**
	 * Class constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->filename    = 'export_' . current_time( 'Y-m-d' ) . '_' . time() . '.csv';
		$this->folder_name = 'order-exports';
	}

	/**
	 * Get the CSV link after records have been retrieved and saved.
	 *
	 * @param array $date_range
	 * @return void|string
	 * @since 1.4.0
	 */
	public function get_csv_link( $date_range ) {

		$link = '';

		$records = $this->get_records( $date_range );

		if ( empty( $records ) || ! is_array( $records ) ) {
			return null;
		}

		$saved = $this->save_records_to_csv( $records );

		if ( $saved ) {
			$link = $this->get_order_exports_url();
		}

		return $link;
	}

	/**
	 * Get our records from the Database.
	 *
	 * @param array $date_range
	 * @return array|object|null
	 * @since 1.4.0
	 */
	private function get_records( $date_range ) {

		global $wpdb;

		$from_date       = sanitize_text_field( $date_range['from'] );
		$to_date         = sanitize_text_field( $date_range['to'] );
		$posts_table     = $wpdb->prefix . 'posts';
		$postsmeta_table = $wpdb->prefix . 'postmeta';
		$users_table     = $wpdb->prefix . 'users';

		$sql = <<<SQL
		SELECT t.order_id as 'order_id', t.uID as 'user_id', users.display_name as 'customer_name', t.email as 'customer_email', t.phone as 'phone_number', t.order_date as 'order_date', t.link as 'location_link'
		FROM(
		SELECT 
		posts.ID as 'order_id',
		post_title as 'order_date',
		SUM(case when meta.meta_key = %s then meta.meta_value end) as uID,
		SUM(case when meta.meta_key = %s then meta.meta_value end) as 'email',
		CONCAT( 
		%s,
		SUM(case when meta.meta_key = %s then meta.meta_value end), ',',
		SUM(case when meta.meta_key = %s then meta.meta_value end)
		) as 'link',
		SUM(case when meta.meta_key = %s then meta.meta_value end) as 'phone'
		FROM $posts_table posts
		LEFT JOIN $postsmeta_table meta on posts.ID = meta.post_id
		WHERE posts.post_type = %s
		AND post_date BETWEEN %s AND %s
		AND (
			meta.meta_key = %s OR 
			meta.meta_key = %s OR 
			meta.meta_key = %s OR 
			meta.meta_key = %s OR 
			meta.meta_key = %s
			)
		GROUP BY posts.ID
		) as t
		LEFT JOIN $users_table users on t.uID = users.ID
SQL;

		$map_link = Functions::create_customer_directions_link();

		$query = $wpdb->prepare(
			$sql,
			'_customer_user',
			'_billing_email',
			$map_link,
			'lpac_latitude',
			'lpac_longitude',
			'_billing_phone',
			'shop_order',
			$from_date,
			$to_date,
			'lpac_latitude',
			'lpac_longitude',
			'_billing_phone',
			'_customer_user',
			'_billing_email',
		);

		$results = $wpdb->get_results( $query, ARRAY_A );

		return $results;

	}

	/**
	 * Get the URL for the export.
	 *
	 * @return string
	 */
	private function get_order_exports_url() {
		$upload_url = wp_upload_dir()['baseurl'];
		$link       = $upload_url . "/lpac/{$this->folder_name}/" . $this->filename;
		return $link;
	}

	/**
	 * Save the records from the DB to a CSV file.
	 *
	 * @param array $results
	 * @return void|true
	 * @since 1.4.0
	 */
	private function save_records_to_csv( array $results ) {

		if ( empty( $results ) || ! is_array( $results ) ) {
			return false;
		}

		clearstatcache();

		$path      = $this->create_upload_folder( $this->folder_name );
		$full_path = $path . $this->filename;

		$outstream = fopen( $full_path, 'w' );

		$headers = array(
			__( 'Order ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			__( 'User ID', 'map-location-picker-at-checkout-for-woocommerce' ),
			__( 'Customer Name', 'map-location-picker-at-checkout-for-woocommerce' ),
			__( 'Customer Email', 'map-location-picker-at-checkout-for-woocommerce' ),
			__( 'Phone Number', 'map-location-picker-at-checkout-for-woocommerce' ),
			__( 'Order Date', 'map-location-picker-at-checkout-for-woocommerce' ),
			__( 'Location Link', 'map-location-picker-at-checkout-for-woocommerce' ),
		);

		 // Create our headings
		fputcsv( $outstream, $headers );

		foreach ( $results as $result_row ) {
			$result_row['order_date'] = html_entity_decode( $result_row['order_date'] );
			if ( empty( $result_row['user_id'] ) ) {
				$result_row['customer_name'] = __( 'Guest Customer', 'map-location-picker-at-checkout-for-woocommerce' );
			}

			// Below we're manually going back into the data to add the billing email because the SQL query in get_records() does not return it.
			$result_row['customer_email'] = get_post_meta( $result_row['order_id'], '_billing_email', true );
			fputcsv( $outstream, $result_row );
		}

		fclose( $outstream );

		if ( ! file_exists( $full_path ) || empty( @filesize( $full_path ) ) ) { // file doesn't exist or empty
			return false;
		}

		return true;
	}

	/**
	 * Get orders from the database by date range.
	 *
	 * @param array $range
	 * @return void
	 */
	public function get_orders_by_range( array $range ): array {

		$from = $range['from'];
		$to   = $range['to'];

		$status = apply_filters( 'lpac_plotted_orders_statuses', array( 'wc-completed', 'processing' ) );

		$orders_object = wc_get_orders(
			array(
				'limit'        => -1,
				'type'         => 'shop_order',
				'status'       => $status,
				'date_created' => $from . '...' . $to,
			)
		);

		$orders_locations = array();

		foreach ( $orders_object as $order ) {

			$order->get_meta( 'lpac_longitude' );

			$orders_locations[] = array(
				'latitude'  => $order->get_meta( 'lpac_latitude' ),
				'longitude' => $order->get_meta( 'lpac_longitude' ),
				'region'    => $order->get_meta( 'lpac_customer_region' ),
			);
		}

		$orders_locations = array_values( array_unique( $orders_locations, SORT_REGULAR ) );

		return $orders_locations;
	}

}

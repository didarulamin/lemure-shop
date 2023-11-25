<?php
/**
 * Class responsible for holding custom Post Types.
 *
 * Author:          Uriahs Victor
 * Created on:      02/02/2023 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.7.0
 * @package Views
 */

namespace Lpac\Pro\Controllers\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Controller responsible for creating our custom post types.
 *
 * @package Lpac\Pro\Controllers\Admin
 * @since 1.7.0
 */
class Custom_Post_Types {

	/**
	 * Register our Map custom post type.
	 *
	 * @return void
	 * @since 1.7.0
	 */
	function register_cpts_maps() : void {

		/**
		 * Post Type: Maps.
		 */
		$labels = array(
			'name'                     => esc_html__( 'Maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'singular_name'            => esc_html__( 'Map', 'map-location-picker-at-checkout-for-woocommerce' ),
			'menu_name'                => esc_html__( 'My maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'all_items'                => esc_html__( 'Map Builder', 'map-location-picker-at-checkout-for-woocommerce' ),
			'add_new'                  => esc_html__( 'Add new', 'map-location-picker-at-checkout-for-woocommerce' ),
			'add_new_item'             => esc_html__( 'Add new maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'edit_item'                => esc_html__( 'Edit maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'new_item'                 => esc_html__( 'New maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'view_item'                => esc_html__( 'View maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'view_items'               => esc_html__( 'View maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'search_items'             => esc_html__( 'Search maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'not_found'                => esc_html__( 'No maps found', 'map-location-picker-at-checkout-for-woocommerce' ),
			'not_found_in_trash'       => esc_html__( 'No maps found in trash', 'map-location-picker-at-checkout-for-woocommerce' ),
			'parent'                   => esc_html__( 'Parent maps:', 'map-location-picker-at-checkout-for-woocommerce' ),
			'featured_image'           => esc_html__( 'Featured image for this maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'set_featured_image'       => esc_html__( 'Set featured image for this maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'remove_featured_image'    => esc_html__( 'Remove featured image for this maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'use_featured_image'       => esc_html__( 'Use as featured image for this maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'archives'                 => esc_html__( 'maps archives', 'map-location-picker-at-checkout-for-woocommerce' ),
			'insert_into_item'         => esc_html__( 'Insert into maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'uploaded_to_this_item'    => esc_html__( 'Upload to this maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'filter_items_list'        => esc_html__( 'Filter maps list', 'map-location-picker-at-checkout-for-woocommerce' ),
			'items_list_navigation'    => esc_html__( 'maps list navigation', 'map-location-picker-at-checkout-for-woocommerce' ),
			'items_list'               => esc_html__( 'maps list', 'map-location-picker-at-checkout-for-woocommerce' ),
			'attributes'               => esc_html__( 'maps attributes', 'map-location-picker-at-checkout-for-woocommerce' ),
			'name_admin_bar'           => esc_html__( 'maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'item_published'           => esc_html__( 'maps published', 'map-location-picker-at-checkout-for-woocommerce' ),
			'item_published_privately' => esc_html__( 'maps published privately.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'item_reverted_to_draft'   => esc_html__( 'maps reverted to draft.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'item_scheduled'           => esc_html__( 'maps scheduled', 'map-location-picker-at-checkout-for-woocommerce' ),
			'item_updated'             => esc_html__( 'maps updated.', 'map-location-picker-at-checkout-for-woocommerce' ),
			'parent_item_colon'        => esc_html__( 'Parent maps:', 'map-location-picker-at-checkout-for-woocommerce' ),
		);

		$args = array(
			'label'                 => esc_html__( 'Maps', 'map-location-picker-at-checkout-for-woocommerce' ),
			'labels'                => $labels,
			'description'           => '',
			'public'                => true,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_rest'          => false,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'rest_namespace'        => 'wp/v2',
			'has_archive'           => false,
			'show_in_menu'          => false,
			'show_in_nav_menus'     => false,
			'delete_with_user'      => false,
			'exclude_from_search'   => true,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'hierarchical'          => false,
			'can_export'            => false,
			'rewrite'               => false,
			'query_var'             => false,
			// 'menu_icon'             => 'dashicons-admin-plugins',
			// 'register_meta_box_cb'  => 'test()',
			'supports'              => array( 'title' ),
			'show_in_graphql'       => false,
		);

		register_post_type( 'kikote-maps', $args );
	}



}

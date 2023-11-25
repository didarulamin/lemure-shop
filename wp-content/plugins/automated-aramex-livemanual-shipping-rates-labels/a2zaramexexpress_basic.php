<?php
/**
 * Plugin Name:  ARAMEX Rates & Labels
 * Plugin URI: https://hitshipo.com/
 * Description: Realtime Shipping Rates, Shipping label, Pickup, commercial invoice automation included.
 * Version: 2.2.1
 * Author: HITShipo
 * Author URI: https://hitshipo.com/
 * Developer: hitshipo
 * Developer URI: https://hitshipo.com/
 * Text Domain: a2z_aramexexpress
 * Domain Path: /languages/
 *
 * WC requires at least: 2.6
 * WC tested up to: 5.8
 *
 *
 * @package WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WC_PLUGIN_FILE.
if ( ! defined( 'A2Z_ARAMEXEXPRESS_PLUGIN_FILE' ) ) {
	define( 'A2Z_ARAMEXEXPRESS_PLUGIN_FILE', __FILE__ );
}

function hit_woo_aramex_express_plugin_activation( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        $setting_value = version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings";
    	// Don't forget to exit() because wp_redirect doesn't exit automatically
    	exit( wp_redirect( admin_url( 'admin.php?page=' . $setting_value  . '&tab=shipping&section=az_aramexexpress' ) ) );
    }
}
add_action( 'activated_plugin', 'hit_woo_aramex_express_plugin_activation' );
// set HPOS feature compatible by plugin
add_action(
    'before_woocommerce_init',
    function () {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    }
);
// Apply translations
function apply_hits_aramex_translations() {
	// Get mo file as per current locale.
	$mofile = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'languages/a2z_aramexexpress-' . get_locale() . '.mo';
	// If file does not exists, then apply English mo.
	if ( ! file_exists( $mofile ) ) {
		$mofile = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'languages/a2z_aramexexpress-en_US.mo';
	}
	load_textdomain( 'a2z_aramexexpress', $mofile );
}
add_action( 'plugins_loaded', 'apply_hits_aramex_translations' );

// Include the main WooCommerce class.
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	if( !class_exists('a2z_aramexexpress_parent') ){
		Class a2z_aramexexpress_parent
		{
			private $errror = '';
			public $hpos_enabled = false;
 	        public $new_prod_editor_enabled = false;
			public function __construct() {
				if (get_option("woocommerce_custom_orders_table_enabled") === "yes") {
 		            $this->hpos_enabled = true;
 		        }
 		        if (get_option("woocommerce_feature_product_block_editor_enabled") === "yes") {
 		            $this->new_prod_editor_enabled = true;
 		        }
				add_action( 'woocommerce_shipping_init', array($this,'a2z_aramexexpress_init') );
				add_action( 'init', array($this,'hit_order_status_update') );
				add_filter( 'woocommerce_shipping_methods', array($this,'a2z_aramexexpress_method') );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'a2z_aramexexpress_plugin_action_links' ) );
				add_action( 'add_meta_boxes', array($this, 'create_aramex_shipping_meta_box' ));
				if ($this->hpos_enabled) {
					add_action( 'woocommerce_process_shop_order_meta', array($this, 'hit_create_aramex_shipping'), 10, 1 );
					// add_action( 'woocommerce_process_shop_order_meta', array($this, 'hit_create_aramex_return_shipping'), 10, 1 );
				} else {
					add_action( 'save_post', array($this, 'hit_create_aramex_shipping'), 10, 1 );
					// add_action( 'save_post', array($this, 'hit_create_aramex_return_shipping'), 10, 1 );
				}
				if ($this->hpos_enabled) {
					add_filter( 'bulk_actions-woocommerce_page_wc-orders', array($this, 'hit_bulk_order_menu'), 10, 1 );
					add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', array($this, 'hit_bulk_create_order'), 10, 3 );
				} else {
					add_filter( 'bulk_actions-edit-shop_order', array($this, 'hit_bulk_order_menu'), 10, 1 );
					add_filter( 'handle_bulk_actions-edit-shop_order', array($this, 'hit_bulk_create_order'), 10, 3 );
				}
				
				add_action( 'admin_notices', array($this, 'shipo_bulk_label_action_admin_notice' ) );
				add_filter( 'woocommerce_product_data_tabs', array($this,'hit_product_data_tab') );
				add_action( 'woocommerce_process_product_meta', array($this,'hit_save_product_options' ));
				add_filter( 'woocommerce_product_data_panels', array($this,'hit_product_option_view') );
				add_action( 'admin_menu', array($this, 'hit_aramex_menu_page' ));
				// add_action( 'woocommerce_checkout_order_processed', array( $this, 'hit_wc_checkout_order_processed' ) );
				// add_action( 'woocommerce_thankyou', array( $this, 'hit_wc_checkout_order_processed' ) );
				add_action( 'woocommerce_order_status_processing', array( $this, 'hit_wc_checkout_order_processed' ) );
				add_action('woocommerce_order_details_after_order_table', array( $this, 'aramex_track' ) );
				if ($this->hpos_enabled) {
					add_filter( 'manage_woocommerce_page_wc-orders_columns', array($this, 'a2z_wc_new_order_column') );
					add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'show_buttons_to_downlaod_shipping_label'), 10, 2 );
				} else {
					add_filter( 'manage_edit-shop_order_columns', array($this, 'a2z_wc_new_order_column') );
					add_action( 'manage_shop_order_posts_custom_column', array( $this, 'show_buttons_to_downlaod_shipping_label'), 10, 2 );
				}
				
				$general_settings = get_option('a2z_aramex_main_settings');
				$general_settings = empty($general_settings) ? array() : $general_settings;

				if(isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes' ){
					add_action( 'woocommerce_product_options_shipping', array($this,'hit_choose_vendor_address' ));
					add_action( 'woocommerce_process_product_meta', array($this,'hit_save_product_meta' ));

					// Edit User Hooks
					add_action( 'edit_user_profile', array($this,'hit_define_aramex_credentails') );
					add_action( 'edit_user_profile_update', array($this, 'save_user_fields' ));

				}
				add_action('dokan_order_detail_after_order_items', array( $this, 'show_order_ui_on_custom_page'));
			}
			public function show_order_ui_on_custom_page($order=[])
			{
				$order_id = apply_filters("hits_aramex_custom_order_page_o_id", "");
				if (!empty($order_id)) {
					if (  isset( $_POST[ 'hit_aramex_reset' ] ) ) {
			    		delete_option('hit_aramex_values_'.$order_id);
			    		delete_option('hit_aramex_pickup_values_'.$order_id);
			    	}
					if (isset($_POST['hit_aramex_create_label'])) {
						$this->hit_create_aramex_shipping($order_id);
					}
					$order->post_type = "shop_order";
					$order->ID = $order_id;
					$style = apply_filters("hits_aramex_custom_order_page_ui_css", "");
					echo '<div style="'.$style.'"><b>'.esc_html__("Automated Aramex Shipping", "a2z_aramexexpress").'</b><form method="POST">';
					$this->create_aramex_shipping_label_genetation($order);
					echo '</form></div>';
				}
			}
			function a2z_wc_new_order_column( $columns ) {
				$columns['hit_aramexexpress'] = __("ARAMEX Express", "a2z_aramexexpress");
				return $columns;
			}
			
			function show_buttons_to_downlaod_shipping_label( $column, $post ) {
				
				if ( 'hit_aramexexpress' === $column ) {
					$order    = ($this->hpos_enabled) ? $post : wc_get_order( $post );
					$order_id = $order->get_id();
					$json_data = get_option('hit_aramex_values_'.$order_id);
					
					if(!empty($json_data)){
						$array_data = json_decode( $json_data, true );
						// echo '<pre>';print_r($array_data);die();
						if(isset($array_data[0])){
							foreach ($array_data as $key => $value) {
								echo '<a href="'.$value['label'].'" target="_blank" class="button button-secondary"><span class="dashicons dashicons-printer" style="vertical-align:sub;"></span></a> ';
								if (isset($value['invoice'])) {
									echo ' <a href="'.$value['invoice'].'" target="_blank" class="button button-secondary"><span class="dashicons dashicons-pdf" style="vertical-align:sub;"></span></a><br/>';
								}
							}	
						}else{
							echo '<a href="'.$array_data['label'].'" target="_blank" class="button button-secondary"><span class="dashicons dashicons-printer" style="vertical-align:sub;"></span></a> ';
							if (isset($array_data['invoice'])) {
								echo ' <a href="'.$array_data['invoice'].'" target="_blank" class="button button-secondary"><span class="dashicons dashicons-pdf" style="vertical-align:sub;"></span></a>';
							}
						}
					}else{
						echo '-';
					}
				}
			}
			
			function hit_aramex_menu_page() {
				$general_settings = get_option('a2z_aramex_main_settings');
				if (isset($general_settings['a2z_aramexexpress_integration_key']) && !empty($general_settings['a2z_aramexexpress_integration_key'])) {
					add_menu_page(__( 'Aramex Labels', 'a2z_aramexexpress' ), 'ARAMEX Labels', 'manage_options', 'hit-ARAMEX-labels', array($this,'my_label_page_contents'), '', 6);
				}			
				
				add_submenu_page( 'options-general.php', 'ARAMEX Express Config', 'ARAMEX Express Config', 'manage_options', 'hit-aramex-express-configuration', array($this, 'my_admin_page_contents') ); 

			}
			function my_label_page_contents(){
				$general_settings = get_option('a2z_aramex_main_settings');
				$url = site_url();
				if (isset($general_settings['a2z_aramexexpress_integration_key']) && !empty($general_settings['a2z_aramexexpress_integration_key'])) {
					echo "<iframe style='width: 100%;height: 100vh;' src='https://app.hitshipo.com/embed/label.php?shop=".$url."&key=".$general_settings['a2z_aramexexpress_integration_key']."&show=ship'></iframe>";
				}
            }
			function my_admin_page_contents(){
				include_once('controllors/views/a2z_aramexexpress_settings_view.php');
			}

			public function hit_product_data_tab( $tabs) {

				$tabs['hits_aramex_product_options'] = array(
					'label'		=> __( 'HITShipo - ARAMEX Options', 'a2z_aramexexpress' ),
					'target'	=> 'hit_aramex_product_options',
					// 'class'		=> array( 'show_if_simple', 'show_if_variable' ),
				);
			
				return $tabs;
			
			}

			public function hit_save_product_options( $post_id ){
				if( isset($_POST['hits_aramex_cc']) ){
					$cc = sanitize_text_field($_POST['hits_aramex_cc']);
					if ($this->hpos_enabled && $this->new_prod_editor_enabled) {
 	                    $hpos_prod_data = wc_get_product($post_id);
 	                    $hpos_prod_data->update_meta_data("hits_aramex_cc", (string) esc_html( $cc ));
 	                } else {
						update_post_meta( $post_id, 'hits_aramex_cc', (string) esc_html( $cc ) );
					}
					// print_r($post_id);die();
				}
			}

			public function hit_product_option_view(){
				global $woocommerce, $post;
				if ($this->hpos_enabled) {
                    $hpos_prod_data = wc_get_product($post->ID);
                    $hits_aramex_saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
                } else {
					$hits_aramex_saved_cc = get_post_meta( $post->ID, 'hits_aramex_cc', true);
				}
				?>
				<div id='hit_aramex_product_options' class='panel woocommerce_options_panel'>
					<div class='options_group'>
						<p class="form-field">
							<label for="hits_aramex_cc"><?php _e( 'Enter Commodity code', 'a2z_aramexexpress' ); ?></label>
							<span class='woocommerce-help-tip' data-tip="<?php _e('Enter commodity code for product (20 charcters max).','a2z_aramexexpress') ?>"></span>
							<input type='text' id='hits_aramex_cc' name='hits_aramex_cc' maxlength="20" <?php echo (!empty($hits_aramex_saved_cc) ? 'value="'.$hits_aramex_saved_cc.'"' : '');?> style="width: 30%;">
						</p>
					</div>
				</div>
				<?php
			}

			public function hit_bulk_order_menu( $actions ) {
				// echo "<pre>";print_r($actions);die();
				$actions['create_label_shipo_aramex'] = __( 'Create Labels - HITShipo [ARAMEX]', 'a2z_aramexexpress' );
				return $actions;
			}

			public function hit_bulk_create_order($redirect_to, $action, $order_ids){
				$success = 0;
				$failed = 0;
				$failed_ids = [];
				if($action == "create_label_shipo_aramex"){
					
					if(!empty($order_ids)){
						$create_shipment_for = "default";
						$service_code = "PPX";
						$pickup_mode = 'manual';
						
						foreach($order_ids as $key => $order_id){
							$order = wc_get_order( $order_id );
							if($order){

									$order_data = $order->get_data();
									$order_id = $order_data['id'];
									$order_currency = $order_data['currency'];

									// $order_shipping_first_name = $order_data['shipping']['first_name'];
									// $order_shipping_last_name = $order_data['shipping']['last_name'];
									// $order_shipping_company = empty($order_data['shipping']['company']) ? $order_data['shipping']['first_name'] :  $order_data['shipping']['company'];
									// $order_shipping_address_1 = $order_data['shipping']['address_1'];
									// $order_shipping_address_2 = $order_data['shipping']['address_2'];
									// $order_shipping_city = $order_data['shipping']['city'];
									// $order_shipping_state = $order_data['shipping']['state'];
									// $order_shipping_postcode = $order_data['shipping']['postcode'];
									// $order_shipping_country = $order_data['shipping']['country'];
									// $order_shipping_phone = $order_data['billing']['phone'];
									// $order_shipping_email = $order_data['billing']['email'];

									$shipping_arr = (isset($order_data['shipping']['first_name']) && $order_data['shipping']['first_name'] != "") ? $order_data['shipping'] : $order_data['billing'];
									$order_shipping_first_name = $shipping_arr['first_name'];
									$order_shipping_last_name = $shipping_arr['last_name'];
									$order_shipping_company = empty($shipping_arr['company']) ? $shipping_arr['first_name'] :  $shipping_arr['company'];
									$order_shipping_address_1 = $shipping_arr['address_1'];
									$order_shipping_address_2 = $shipping_arr['address_2'];
									$order_shipping_city = $shipping_arr['city'];
									$order_shipping_state = $shipping_arr['state'];
									$order_shipping_postcode = $shipping_arr['postcode'];
									$order_shipping_country = $shipping_arr['country'];
									$order_shipping_phone = $order_data['billing']['phone'];
									$order_shipping_email = $order_data['billing']['email'];
									$shipping_charge = $order_data['shipping_total'];
									
									$items = $order->get_items();
									$pack_products = array();
									$general_settings = get_option('a2z_aramex_main_settings',array());

									if($general_settings['a2z_aramexexpress_country'] != $order_shipping_country){
										$service_code = "PPX";
									}

									foreach ( $items as $item ) {
										$product_data = $item->get_data();

										$product = array();
										$product['product_name'] = str_replace('"', '', $product_data['name']);
										$product['product_quantity'] = $product_data['quantity'];
										$product['product_id'] = $product_data['product_id'];

										if ($this->hpos_enabled) {
						                    $hpos_prod_data = wc_get_product($product_data['product_id']);
						                    $saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
						                } else {
											$saved_cc = get_post_meta( $product_data['product_id'], 'hits_aramex_cc', true);
										}
										if(!empty($saved_cc)){
											$product['commodity_code'] = $saved_cc;
										}

										$product_variation_id = $item->get_variation_id();
										if(empty($product_variation_id)){
											$getproduct = wc_get_product( $product_data['product_id'] );
										}else{
											$getproduct = wc_get_product( $product_variation_id );
										}
										
										$woo_weight_unit = get_option('woocommerce_weight_unit');
										$woo_dimension_unit = get_option('woocommerce_dimension_unit');

										$aramex_mod_weight_unit = $aramex_mod_dim_unit = '';

										if(!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM')
										{
											$aramex_mod_weight_unit = 'kg';
											$aramex_mod_dim_unit = 'cm';
										}elseif(!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'LB_IN')
										{
											$aramex_mod_weight_unit = 'lbs';
											$aramex_mod_dim_unit = 'in';
										}
										else
										{
											$aramex_mod_weight_unit = 'kg';
											$aramex_mod_dim_unit = 'cm';
										}

										$product['price'] = $getproduct->get_price();

										if(!$product['price']){
											$product['price'] = (isset($product_data['total']) && isset($product_data['quantity'])) ? number_format(($product_data['total'] / $product_data['quantity']), 2) : 0;
										}

										if ($woo_dimension_unit != $aramex_mod_dim_unit) {
										$prod_width = $getproduct->get_width();
										$prod_height = $getproduct->get_height();
										$prod_depth = $getproduct->get_length();

										//wc_get_dimension( $dimension, $to_unit, $from_unit );
										$product['width'] = (!empty($prod_width) && $prod_width > 0) ? round(wc_get_dimension( $prod_width, $aramex_mod_dim_unit, $woo_dimension_unit ), 2) : 0.1 ;
										$product['height'] = (!empty($prod_height) && $prod_height > 0) ? round(wc_get_dimension( $prod_height, $aramex_mod_dim_unit, $woo_dimension_unit ), 2) : 0.1 ;
										$product['depth'] = (!empty($prod_depth) && $prod_depth > 0) ? round(wc_get_dimension( $prod_depth, $aramex_mod_dim_unit, $woo_dimension_unit ), 2) : 0.1 ;

										}else {
											$product['width'] = $getproduct->get_width();
											$product['height'] = $getproduct->get_height();
											$product['depth'] = $getproduct->get_length();
										}
										
										if ($woo_weight_unit != $aramex_mod_weight_unit) {
											$prod_weight = $getproduct->get_weight();
											$product['weight'] = (!empty($prod_weight) && $prod_weight > 0) ? round(wc_get_weight( $prod_weight, $aramex_mod_weight_unit, $woo_weight_unit ), 2) : 0.1 ;
										}else{
											$product['weight'] = $getproduct->get_weight();
										}

										$pack_products[] = $product;
										
									}
									
									$custom_settings = array();
									$custom_settings['default'] = array(
														'a2z_aramexexpress_site_id' => $general_settings['a2z_aramexexpress_site_id'],
														'a2z_aramexexpress_site_pwd' => $general_settings['a2z_aramexexpress_site_pwd'],
														'a2z_aramexexpress_acc_no' => $general_settings['a2z_aramexexpress_acc_no'],
														'a2z_aramexexpress_entity' => $general_settings['a2z_aramexexpress_entity'],
														'a2z_aramexexpress_acc_pin' => $general_settings['a2z_aramexexpress_acc_pin'],
														'a2z_aramexexpress_shipper_name' => $general_settings['a2z_aramexexpress_shipper_name'],
														'a2z_aramexexpress_company' => $general_settings['a2z_aramexexpress_company'],
														'a2z_aramexexpress_mob_num' => $general_settings['a2z_aramexexpress_mob_num'],
														'a2z_aramexexpress_email' => $general_settings['a2z_aramexexpress_email'],
														'a2z_aramexexpress_address1' => $general_settings['a2z_aramexexpress_address1'],
														'a2z_aramexexpress_address2' => $general_settings['a2z_aramexexpress_address2'],
														'a2z_aramexexpress_city' => $general_settings['a2z_aramexexpress_city'],
														'a2z_aramexexpress_state' => $general_settings['a2z_aramexexpress_state'],
														'a2z_aramexexpress_zip' => $general_settings['a2z_aramexexpress_zip'],
														'a2z_aramexexpress_country' => $general_settings['a2z_aramexexpress_country'],
														'a2z_aramexexpress_gstin' => $general_settings['a2z_aramexexpress_gstin'],
														'a2z_aramexexpress_con_rate' => $general_settings['a2z_aramexexpress_con_rate'],
														'service_code' => $service_code,
														'a2z_aramexexpress_label_email' => $general_settings['a2z_aramexexpress_label_email'],
													);
									$vendor_settings = array();
								// 	if(isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes' && isset($general_settings['a2z_aramexexpress_v_labels']) && $general_settings['a2z_aramexexpress_v_labels'] == 'yes'){
								// 	// Multi Vendor Enabled
								// 	foreach ($pack_products as $key => $value) {
								// 		$product_id = $value['product_id'];
								// 		$aramex_account = get_post_meta($product_id,'aramex_express_address', true);
								// 		if(empty($aramex_account) || $aramex_account == 'default'){
								// 			$aramex_account = 'default';
								// 			if (!isset($vendor_settings[$aramex_account])) {
								// 				$vendor_settings[$aramex_account] = $custom_settings['default'];
								// 			}
											
								// 			$vendor_settings[$aramex_account]['products'][] = $value;
								// 		}

								// 		if($aramex_account != 'default'){
								// 			$user_account = get_post_meta($aramex_account,'a2z_aramex_vendor_settings', true);
								// 			$user_account = empty($user_account) ? array() : $user_account;
								// 			if(!empty($user_account)){
								// 				if(!isset($vendor_settings[$aramex_account])){

								// 					$vendor_settings[$aramex_account] = $custom_settings['default'];
													
								// 				if($user_account['a2z_aramexexpress_site_id'] != '' && $user_account['a2z_aramexexpress_site_pwd'] != '' && $user_account['a2z_aramexexpress_acc_no'] != ''){
													
								// 					$vendor_settings[$aramex_account]['a2z_aramexexpress_site_id'] = $user_account['a2z_aramexexpress_site_id'];

								// 					if($user_account['a2z_aramexexpress_site_pwd'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_site_pwd'] = $user_account['a2z_aramexexpress_site_pwd'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_acc_no'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_no'] = $user_account['a2z_aramexexpress_acc_no'];
								// 					}

													
								// 				}

								// 				if ($user_account['a2z_aramexexpress_address1'] != '' && $user_account['a2z_aramexexpress_city'] != '' && $user_account['a2z_aramexexpress_state'] != '' && $user_account['a2z_aramexexpress_zip'] != '' && $user_account['a2z_aramexexpress_country'] != '' && $user_account['a2z_aramexexpress_shipper_name'] != '') {
													
								// 					if($user_account['a2z_aramexexpress_shipper_name'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_shipper_name'] = $user_account['a2z_aramexexpress_shipper_name'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_company'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_company'] = $user_account['a2z_aramexexpress_company'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_mob_num'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_mob_num'] = $user_account['a2z_aramexexpress_mob_num'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_email'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_email'] = $user_account['a2z_aramexexpress_email'];
								// 					}

								// 					if ($user_account['a2z_aramexexpress_address1'] != '') {
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_address1'] = $user_account['a2z_aramexexpress_address1'];
								// 					}

								// 					$vendor_settings[$aramex_account]['a2z_aramexexpress_address2'] = $user_account['a2z_aramexexpress_address2'];
													
								// 					if($user_account['a2z_aramexexpress_city'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_city'] = $user_account['a2z_aramexexpress_city'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_state'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_state'] = $user_account['a2z_aramexexpress_state'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_zip'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_zip'] = $user_account['a2z_aramexexpress_zip'];
								// 					}

								// 					if($user_account['a2z_aramexexpress_country'] != ''){
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_country'] = $user_account['a2z_aramexexpress_country'];
								// 					}

								// 					$vendor_settings[$aramex_account]['a2z_aramexexpress_gstin'] = $user_account['a2z_aramexexpress_gstin'];
								// 					$vendor_settings[$aramex_account]['a2z_aramexexpress_con_rate'] = $user_account['a2z_aramexexpress_con_rate'];

								// 				}
													
								// 					if(isset($general_settings['a2z_aramexexpress_v_email']) && $general_settings['a2z_aramexexpress_v_email'] == 'yes'){
								// 						$user_dat = get_userdata($aramex_account);
								// 						$vendor_settings[$aramex_account]['a2z_aramexexpress_label_email'] = $user_dat->data->user_email;
								// 					}
													

								// 					if($order_data['shipping']['country'] != $vendor_settings[$aramex_account]['a2z_aramexexpress_country']){
								// 						$vendor_settings[$aramex_account]['service_code'] = empty($service_code) ? $user_account['a2z_aramexexpress_def_inter'] : $service_code;
								// 					}else{
								// 						$vendor_settings[$aramex_account]['service_code'] = empty($service_code) ? $user_account['a2z_aramexexpress_def_dom'] : $service_code;
								// 					}
								// 				}
								// 				$vendor_settings[$aramex_account]['products'][] = $value;
								// 			}
								// 		}

								// 	}

								// }

								if(empty($vendor_settings)){
									$custom_settings['default']['products'] = $pack_products;
								}else{
									$custom_settings = $vendor_settings;
								}

								if(!empty($general_settings) && isset($general_settings['a2z_aramexexpress_integration_key']) && isset($custom_settings[$create_shipment_for])){
									$mode = 'live';
									if(isset($general_settings['a2z_aramexexpress_test']) && $general_settings['a2z_aramexexpress_test']== 'yes'){
										$mode = 'test';
									}

									$execution = 'manual';
									
									$boxes_to_shipo = array();
									if (isset($general_settings['a2z_aramexexpress_packing_type']) && $general_settings['a2z_aramexexpress_packing_type'] == "box") {
										if (isset($general_settings['a2z_aramexexpress_boxes']) && !empty($general_settings['a2z_aramexexpress_boxes'])) {
											foreach ($general_settings['a2z_aramexexpress_boxes'] as $box) {
												if ($box['enabled'] != 1) {
													continue;
												}else {
													$boxes_to_shipo[] = $box;
												}
											}
										}
									}

									global $aramex_core;
									$frm_curr = get_option('woocommerce_currency');
									$to_curr = isset($aramex_core[$custom_settings[$create_shipment_for]['a2z_aramexexpress_country']]) ? $aramex_core[$custom_settings[$create_shipment_for]['a2z_aramexexpress_country']]['currency'] : '';
									$curr_con_rate = ( isset($custom_settings[$create_shipment_for]['a2z_aramexexpress_con_rate']) && !empty($custom_settings[$create_shipment_for]['a2z_aramexexpress_con_rate']) ) ? $custom_settings[$create_shipment_for]['a2z_aramexexpress_con_rate'] : 0;

									if (!empty($frm_curr) && !empty($to_curr) && ($frm_curr != $to_curr) ) {
										if (isset($general_settings['a2z_aramexexpress_auto_con_rate']) && $general_settings['a2z_aramexexpress_auto_con_rate'] == "yes") {
											$current_date = date('m-d-Y', time());
											$ex_rate_data = get_option('a2z_aramex_ex_rate'.$create_shipment_for);
											$ex_rate_data = !empty($ex_rate_data) ? $ex_rate_data : array();
											if (empty($ex_rate_data) || (isset($ex_rate_data['date']) && $ex_rate_data['date'] != $current_date) ) {
												if (isset($custom_settings[$create_shipment_for]['a2z_aramexexpress_country']) && !empty($custom_settings[$create_shipment_for]['a2z_aramexexpress_country']) && isset($general_settings['a2z_aramexexpress_integration_key']) && !empty($general_settings['a2z_aramexexpress_integration_key'])) {
													
													$ex_rate_Request = json_encode(array('integrated_key' => $general_settings['a2z_aramexexpress_integration_key'],
																		'from_curr' => $frm_curr,
																		'to_curr' => $to_curr));

													$ex_rate_url = "https://app.hitshipo.com/get_exchange_rate.php";
													// $ex_rate_url = "http://localhost/hitshipo/get_exchange_rate.php";
													$ex_rate_response = wp_remote_post( $ex_rate_url , array(
																	'method'      => 'POST',
																	'timeout'     => 45,
																	'redirection' => 5,
																	'httpversion' => '1.0',
																	'blocking'    => true,
																	'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
																	'body'        => $ex_rate_Request,
																	'sslverify'   => FALSE
																	)
																);

													$ex_rate_result = ( is_array($ex_rate_response) && isset($ex_rate_response['body'])) ? json_decode($ex_rate_response['body'], true) : array();

													if ( !empty($ex_rate_result) && isset($ex_rate_result['ex_rate']) && $ex_rate_result['ex_rate'] != "Not Found" ) {
														$ex_rate_result['date'] = $current_date;
														update_option('a2z_aramex_ex_rate'.$create_shipment_for, $ex_rate_result);
													}else {
														if (!empty($ex_rate_data)) {
															$ex_rate_data['date'] = $current_date;
															update_option('a2z_aramex_ex_rate'.$create_shipment_for, $ex_rate_data);
														}
													}
												}
											}
											$get_ex_rate = get_option('a2z_aramex_ex_rate'.$create_shipment_for, '');
											$get_ex_rate = !empty($get_ex_rate) ? $get_ex_rate : array();
											$curr_con_rate = ( !empty($get_ex_rate) && isset($get_ex_rate['ex_rate']) ) ? $get_ex_rate['ex_rate'] : 0;
										}
									}

									$c_codes = [];
									$ship_charge_con = "N";

									foreach($custom_settings[$create_shipment_for]['products'] as $prod_to_shipo_key => $prod_to_shipo){
										if ($this->hpos_enabled) {
						                    $hpos_prod_data = wc_get_product($prod_to_shipo['product_id']);
						                    $saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
						                } else {
											$saved_cc = get_post_meta( $prod_to_shipo['product_id'], 'hits_aramex_cc', true);
										}
										if(!empty($saved_cc)){
											$c_codes[] = $saved_cc;
										}

										if (!empty($frm_curr) && !empty($to_curr) && ($frm_curr != $to_curr) ) {
											if ($curr_con_rate > 0) {
												$custom_settings[$create_shipment_for]['products'][$prod_to_shipo_key]['price'] = $prod_to_shipo['price'] * $curr_con_rate;
												if ($ship_charge_con = "N") {
													$shipping_charge = $shipping_charge * $curr_con_rate;
													$ship_charge_con = "Y";
												}
											}
										}
									}
									
									$data = array();
									$data['integrated_key'] = $general_settings['a2z_aramexexpress_integration_key'];									
									$data['order_id'] = $order_id;
									$data['exec_type'] = $execution;
									$data['mode'] = $mode;
									$data['carrier_type'] = 'aramex';
									$data['meta'] = array(
										"site_id" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_site_id'],
										"password"  => $custom_settings[$create_shipment_for]['a2z_aramexexpress_site_pwd'],
										"accountnum" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_acc_no'],
										"acc_entity" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_entity'],
										"acc_pin" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_acc_pin'],
										"t_company" => $order_shipping_company,
										"t_address1" => str_replace('"', '', $order_shipping_address_1),
										"t_address2" => str_replace('"', '', $order_shipping_address_2),
										"t_city" => $order_shipping_city,
										"t_state" => $order_shipping_state,
										"t_postal" => $order_shipping_postcode,
										"t_country" => $order_shipping_country,
										"t_name" => $order_shipping_first_name . ' '. $order_shipping_last_name,
										"t_phone" => $order_shipping_phone,
										"t_email" => $order_shipping_email,
										"dutiable" => $general_settings['a2z_aramexexpress_duty_payment'],
										"insurance" => $general_settings['a2z_aramexexpress_insure'],
										"pack_this" => "Y",
										"products" => $custom_settings[$create_shipment_for]['products'],
										"pack_algorithm" => $general_settings['a2z_aramexexpress_packing_type'],
										"boxes" => $boxes_to_shipo,
										"max_weight" => $general_settings['a2z_aramexexpress_max_weight'],
										"cod" => ($general_settings['a2z_aramexexpress_cod'] == 'yes') ? "Y" : "N",
										"service_code" => $custom_settings[$create_shipment_for]['service_code'],
										"email_alert" => ( isset($general_settings['a2z_aramexexpress_email_alert']) && ($general_settings['a2z_aramexexpress_email_alert'] == 'yes') ) ? "Y" : "N",
										"shipment_content" => isset($general_settings['a2z_aramexexpress_ship_content']) ? $general_settings['a2z_aramexexpress_ship_content'] : 'Shipment Content',
										"s_company" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_company'],
										"s_address1" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_address1'],
										"s_address2" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_address2'],
										"s_city" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_city'],
										"s_state" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_state'],
										"s_postal" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_zip'],
										"s_country" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_country'],
										"gstin" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_gstin'],
										"s_name" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_shipper_name'],
										"s_phone" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_mob_num'],
										"s_email" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_email'],
										"sent_email_to" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_label_email'],
										"pic_exec_type" => $pickup_mode,
										"pic_pac_loc" => "",
										"pic_days_after" => "",
										"pic_open_time" => "",
										"pic_close_time" => "",
										"pic_mail_date" => date('c'),
										"pic_date" => date("Y-m-d"),
										"translation" => ( (isset($general_settings['a2z_aramexexpress_translation']) && $general_settings['a2z_aramexexpress_translation'] == "yes" ) ? 'Y' : 'N'),
										"translation_key" => (isset($general_settings['a2z_aramexexpress_translation_key']) ? $general_settings['a2z_aramexexpress_translation_key'] : ''),
										"commodity_code" => $c_codes,
										"pay_type" => (isset($general_settings['a2z_aramexexpress_pay_type']) ? $general_settings['a2z_aramexexpress_pay_type'] : ''),
										"shipping_charge" => $shipping_charge,
										"label" => $create_shipment_for
									);
									
									//Bulk shipment
									$bulk_shipment_url = "https://app.hitshipo.com/label_api/create_shipment.php";
									// $bulk_shipment_url = "http://localhost/hitshipo/label_api/create_shipment.php";
									$response = wp_remote_post( $bulk_shipment_url , array(
										'method'      => 'POST',
										'timeout'     => 45,
										'redirection' => 5,
										'httpversion' => '1.0',
										'blocking'    => true,
										'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
										'body'        => json_encode($data),
										'sslverify'   => FALSE
										)
									);
									
									$output = (is_array($response) && isset($response['body'])) ? json_decode($response['body'],true) : [];
										
										if($output){
											if(isset($output['status']) || isset($output['pickup_status'])){

												if(isset($output['status']) && is_array($output['status']) && $output['status'][0] != 'success'){
													// update_option('hit_aramex_status_'.$order_id, $output['status'][0]);
													$failed += 1;
													$failed_ids[] = $order_id;

												}else if(isset($output['status']) && $output['status'] == 'success'){
													$output['user_id'] = $create_shipment_for;
													$result_arr = get_option('hit_aramex_values_'.$order_id, "");
													$result_arr = !empty($result_arr) ? json_decode($result_arr, true) : array();
													$result_arr[] = $output;

													update_option('hit_aramex_values_'.$order_id, json_encode($result_arr));

													$success += 1;
													
												}
												if (isset($output['pickup_status']) && $output['pickup_status'] != 'Success') {
													$pic_res['status'] = "failed";
													update_option('hit_aramex_pickup_values_'.$order_id, json_encode($pic_res));
												}elseif (isset($output['pickup_status']) && $output['pickup_status'] == 'Success') {
													$pic_res['confirm_no'] = $output['pickup_confirm_no'];
													$pic_res['status'] = "success";

													update_option('hit_aramex_pickup_values_'.$order_id, json_encode($pic_res));
												}
											}else{
												$failed += 1;
												$failed_ids[] = $order_id;
											}
										}else{

											$failed += 1;
											$failed_ids[] = $order_id;
										}
									}
							}else{
								$failed += 1;
							}
							
						}
						return $redirect_to = add_query_arg( array(
							'success_lbl' => $success,
							'failed_lbl' => $failed,
							// 'failed_lbl_ids' => implode( ',', rtrim($failed_ids, ",") ),
						), $redirect_to );
					}
				}
				
			}

			function shipo_bulk_label_action_admin_notice() {
				if(isset($_GET['success_lbl']) && isset($_GET['failed_lbl'])){
					printf( '<div id="message" class="updated fade"><p>
						Generated labels: '. esc_html($_GET['success_lbl']) .' Failed Label: '. esc_html($_GET['failed_lbl']).' </p></div>');
				}

			}

			public function aramex_track($order){
				//
			}
			public function save_user_fields($user_id){
				if(isset($_POST['a2z_aramexexpress_country'])){
					$general_settings['a2z_aramexexpress_site_id'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_site_id']) ? $_POST['a2z_aramexexpress_site_id'] : '');
					$general_settings['a2z_aramexexpress_site_pwd'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_site_pwd']) ? $_POST['a2z_aramexexpress_site_pwd'] : '');
					$general_settings['a2z_aramexexpress_acc_no'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_acc_no']) ? $_POST['a2z_aramexexpress_acc_no'] : '');
					$general_settings['a2z_aramexexpress_entity'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_entity']) ? $_POST['a2z_aramexexpress_entity'] : '');
					$general_settings['a2z_aramexexpress_acc_pin'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_acc_pin']) ? $_POST['a2z_aramexexpress_acc_pin'] : '');
					$general_settings['a2z_aramexexpress_shipper_name'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_shipper_name']) ? $_POST['a2z_aramexexpress_shipper_name'] : '');
					$general_settings['a2z_aramexexpress_company'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_company']) ? $_POST['a2z_aramexexpress_company'] : '');
					$general_settings['a2z_aramexexpress_mob_num'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_mob_num']) ? $_POST['a2z_aramexexpress_mob_num'] : '');
					$general_settings['a2z_aramexexpress_email'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_email']) ? $_POST['a2z_aramexexpress_email'] : '');
					$general_settings['a2z_aramexexpress_address1'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_address1']) ? $_POST['a2z_aramexexpress_address1'] : '');
					$general_settings['a2z_aramexexpress_address2'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_address2']) ? $_POST['a2z_aramexexpress_address2'] : '');
					$general_settings['a2z_aramexexpress_city'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_city']) ? $_POST['a2z_aramexexpress_city'] : '');
					$general_settings['a2z_aramexexpress_state'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_state']) ? $_POST['a2z_aramexexpress_state'] : '');
					$general_settings['a2z_aramexexpress_zip'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_zip']) ? $_POST['a2z_aramexexpress_zip'] : '');
					$general_settings['a2z_aramexexpress_country'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_country']) ? $_POST['a2z_aramexexpress_country'] : '');
					$general_settings['a2z_aramexexpress_gstin'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_gstin']) ? $_POST['a2z_aramexexpress_gstin'] : '');
					$general_settings['a2z_aramexexpress_con_rate'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_con_rate']) ? $_POST['a2z_aramexexpress_con_rate'] : '');
					$general_settings['a2z_aramexexpress_def_dom'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_def_dom']) ? $_POST['a2z_aramexexpress_def_dom'] : '');

					$general_settings['a2z_aramexexpress_def_inter'] = sanitize_text_field(isset($_POST['a2z_aramexexpress_def_inter']) ? $_POST['a2z_aramexexpress_def_inter'] : '');

					update_post_meta($user_id,'a2z_aramex_vendor_settings',$general_settings);
				}

			}

			public function hit_define_aramex_credentails( $user ){
				global $aramex_core;
				$main_settings = get_option('a2z_aramex_main_settings');
				$main_settings = empty($main_settings) ? array() : $main_settings;
				$allow = false;
				
				if(!isset($main_settings['a2z_aramexexpress_v_roles'])){
					return;
				}else{
					foreach ($user->roles as $value) {
						if(in_array($value, $main_settings['a2z_aramexexpress_v_roles'])){
							$allow = true;
						}
					}
				}
				
				if(!$allow){
					return;
				}

				$general_settings = get_post_meta($user->ID,'a2z_aramex_vendor_settings',true);
				$general_settings = empty($general_settings) ? array() : $general_settings;
				$countires =  array(
									'AF' => 'Afghanistan',
									'AL' => 'Albania',
									'DZ' => 'Algeria',
									'AS' => 'American Samoa',
									'AD' => 'Andorra',
									'AO' => 'Angola',
									'AI' => 'Anguilla',
									'AG' => 'Antigua and Barbuda',
									'AR' => 'Argentina',
									'AM' => 'Armenia',
									'AW' => 'Aruba',
									'AU' => 'Australia',
									'AT' => 'Austria',
									'AZ' => 'Azerbaijan',
									'BS' => 'Bahamas',
									'BH' => 'Bahrain',
									'BD' => 'Bangladesh',
									'BB' => 'Barbados',
									'BY' => 'Belarus',
									'BE' => 'Belgium',
									'BZ' => 'Belize',
									'BJ' => 'Benin',
									'BM' => 'Bermuda',
									'BT' => 'Bhutan',
									'BO' => 'Bolivia',
									'BA' => 'Bosnia and Herzegovina',
									'BW' => 'Botswana',
									'BR' => 'Brazil',
									'VG' => 'British Virgin Islands',
									'BN' => 'Brunei',
									'BG' => 'Bulgaria',
									'BF' => 'Burkina Faso',
									'BI' => 'Burundi',
									'KH' => 'Cambodia',
									'CM' => 'Cameroon',
									'CA' => 'Canada',
									'CV' => 'Cape Verde',
									'KY' => 'Cayman Islands',
									'CF' => 'Central African Republic',
									'TD' => 'Chad',
									'CL' => 'Chile',
									'CN' => 'China',
									'CO' => 'Colombia',
									'KM' => 'Comoros',
									'CK' => 'Cook Islands',
									'CR' => 'Costa Rica',
									'HR' => 'Croatia',
									'CU' => 'Cuba',
									'CY' => 'Cyprus',
									'CZ' => 'Czech Republic',
									'DK' => 'Denmark',
									'DJ' => 'Djibouti',
									'DM' => 'Dominica',
									'DO' => 'Dominican Republic',
									'TL' => 'East Timor',
									'EC' => 'Ecuador',
									'EG' => 'Egypt',
									'SV' => 'El Salvador',
									'GQ' => 'Equatorial Guinea',
									'ER' => 'Eritrea',
									'EE' => 'Estonia',
									'ET' => 'Ethiopia',
									'FK' => 'Falkland Islands',
									'FO' => 'Faroe Islands',
									'FJ' => 'Fiji',
									'FI' => 'Finland',
									'FR' => 'France',
									'GF' => 'French Guiana',
									'PF' => 'French Polynesia',
									'GA' => 'Gabon',
									'GM' => 'Gambia',
									'GE' => 'Georgia',
									'DE' => 'Germany',
									'GH' => 'Ghana',
									'GI' => 'Gibraltar',
									'GR' => 'Greece',
									'GL' => 'Greenland',
									'GD' => 'Grenada',
									'GP' => 'Guadeloupe',
									'GU' => 'Guam',
									'GT' => 'Guatemala',
									'GG' => 'Guernsey',
									'GN' => 'Guinea',
									'GW' => 'Guinea-Bissau',
									'GY' => 'Guyana',
									'HT' => 'Haiti',
									'HN' => 'Honduras',
									'HK' => 'Hong Kong',
									'HU' => 'Hungary',
									'IS' => 'Iceland',
									'IN' => 'India',
									'ID' => 'Indonesia',
									'IR' => 'Iran',
									'IQ' => 'Iraq',
									'IE' => 'Ireland',
									'IL' => 'Israel',
									'IT' => 'Italy',
									'CI' => 'Ivory Coast',
									'JM' => 'Jamaica',
									'JP' => 'Japan',
									'JE' => 'Jersey',
									'JO' => 'Jordan',
									'KZ' => 'Kazakhstan',
									'KE' => 'Kenya',
									'KI' => 'Kiribati',
									'KW' => 'Kuwait',
									'KG' => 'Kyrgyzstan',
									'LA' => 'Laos',
									'LV' => 'Latvia',
									'LB' => 'Lebanon',
									'LS' => 'Lesotho',
									'LR' => 'Liberia',
									'LY' => 'Libya',
									'LI' => 'Liechtenstein',
									'LT' => 'Lithuania',
									'LU' => 'Luxembourg',
									'MO' => 'Macao',
									'MK' => 'Macedonia',
									'MG' => 'Madagascar',
									'MW' => 'Malawi',
									'MY' => 'Malaysia',
									'MV' => 'Maldives',
									'ML' => 'Mali',
									'MT' => 'Malta',
									'MH' => 'Marshall Islands',
									'MQ' => 'Martinique',
									'MR' => 'Mauritania',
									'MU' => 'Mauritius',
									'YT' => 'Mayotte',
									'MX' => 'Mexico',
									'FM' => 'Micronesia',
									'MD' => 'Moldova',
									'MC' => 'Monaco',
									'MN' => 'Mongolia',
									'ME' => 'Montenegro',
									'MS' => 'Montserrat',
									'MA' => 'Morocco',
									'MZ' => 'Mozambique',
									'MM' => 'Myanmar',
									'NA' => 'Namibia',
									'NR' => 'Nauru',
									'NP' => 'Nepal',
									'NL' => 'Netherlands',
									'NC' => 'New Caledonia',
									'NZ' => 'New Zealand',
									'NI' => 'Nicaragua',
									'NE' => 'Niger',
									'NG' => 'Nigeria',
									'NU' => 'Niue',
									'KP' => 'North Korea',
									'MP' => 'Northern Mariana Islands',
									'NO' => 'Norway',
									'OM' => 'Oman',
									'PK' => 'Pakistan',
									'PW' => 'Palau',
									'PA' => 'Panama',
									'PG' => 'Papua New Guinea',
									'PY' => 'Paraguay',
									'PE' => 'Peru',
									'PH' => 'Philippines',
									'PL' => 'Poland',
									'PT' => 'Portugal',
									'PR' => 'Puerto Rico',
									'QA' => 'Qatar',
									'CG' => 'Republic of the Congo',
									'RE' => 'Reunion',
									'RO' => 'Romania',
									'RU' => 'Russia',
									'RW' => 'Rwanda',
									'SH' => 'Saint Helena',
									'KN' => 'Saint Kitts and Nevis',
									'LC' => 'Saint Lucia',
									'VC' => 'Saint Vincent and the Grenadines',
									'WS' => 'Samoa',
									'SM' => 'San Marino',
									'ST' => 'Sao Tome and Principe',
									'SA' => 'Saudi Arabia',
									'SN' => 'Senegal',
									'RS' => 'Serbia',
									'SC' => 'Seychelles',
									'SL' => 'Sierra Leone',
									'SG' => 'Singapore',
									'SK' => 'Slovakia',
									'SI' => 'Slovenia',
									'SB' => 'Solomon Islands',
									'SO' => 'Somalia',
									'ZA' => 'South Africa',
									'KR' => 'South Korea',
									'SS' => 'South Sudan',
									'ES' => 'Spain',
									'LK' => 'Sri Lanka',
									'SD' => 'Sudan',
									'SR' => 'Suriname',
									'SZ' => 'Swaziland',
									'SE' => 'Sweden',
									'CH' => 'Switzerland',
									'SY' => 'Syria',
									'TW' => 'Taiwan',
									'TJ' => 'Tajikistan',
									'TZ' => 'Tanzania',
									'TH' => 'Thailand',
									'TG' => 'Togo',
									'TO' => 'Tonga',
									'TT' => 'Trinidad and Tobago',
									'TN' => 'Tunisia',
									'TR' => 'Turkey',
									'TC' => 'Turks and Caicos Islands',
									'TV' => 'Tuvalu',
									'VI' => 'U.S. Virgin Islands',
									'UG' => 'Uganda',
									'UA' => 'Ukraine',
									'AE' => 'United Arab Emirates',
									'GB' => 'United Kingdom',
									'US' => 'United States',
									'UY' => 'Uruguay',
									'UZ' => 'Uzbekistan',
									'VU' => 'Vanuatu',
									'VE' => 'Venezuela',
									'VN' => 'Vietnam',
									'YE' => 'Yemen',
									'ZM' => 'Zambia',
									'ZW' => 'Zimbabwe',
								);
				 $_aramex_carriers = array(
					//"Public carrier name" => "technical name",
					'CDS'                    => 'Domestic service Outbound',
					'RTC'                    => 'Domestic service Inbound',
					'EPX'                    => 'International service Outbound',
					'PDX'                    => 'Priority Document Express',
					'PPX'                    => 'Priority Parcel Express',
					'PLX'                    => 'Priority Letter Express',
					'DDX'                    => 'Deferred Document Express',
					'DPX'                    => 'Deferred Parcel Express ',
					'GDX'                    => 'Ground Document Express',
					'GPX'                    => 'Ground Parcel Express',
				);

				 echo '<hr><h3 class="heading">ARAMEX Express - <a href="https://hitshipo.com/" target="_blank">HITShipo</a></h3>';
				    ?>
				    
				    <table class="form-table">
						<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('ARAMEX Integration Team will give this details to you.','a2z_aramexexpress') ?>"></span>	<?php _e('ARAMEX XML API Site ID','a2z_aramexexpress') ?></h4>
							<p> <?php _e('Leave this field as empty to use default account.','a2z_aramexexpress') ?> </p>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_site_id" value="<?php echo (isset($general_settings['a2z_aramexexpress_site_id'])) ? $general_settings['a2z_aramexexpress_site_id'] : ''; ?>">
						</td>

					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('ARAMEX Integration Team will give this details to you.','a2z_aramexexpress') ?>"></span>	<?php _e('ARAMEX XML API Password','a2z_aramexexpress') ?></h4>
							<p> <?php _e('Leave this field as empty to use default account.','a2z_aramexexpress') ?> </p>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_site_pwd" value="<?php echo (isset($general_settings['a2z_aramexexpress_site_pwd'])) ? $general_settings['a2z_aramexexpress_site_pwd'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('ARAMEX Integration Team will give this details to you.','a2z_aramexexpress') ?>"></span>	<?php _e('ARAMEX Account Number','a2z_aramexexpress') ?></h4>
							<p> <?php _e('Leave this field as empty to use default account.','a2z_aramexexpress') ?> </p>
						</td>
						<td>
							
							<input type="text" name="a2z_aramexexpress_acc_no" value="<?php echo (isset($general_settings['a2z_aramexexpress_acc_no'])) ? $general_settings['a2z_aramexexpress_acc_no'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('ARAMEX Integration Team will give this details to you.','a2z_aramexexpress') ?>"></span>	<?php _e('ARAMEX Account Entity','a2z_aramexexpress') ?></h4>
							<p> <?php _e('Leave this field as empty to use default account.','a2z_aramexexpress') ?> </p>
						</td>
						<td>
							
							<input type="text" name="a2z_aramexexpress_entity" value="<?php echo (isset($general_settings['a2z_aramexexpress_entity'])) ? $general_settings['a2z_aramexexpress_entity'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('ARAMEX Integration Team will give this details to you.','a2z_aramexexpress') ?>"></span>	<?php _e('ARAMEX Account Pin','a2z_aramexexpress') ?></h4>
							<p> <?php _e('Leave this field as empty to use default account.','a2z_aramexexpress') ?> </p>
						</td>
						<td>
							
							<input type="text" name="a2z_aramexexpress_acc_pin" value="<?php echo (isset($general_settings['a2z_aramexexpress_acc_pin'])) ? $general_settings['a2z_aramexexpress_acc_pin'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Shipping Person Name','a2z_aramexexpress') ?>"></span>	<?php _e('Shipper Name','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_shipper_name" value="<?php echo (isset($general_settings['a2z_aramexexpress_shipper_name'])) ? $general_settings['a2z_aramexexpress_shipper_name'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Shipper Company Name.','a2z_aramexexpress') ?>"></span>	<?php _e('Company Name','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_company" value="<?php echo (isset($general_settings['a2z_aramexexpress_company'])) ? $general_settings['a2z_aramexexpress_company'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Shipper Mobile / Contact Number.','a2z_aramexexpress') ?>"></span>	<?php _e('Contact Number','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_mob_num" value="<?php echo (isset($general_settings['a2z_aramexexpress_mob_num'])) ? $general_settings['a2z_aramexexpress_mob_num'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Email Address of the Shipper.','a2z_aramexexpress') ?>"></span>	<?php _e('Email Address','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_email" value="<?php echo (isset($general_settings['a2z_aramexexpress_email'])) ? $general_settings['a2z_aramexexpress_email'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Address Line 1 of the Shipper from Address.','a2z_aramexexpress') ?>"></span>	<?php _e('Address Line 1','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_address1" value="<?php echo (isset($general_settings['a2z_aramexexpress_address1'])) ? $general_settings['a2z_aramexexpress_address1'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Address Line 2 of the Shipper from Address.','a2z_aramexexpress') ?>"></span>	<?php _e('Address Line 2','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_address2" value="<?php echo (isset($general_settings['a2z_aramexexpress_address2'])) ? $general_settings['a2z_aramexexpress_address2'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%;padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('City of the Shipper from address.','a2z_aramexexpress') ?>"></span>	<?php _e('City','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_city" value="<?php echo (isset($general_settings['a2z_aramexexpress_city'])) ? $general_settings['a2z_aramexexpress_city'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('State of the Shipper from address.','a2z_aramexexpress') ?>"></span>	<?php _e('State (Two Digit String)','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_state" value="<?php echo (isset($general_settings['a2z_aramexexpress_state'])) ? $general_settings['a2z_aramexexpress_state'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Postal/Zip Code.','a2z_aramexexpress') ?>"></span>	<?php _e('Postal/Zip Code','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_zip" value="<?php echo (isset($general_settings['a2z_aramexexpress_zip'])) ? $general_settings['a2z_aramexexpress_zip'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Country of the Shipper from Address.','a2z_aramexexpress') ?>"></span>	<?php _e('Country','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<select name="a2z_aramexexpress_country" class="wc-enhanced-select" style="width:210px;">
								<?php foreach($countires as $key => $value)
								{

									if(isset($general_settings['a2z_aramexexpress_country']) && ($general_settings['a2z_aramexexpress_country'] == $key))
									{
										echo "<option value=".$key." selected='true'>".$value." [". $aramex_core[$key]['currency'] ."]</option>";
									}
									else
									{
										echo "<option value=".$key.">".$value." [". $aramex_core[$key]['currency'] ."]</option>";
									}
								} ?>
							</select>
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('GSTIN/VAT No.','a2z_aramexexpress') ?>"></span>	<?php _e('GSTIN/VAT No','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_gstin" value="<?php echo (isset($general_settings['a2z_aramexexpress_gstin'])) ? $general_settings['a2z_aramexexpress_gstin'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Conversion Rate from Site Currency to ARAMEX Currency.','a2z_aramexexpress') ?>"></span>	<?php _e('Conversion Rate from Site Currency to ARAMEX Currency ( Ignore if auto conversion is Enabled )','a2z_aramexexpress') ?></h4>
						</td>
						<td>
							<input type="text" name="a2z_aramexexpress_con_rate" value="<?php echo (isset($general_settings['a2z_aramexexpress_con_rate'])) ? $general_settings['a2z_aramexexpress_con_rate'] : ''; ?>">
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Default Domestic Shipping.','a2z_aramexexpress') ?>"></span>	<?php _e('Default Domestic Service','a2z_aramexexpress') ?></h4>
							<p><?php _e('This will be used while shipping label generation.','a2z_aramexexpress') ?></p>
						</td>
						<td>
							<select name="a2z_aramexexpress_def_dom" class="wc-enhanced-select" style="width:210px;">
								<?php foreach($_aramex_carriers as $key => $value)
								{
									if(isset($general_settings['a2z_aramexexpress_def_dom']) && ($general_settings['a2z_aramexexpress_def_dom'] == $key))
									{
										echo "<option value=".$key." selected='true'>[".$key."] ".$value."</option>";
									}
									else
									{
										echo "<option value=".$key.">[".$key."] ".$value."</option>";
									}
								} ?>
							</select>
						</td>
					</tr>
					<tr>
						<td style=" width: 50%; padding: 5px; ">
							<h4> <span class="woocommerce-help-tip" data-tip="<?php _e('Default International Shipping.','a2z_aramexexpress') ?>"></span>	<?php _e('Default International Service','a2z_aramexexpress') ?></h4>
							<p><?php _e('This will be used while shipping label generation.','a2z_aramexexpress') ?></p>
						</td>
						<td>
							<select name="a2z_aramexexpress_def_inter" class="wc-enhanced-select" style="width:210px;">
								<?php foreach($_aramex_carriers as $key => $value)
								{
									if(isset($general_settings['a2z_aramexexpress_def_inter']) && ($general_settings['a2z_aramexexpress_def_inter'] == $key))
									{
										echo "<option value=".$key." selected='true'>[".$key."] ".$value."</option>";
									}
									else
									{
										echo "<option value=".$key.">[".$key."] ".$value."</option>";
									}
								} ?>
							</select>
						</td>
					</tr>
				    </table>
				    <hr>
				    <?php
			}
			public function hit_save_product_meta( $post_id ){
				if(isset( $_POST['aramex_express_shipment'])){
					$aramex_express_shipment = sanitize_text_field($_POST['aramex_express_shipment']);
					if( !empty( $aramex_express_shipment ) ){
						if ($this->hpos_enabled && $this->new_prod_editor_enabled) {
 	                        $hpos_prod_data = wc_get_product($post_id);
 	                        $hpos_prod_data->update_meta_data("aramex_express_address", (string) esc_html( $aramex_express_shipment ));
 	                    } else {
 	                    	update_post_meta( $post_id, 'aramex_express_address', (string) esc_html( $aramex_express_shipment ) );
 	                    }
					}
				}
							
			}
			public function hit_choose_vendor_address(){
				global $woocommerce, $post;
				$hit_multi_vendor = get_option('hit_multi_vendor');
				$hit_multi_vendor = empty($hit_multi_vendor) ? array() : $hit_multi_vendor;
				if ($this->hpos_enabled) {
				    $hpos_prod_data = wc_get_product($post->ID);
				    $selected_addr = $hpos_prod_data->get_meta("aramex_express_address");
				} else {
					$selected_addr = get_post_meta( $post->ID, 'aramex_express_address', true);
				}

				$main_settings = get_option('a2z_aramex_main_settings');
				$main_settings = empty($main_settings) ? array() : $main_settings;
				if(!isset($main_settings['a2z_aramexexpress_v_roles']) || empty($main_settings['a2z_aramexexpress_v_roles'])){
					return;
				}
				$v_users = get_users( [ 'role__in' => $main_settings['a2z_aramexexpress_v_roles'] ] );
				
				?>
				<div class="options_group">
				<p class="form-field aramex_express_shipment">
					<label for="aramex_express_shipment"><?php _e( 'ARAMEX Express Account', 'woocommerce' ); ?></label>
					<select id="aramex_express_shipment" style="width:240px;" name="aramex_express_shipment" class="wc-enhanced-select" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>">
						<option value="default" >Default Account</option>
						<?php
							if ( $v_users ) {
								foreach ( $v_users as $value ) {
									echo '<option value="' .  $value->data->ID  . '" '.($selected_addr == $value->data->ID ? 'selected="true"' : '').'>' . $value->data->display_name . '</option>';
								}
							}
						?>
					</select>
					</p>
				</div>
				<?php
			}

			public function a2z_aramexexpress_init()
			{
				include_once("controllors/a2z_aramexexpress_init.php");
			}
			public function hit_order_status_update(){
				global $woocommerce;
				if(isset($_GET['hitshipo_key'])){
					$hitshipo_key = sanitize_text_field($_GET['hitshipo_key']);
					if($hitshipo_key == 'fetch'){
						echo json_encode(array(get_transient('hitshipo_aramex_express_nonce_temp')));
						die();
					}
				}

				if(isset($_GET['hitshipo_integration_key']) && isset($_GET['hitshipo_action'])){
					$integration_key = sanitize_text_field($_GET['hitshipo_integration_key']);
					$hitshipo_action = sanitize_text_field($_GET['hitshipo_action']);
					$general_settings = get_option('a2z_aramex_main_settings');
					$general_settings = empty($general_settings) ? array() : $general_settings;
					if(isset($general_settings['a2z_aramexexpress_integration_key']) && $integration_key == $general_settings['a2z_aramexexpress_integration_key']){
						if($hitshipo_action == 'stop_working'){
							update_option('a2z_aramex_express_working_status', 'stop_working');
						}else if ($hitshipo_action = 'start_working'){
							update_option('a2z_aramex_express_working_status', 'start_working');
						}
					}
					
				}

				if(isset($_GET['h1t_updat3_0rd3r']) && isset($_GET['key']) && isset($_GET['action'])){
					$order_id = sanitize_text_field($_GET['h1t_updat3_0rd3r']);
					$key = sanitize_text_field($_GET['key']);
					$action = sanitize_text_field($_GET['action']);
					$order_ids = explode(",",$order_id);
					$general_settings = get_option('a2z_aramex_main_settings',array());
					
					if(isset($general_settings['a2z_aramexexpress_integration_key']) && $general_settings['a2z_aramexexpress_integration_key'] == $key){
						if($action == 'processing'){
							foreach ($order_ids as $order_id) {
								$order = wc_get_order( $order_id );
								$order->update_status( 'processing' );
							}
						}else if($action == 'completed'){
							foreach ($order_ids as $order_id) {
								  $order = wc_get_order( $order_id );
								  $order->update_status( 'completed' );
								  	
							}
						}
					}
					die();
				}

				if(isset($_GET['h1t_updat3_sh1pp1ng']) && isset($_GET['key']) && isset($_GET['user_id']) && isset($_GET['carrier']) && isset($_GET['track']) && isset($_GET['pic_status'])){
					$order_id = sanitize_text_field($_GET['h1t_updat3_sh1pp1ng']);
					$key = sanitize_text_field($_GET['key']);
					$general_settings = get_option('a2z_aramex_main_settings',array());
					$user_id = sanitize_text_field($_GET['user_id']);
					$carrier = sanitize_text_field($_GET['carrier']);
					$track = sanitize_text_field($_GET['track']);
					$pic_status = sanitize_text_field($_GET['pic_status']);
					$user = isset($_GET['label']) ? sanitize_text_field($_GET['label']) : "default";
					$output['status'] = 'success';
					$output['tracking_num'] = $track;
					$output['label'] = "https://app.hitshipo.com/api/shipping_labels/".$user_id."/".$carrier."/order_".$order_id."_track_".$track."_label.pdf";
					if (isset($_GET['inv_data']) && $_GET['inv_data'] == "yes") {
						$output['invoice'] = "https://app.hitshipo.com/api/shipping_labels/".$user_id."/".$carrier."/order_".$order_id."_track_".$track."_invoice.pdf";
					}
					if (isset($_GET['track_group']) && !empty($_GET['track_group'])) {
						$output['track_group'] = urldecode($_GET['track_group']);
					}
					$result_arr = array();
					$pic_res_arr = array();
					if(isset($general_settings['a2z_aramexexpress_integration_key']) && $general_settings['a2z_aramexexpress_integration_key'] == $key){
						
						if(isset($_GET['label'])){
							$output['user_id'] = sanitize_text_field($_GET['label']);
							if(isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes'){
								$result_arr = get_option('hit_aramex_values_'.$order_id, '');
								$pic_res_arr = get_option('hit_aramex_pickup_values_'.$order_id, '');
								$result_arr = !empty($result_arr) ? json_decode($result_arr, true) : array();
								$pic_res_arr = !empty($pic_res_arr) ? json_decode($pic_res_arr, true) : array();
							}
							$result_arr[] = $output;
						}else{
							$result_arr[] = $output;
						}

						update_option('hit_aramex_values_'.$order_id, json_encode($result_arr));
						
						if (isset($pic_status) && $pic_status == "success") {

							$pic_res['confirm_no'] = sanitize_text_field($_GET['confirm_no']);
							$pic_res['status'] = "success";

							$pic_res_arr[$user] = $pic_res;

							update_option('hit_aramex_pickup_values_'.$order_id, json_encode($pic_res_arr));

						}elseif (isset($pic_status) && $pic_status == "failed"){
							$pic_res['status'] = "failed";
							$pic_res_arr[$user] = $pic_res;
							update_option('hit_aramex_pickup_values_'.$order_id, json_encode($pic_res_arr));
						}
					}

					die();
				}
			}
			public function a2z_aramexexpress_method( $methods )
			{
				if (is_admin() && !is_ajax() || apply_filters('a2z_shipping_method_enabled', true)) {
					$methods['a2z_aramexexpress'] = 'a2z_aramexexpress'; 
				}

				return $methods;
			}
			
			public function a2z_aramexexpress_plugin_action_links($links)
			{
				$setting_value = version_compare(WC()->version, '2.1', '>=') ? "wc-settings" : "woocommerce_settings";
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=' . $setting_value  . '&tab=shipping&section=az_aramexexpress' ) . '" style="color:green;">' . __( 'Configure', 'a2z_aramexexpress' ) . '</a>',
					'<a href="https://app.hitshipo.com/support" target="_blank" >' . __('Support', 'a2z_aramexexpress') . '</a>'
					);
				return array_merge( $plugin_links, $links );
			}
			public function create_aramex_shipping_meta_box() {
				$meta_scrn = $this->hpos_enabled ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
	       		add_meta_box( 'hit_create_aramex_shipping', __('ARAMEX Shipping Label','a2z_aramexexpress'), array($this, 'create_aramex_shipping_label_genetation'), $meta_scrn, 'side', 'core' );
	       		// add_meta_box( 'hit_create_aramex_return_shipping', __('ARAMEX Return Label','a2z_aramexexpress'), array($this, 'create_aramex_return_label_genetation'), 'shop_order', 'side', 'core' );
		    }
		    public function create_aramex_shipping_label_genetation($post){
		    	// print_r('expression');
		    	// die();
		    	if(!$this->hpos_enabled && $post->post_type !='shop_order' ){
		    		return;
		    	}
		    	$order = (!$this->hpos_enabled) ? wc_get_order( $post->ID ) : $post;
		    	$order_id = $order->get_id();
		        $_aramex_carriers = array(
								'CDS'                    => 'Domestic service Outbound',
								'RTC'                    => 'Domestic service Inbound',
								'EPX'                    => 'International service Outbound',
								'PDX'                    => 'Priority Document Express',
								'PPX'                    => 'Priority Parcel Express',
								'PLX'                    => 'Priority Letter Express',
								'DDX'                    => 'Deferred Document Express',
								'DPX'                    => 'Deferred Parcel Express ',
								'GDX'                    => 'Ground Document Express',
								'GPX'                    => 'Ground Parcel Express',
							);

		        $general_settings = get_option('a2z_aramex_main_settings',array());
		       	
		        $items = $order->get_items();

    		    $custom_settings = array();
		    	$custom_settings['default'] =  array();
		    	$vendor_settings = array();

		    	$pack_products = array();
				
				foreach ( $items as $item ) {
					$product_data = $item->get_data();
				    $product = array();
				    $product['product_name'] = $product_data['name'];
				    $product['product_quantity'] = $product_data['quantity'];
				    $product['product_id'] = $product_data['product_id'];
				    
				    $pack_products[] = $product;
				    
				}

				if(isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes' && isset($general_settings['a2z_aramexexpress_v_labels']) && $general_settings['a2z_aramexexpress_v_labels'] == 'yes'){
					// Multi Vendor Enabled
					foreach ($pack_products as $key => $value) {
						$product_id = $value['product_id'];
						$cus_aramex_account = apply_filters('hits_aramex_custom_account', "", $product_id);
						if ($this->hpos_enabled) {
						    $hpos_prod_data = wc_get_product($product_id);
						    $aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : $hpos_prod_data->get_meta("aramex_express_address");
						} else {
							$aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : get_post_meta($product_id,'aramex_express_address', true);
						}
						if(empty($aramex_account) || $aramex_account == 'default'){
							$aramex_account = 'default';
							if (!isset($vendor_settings[$aramex_account])) {
								$vendor_settings[$aramex_account] = $custom_settings['default'];
							}
							
							$vendor_settings[$aramex_account]['products'][] = $value;
						}

						if($aramex_account != 'default'){
							$cus_user_account_data = apply_filters('hits_aramex_custom_account_info', [], $aramex_account);
							$user_account = !empty($cus_user_account_data) ? $cus_user_account_data : get_post_meta($aramex_account,'a2z_aramex_vendor_settings', true);
							$user_account = empty($user_account) ? array() : $user_account;
							if(!empty($user_account)){
								if(!isset($vendor_settings[$aramex_account])){

									$vendor_settings[$aramex_account] = $custom_settings['default'];
									unset($value['product_id']);
									$vendor_settings[$aramex_account]['products'][] = $value;
								}
							}else{
								$aramex_account = 'default';
								$vendor_settings[$aramex_account] = $custom_settings['default'];
								$vendor_settings[$aramex_account]['products'][] = $value;
							}
						}

					}

				}

				if(empty($vendor_settings)){
					$custom_settings['default']['products'] = $pack_products;
				}else{
					$custom_settings = $vendor_settings;
				}

		       	$json_data = get_option('hit_aramex_values_'.$order_id);
		       	$pickup_json_data = get_option('hit_aramex_pickup_values_'.$order_id);
		       	// echo $pickup_json_data;die();
		       	$notice = get_option('hit_aramex_status_'.$order_id, null);
		        if($notice && $notice != 'success'){
		        	echo "<p style='color:red'>".$notice."</p>";
		        	delete_option('hit_aramex_status_'.$order_id);
		        }
		        if(!empty($json_data)){
   					$array_data = json_decode( $json_data, true );
   					// echo '<pre>';print_r($array_data);die();
		       		if(isset($array_data[0])){
		       			foreach ($array_data as $key => $value) {
		       				if(isset($value['user_id'])){
		       					unset($custom_settings[$value['user_id']]);
		       				}
		       				if(isset($value['user_id']) && $value['user_id'] == 'default'){
		       					echo '<br/><b>'.__("Default Account", "a2z_aramexexpress").'</b><br/>';
		       				}else{
		       					$user = get_user_by( 'id', $value['user_id'] );
		       					echo '<br/><b>'.__("Account", "a2z_aramexexpress").':</b> <small>'.$user->display_name.'</small><br/>';
		       				}
		       				if (isset($value['track_group'])) {
		       					echo '<br/><b>'.__("Tracking No(s)", "a2z_aramexexpress").':</b> <small>'.$value['track_group'].'</small><br/>';
		       				}
			       			echo '<a href="'.$value['label'].'" target="_blank" style="background:#26a4db; color: #FFFF;border-color: #26a4db;box-shadow: 0px 1px 0px #26a4db;text-shadow: 0px 1px 0px #FFFF;margin-top:3px;" class="button button-primary"> '.__("Shipping Label", "a2z_aramexexpress").'</a> ';
			       			if (isset($value['invoice']) && !empty($value['invoice']) ) {
			       				echo ' <a href="'.$value['invoice'].'" target="_blank" class="button button-primary" style="margin-top:3px;"> '.__("Invoice", "a2z_aramexexpress").' </a><br/>';
			       			}
			       			// if (!empty($pickup_json_data)) {
			       			// 	$pickup_array_data = json_decode( $pickup_json_data, true );
					        // 	if (isset($pickup_array_data[$value['user_id']]) && isset($pickup_array_data[$value['user_id']]['status']) && $pickup_array_data[$value['user_id']]['status'] == "success") {
					        // 		echo '<h4>'.__("ARAMEX pickup details", "a2z_aramexexpress").':</h4>';
						       //  	echo '<b>GUID : </b>'.$pickup_array_data[$value['user_id']]['confirm_no'].'<br/>';
					        // 	}else{
						       //  	echo "<br/>".__('Pickup creation failed', 'a2z_aramexexpress')."<br/>";
					        // 	}
			       			// } else {
			       			// 	echo '<h4>'.__("Pickup request can only be created in automatic mode", "a2z_aramexexpress").'</h4>';
			       			// }
		       			}
		       		}else{
		       			$custom_settings = array();
		       			echo '<a href="'.$array_data['label'].'" target="_blank" style="background:#26a4db; color: #FFFF;border-color: #26a4db;box-shadow: 0px 1px 0px #26a4db;text-shadow: 0px 1px 0px #FFFF;" class="button button-primary"> '.__("Shipping Label", "a2z_aramexexpress").'</a> ';
		       			if (isset($array_data['invoice'])) {
		       				echo ' <a href="'.$array_data['invoice'].'" target="_blank" class="button button-primary"> '.__("Invoice", "a2z_aramexexpress").' </a>';
		       			}
		       		}
   				}
	       		foreach ($custom_settings as $ukey => $value) {
	       			if($ukey == 'default'){
	       				echo '<br/><b>'.__("Default Account", "a2z_aramexexpress").'</b>';
				        echo '<br/><select name="hit_aramex_express_service_code_default">';
				        if(!empty($general_settings['a2z_aramexexpress_carrier'])){
				        	foreach ($general_settings['a2z_aramexexpress_carrier'] as $key => $value) {
				        		echo "<option value='".$key."'>".$key .' - ' .__($_aramex_carriers[$key], "a2z_aramexexpress")."</option>";
				        	}
				        }
				        echo '</select>';
				        echo '<br/><b>'.__("Shipment Content", "a2z_aramexexpress").'</b>';
		        
				        echo '<br/><input type="text" style="width:250px;margin-bottom:10px;"  name="hit_aramex_shipment_content_default" placeholder="Shipment content" value="' . (($general_settings['a2z_aramexexpress_ship_content']) ? $general_settings['a2z_aramexexpress_ship_content'] : "") . '" >';
				        
				        echo '<br><button name="hit_aramex_create_label" value="default" style="background:#26a4db; color: #FFF;border-color: #26a4db;box-shadow: 0px 1px 0px #26a4db;text-shadow: 0px 1px 0px #FFFF;" class="button button-primary">'.__("Create Shipment", "a2z_aramexexpress").'</button>';
				        
	       			}else{

	       				$user = get_user_by( 'id', $ukey );
		       			echo '<br/><b>'.__("Account", "a2z_aramexexpress").':</b> <small>'.$user->display_name.'</small>';
				        echo '<br/><select name="hit_aramex_express_service_code_'.$ukey.'">';
				        if(!empty($general_settings['a2z_aramexexpress_carrier'])){
				        	foreach ($general_settings['a2z_aramexexpress_carrier'] as $key => $value) {
				        		echo "<option value='".$key."'>".$key .' - ' .__($_aramex_carriers[$key], "a2z_aramexexpress")."</option>";
				        	}
				        }
				        echo '</select>';
				        echo '<br/><b>'.__("Shipment Content", "a2z_aramexexpress").'</b>';
		        
				        echo '<br/><input type="text" style="width:250px;margin-bottom:10px;"  name="hit_aramex_shipment_content_'.$ukey.'" placeholder="Shipment content" value="' . (($general_settings['a2z_aramexexpress_ship_content']) ? $general_settings['a2z_aramexexpress_ship_content'] : "") . '" >';
				       
				        echo '<br><button name="hit_aramex_create_label" value="'.$ukey.'" style="background:#26a4db; color: #FFFF;border-color: #26a4db;box-shadow: 0px 1px 0px #26a4db;text-shadow: 0px 1px 0px #FFFF;" class="button button-primary">'.__("Create Shipment", "a2z_aramexexpress").'</button><br/>';
				        
	       			}
	       			
	       		}
		        
		        // if (!empty($pickup_json_data) && empty($json_data)) {
		        // 	$pickup_array_data = json_decode( $pickup_json_data, true );
		        // 	if (isset($pickup_array_data['status']) && $pickup_array_data['status'] == "failed") {
		        // 		echo "<br/>Pickup creation failed<br/>";
		        // 	}else{
		        // 	echo '<h4>ARAMEX pickup details:</h4>';
		        // 	echo '<b>GUID : </b>'.$pickup_array_data['confirm_no'].'<br/>';
		        // 	}
		        // }else {
		        // 	echo '<h4>Pickup request can only be created in automatic mode</h4>';
		        // }

		       	if(!empty($json_data)){
		       		echo '<br/><button name="hit_aramex_reset" class="button button-secondary" style="margin-top:3px;"> '.__("Reset Shipments", "a2z_aramexexpress").'</button>';
		       	}

		    }

		    public function create_aramex_return_label_genetation($post){

		    }

		    public function hit_wc_checkout_order_processed($order_id){
		    	// die();
		    	if ($this->hpos_enabled) {
	 		        if ('shop_order' !== Automattic\WooCommerce\Utilities\OrderUtil::get_order_type($order_id)) {
	 		            return;
	 		        }
	 		    } else {
					$post = get_post($order_id);
					if($post->post_type !='shop_order' ){
			    		return;
			    	}
				}
				
		    	$ship_content = !empty($_POST['hit_aramex_shipment_content']) ? sanitize_text_field($_POST['hit_aramex_shipment_content']) : 'Shipment Content';
		        $order = wc_get_order( $order_id );

		        $service_code = $multi_ven = '';
		        foreach( $order->get_shipping_methods() as $item_id => $item ){
					$service_code = $item->get_meta('a2z_aramex_service');
					$multi_ven = $item->get_meta('a2z_multi_ven');

				}
				// if(empty($service_code)){
				// 	return;
				// }
				$general_settings = get_option('a2z_aramex_main_settings',array());				
		    	$order_data = $order->get_data();
		    	$items = $order->get_items();
		    	
				$desination_country = (isset($order_data['shipping']['country']) && $order_data['shipping']['country'] != '') ? $order_data['shipping']['country'] : $order_data['billing']['country'];
				if(empty($service_code)){
					if( !isset($general_settings['a2z_aramexexpress_international_service']) && !isset($general_settings['a2z_aramexexpress_Domestic_service'])){
						return;
					}
					if (isset($general_settings['a2z_aramexexpress_country']) && $general_settings["a2z_aramexexpress_country"] == $desination_country && $general_settings['a2z_aramexexpress_Domestic_service'] != 'null'){
						$service_code = $general_settings['a2z_aramexexpress_Domestic_service'];
					} elseif (isset($general_settings['a2z_aramexexpress_country']) && $general_settings["a2z_aramexexpress_country"] != $desination_country && $general_settings['a2z_aramexexpress_international_service'] != 'null'){
						$service_code = $general_settings['a2z_aramexexpress_international_service'];
					} else {
						return;
					}
					
				}	

		    	if(!isset($general_settings['a2z_aramexexpress_label_automation']) || $general_settings['a2z_aramexexpress_label_automation'] != 'yes'){
		    		return;
		    	}

		    	$custom_settings = array();
				$custom_settings['default'] = array(
									'a2z_aramexexpress_site_id' => $general_settings['a2z_aramexexpress_site_id'],
									'a2z_aramexexpress_site_pwd' => $general_settings['a2z_aramexexpress_site_pwd'],
									'a2z_aramexexpress_acc_no' => $general_settings['a2z_aramexexpress_acc_no'],
									'a2z_aramexexpress_entity' => $general_settings['a2z_aramexexpress_entity'],
									'a2z_aramexexpress_acc_pin' => $general_settings['a2z_aramexexpress_acc_pin'],
									'a2z_aramexexpress_shipper_name' => $general_settings['a2z_aramexexpress_shipper_name'],
									'a2z_aramexexpress_company' => $general_settings['a2z_aramexexpress_company'],
									'a2z_aramexexpress_mob_num' => $general_settings['a2z_aramexexpress_mob_num'],
									'a2z_aramexexpress_email' => $general_settings['a2z_aramexexpress_email'],
									'a2z_aramexexpress_address1' => $general_settings['a2z_aramexexpress_address1'],
									'a2z_aramexexpress_address2' => $general_settings['a2z_aramexexpress_address2'],
									'a2z_aramexexpress_city' => $general_settings['a2z_aramexexpress_city'],
									'a2z_aramexexpress_state' => $general_settings['a2z_aramexexpress_state'],
									'a2z_aramexexpress_zip' => $general_settings['a2z_aramexexpress_zip'],
									'a2z_aramexexpress_country' => $general_settings['a2z_aramexexpress_country'],
									'a2z_aramexexpress_gstin' => $general_settings['a2z_aramexexpress_gstin'],
									'a2z_aramexexpress_con_rate' => $general_settings['a2z_aramexexpress_con_rate'],
									'service_code' => $service_code,
									'a2z_aramexexpress_label_email' => $general_settings['a2z_aramexexpress_label_email'],
								);
				$vendor_settings = array();



				if(!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM')
				{
					$aramex_mod_weight_unit = 'kg';
					$aramex_mod_dim_unit = 'cm';
				}elseif(!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'LB_IN')
				{
					$aramex_mod_weight_unit = 'lbs';
					$aramex_mod_dim_unit = 'in';
				}
				else
				{
					$aramex_mod_weight_unit = 'kg';
					$aramex_mod_dim_unit = 'cm';
				}
			    

				$pack_products = array();
				
				foreach ( $items as $item ) {
					$product_data = $item->get_data();

				    $product = array();
				    $product['product_name'] = str_replace('"', '', $product_data['name']);
				    $product['product_quantity'] = $product_data['quantity'];
				    $product['product_id'] = $product_data['product_id'];
				    if ($this->hpos_enabled) {
					    $hpos_prod_data = wc_get_product($product_data['product_id']);
					    $saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
					} else {
					    $saved_cc = get_post_meta( $product_data['product_id'], 'hits_aramex_cc', true);
					}
					if(!empty($saved_cc)){
						$product['commodity_code'] = $saved_cc;
					}
				    
				    $product_variation_id = $item->get_variation_id();
				    if(empty($product_variation_id) || $product_variation_id == 0){
				    	$getproduct = wc_get_product( $product_data['product_id'] );
				    }else{
				    	$getproduct = wc_get_product( $product_variation_id );
				    }
				    $woo_weight_unit = get_option('woocommerce_weight_unit');
					$woo_dimension_unit = get_option('woocommerce_dimension_unit');

					$aramex_mod_weight_unit = $aramex_mod_dim_unit = '';

				    $product['price'] = $getproduct->get_price();

				    if(!$product['price']){
						$product['price'] = (isset($product_data['total']) && isset($product_data['quantity'])) ? number_format(($product_data['total'] / $product_data['quantity']), 2) : 0;
					}

				    if ($woo_dimension_unit != $aramex_mod_dim_unit) {
				    	$prod_width = round($getproduct->get_width(), 3);
				    	$prod_height = round($getproduct->get_height(), 3);
				    	$prod_depth = round($getproduct->get_length(), 3);

				    	//wc_get_dimension( $dimension, $to_unit, $from_unit );
				    	$product['width'] = (!empty($prod_width) && $prod_width > 0) ? round(wc_get_dimension( $prod_width, $aramex_mod_dim_unit, $woo_dimension_unit ), 3) : 0.1 ;
				    	$product['height'] = (!empty($prod_height) && $prod_height > 0) ? round(wc_get_dimension( $prod_height, $aramex_mod_dim_unit, $woo_dimension_unit ), 3) : 0.1 ;
						$product['depth'] = (!empty($prod_depth) && $prod_depth > 0) ? round(wc_get_dimension( $prod_depth, $aramex_mod_dim_unit, $woo_dimension_unit ), 3) : 0.1 ;

				    }else {
				    	$product['width'] = round($getproduct->get_width(),3);
				    	$product['height'] = round($getproduct->get_height(),3);
				    	$product['depth'] = round($getproduct->get_length(),3);
				    }
				    
				    if ($woo_weight_unit != $aramex_mod_weight_unit) {
				    	$prod_weight = $getproduct->get_weight();
				    	$product['weight'] = (!empty($prod_depth) && $prod_depth > 0) ?  round(wc_get_weight( $prod_weight, $aramex_mod_weight_unit, $woo_weight_unit ), 3) : 0.1 ;
				    }else{
				    	$product['weight'] = round($getproduct->get_weight(),3);
					}
				    $pack_products[] = $product;
				    
				}

				if(isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes' && isset($general_settings['a2z_aramexexpress_v_labels']) && $general_settings['a2z_aramexexpress_v_labels'] == 'yes'){
					// Multi Vendor Enabled
					foreach ($pack_products as $key => $value) {
						$product_id = $value['product_id'];
						$cus_aramex_account = apply_filters('hits_aramex_custom_account', "", $product_id);
						if ($this->hpos_enabled) {
						    $hpos_prod_data = wc_get_product($product_id);
						    $aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : $hpos_prod_data->get_meta("aramex_express_address");
						} else {
							$aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : get_post_meta($product_id,'aramex_express_address', true);
						}
						if(empty($aramex_account) || $aramex_account == 'default'){
							$aramex_account = 'default';
							if (!isset($vendor_settings[$aramex_account])) {
								$vendor_settings[$aramex_account] = $custom_settings['default'];
							}
							
							$vendor_settings[$aramex_account]['products'][] = $value;
						}

						if($aramex_account != 'default'){
							$cus_user_account_data = apply_filters('hits_aramex_custom_account_info', [], $aramex_account);
							$user_account = !empty($cus_user_account_data) ? $cus_user_account_data : get_post_meta($aramex_account,'a2z_aramex_vendor_settings', true);
							$user_account = empty($user_account) ? array() : $user_account;
							if(!empty($user_account)){
								if(!isset($vendor_settings[$aramex_account])){

									$vendor_settings[$aramex_account] = $custom_settings['default'];
									
									if($user_account['a2z_aramexexpress_site_id'] != '' && $user_account['a2z_aramexexpress_site_pwd'] != '' && $user_account['a2z_aramexexpress_acc_no'] != ''){
										
										$vendor_settings[$aramex_account]['a2z_aramexexpress_site_id'] = $user_account['a2z_aramexexpress_site_id'];

										if($user_account['a2z_aramexexpress_site_pwd'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_site_pwd'] = $user_account['a2z_aramexexpress_site_pwd'];
										}

										if($user_account['a2z_aramexexpress_acc_no'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_no'] = $user_account['a2z_aramexexpress_acc_no'];
										}

										if($user_account['a2z_aramexexpress_entity'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_entity'] = $user_account['a2z_aramexexpress_entity'];
										}

										if($user_account['a2z_aramexexpress_acc_pin'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_pin'] = $user_account['a2z_aramexexpress_acc_pin'];
										}
										
									}

									if ($user_account['a2z_aramexexpress_address1'] != '' && $user_account['a2z_aramexexpress_city'] != '' && $user_account['a2z_aramexexpress_state'] != '' && $user_account['a2z_aramexexpress_zip'] != '' && $user_account['a2z_aramexexpress_country'] != '' && $user_account['a2z_aramexexpress_shipper_name'] != '') {
										
										if($user_account['a2z_aramexexpress_shipper_name'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_shipper_name'] = $user_account['a2z_aramexexpress_shipper_name'];
										}

										if($user_account['a2z_aramexexpress_company'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_company'] = $user_account['a2z_aramexexpress_company'];
										}

										if($user_account['a2z_aramexexpress_mob_num'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_mob_num'] = $user_account['a2z_aramexexpress_mob_num'];
										}

										if($user_account['a2z_aramexexpress_email'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_email'] = $user_account['a2z_aramexexpress_email'];
										}

										if ($user_account['a2z_aramexexpress_address1'] != '') {
											$vendor_settings[$aramex_account]['a2z_aramexexpress_address1'] = $user_account['a2z_aramexexpress_address1'];
										}

										$vendor_settings[$aramex_account]['a2z_aramexexpress_address2'] = $user_account['a2z_aramexexpress_address2'];
										
										if($user_account['a2z_aramexexpress_city'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_city'] = $user_account['a2z_aramexexpress_city'];
										}

										if($user_account['a2z_aramexexpress_state'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_state'] = $user_account['a2z_aramexexpress_state'];
										}

										if($user_account['a2z_aramexexpress_zip'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_zip'] = $user_account['a2z_aramexexpress_zip'];
										}

										if($user_account['a2z_aramexexpress_country'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_country'] = $user_account['a2z_aramexexpress_country'];
										}

										$vendor_settings[$aramex_account]['a2z_aramexexpress_gstin'] = $user_account['a2z_aramexexpress_gstin'];
										$vendor_settings[$aramex_account]['a2z_aramexexpress_con_rate'] = $user_account['a2z_aramexexpress_con_rate'];
									}

									if(isset($general_settings['a2z_aramexexpress_v_email']) && $general_settings['a2z_aramexexpress_v_email'] == 'yes'){
										$user_dat = get_userdata($aramex_account);
										$cus_ven_email = apply_filters('hits_aramex_custom_account_email', '', $user_account['a2z_aramexexpress_email'], $aramex_account);
										$vendor_settings[$aramex_account]['a2z_aramexexpress_label_email'] = !empty($cus_ven_email) ? $cus_ven_email : $user_dat->data->user_email;
									}
									
									if($multi_ven !=''){
										$array_ven = explode('|',$multi_ven);
										$scode = '';
										foreach ($array_ven as $key => $svalue) {
											$ex_service = explode("_", $svalue);
											if($ex_service[0] == $aramex_account){
												$vendor_settings[$aramex_account]['service_code'] = $ex_service[1];
											}
										}
										
										if($scode == ''){
											if($order_data['shipping']['country'] != $vendor_settings[$aramex_account]['a2z_aramexexpress_country']){
												$vendor_settings[$aramex_account]['service_code'] = $user_account['a2z_aramexexpress_def_inter'];
											}else{
												$vendor_settings[$aramex_account]['service_code'] = $user_account['a2z_aramexexpress_def_dom'];
											}
										}

									}else{
										if($order_data['shipping']['country'] != $vendor_settings[$aramex_account]['a2z_aramexexpress_country']){
											$vendor_settings[$aramex_account]['service_code'] = $user_account['a2z_aramexexpress_def_inter'];
										}else{
											$vendor_settings[$aramex_account]['service_code'] = $user_account['a2z_aramexexpress_def_dom'];
										}

									}
								}
								$vendor_settings[$aramex_account]['products'][] = $value;
							}
						}

					}

				}

				if(empty($vendor_settings)){
					$custom_settings['default']['products'] = $pack_products;
				}else{
					$custom_settings = $vendor_settings;
				}

				$order_id = $order_data['id'];
	       		$order_currency = $order_data['currency'];

	       		// $order_shipping_first_name = $order_data['shipping']['first_name'];
				// $order_shipping_last_name = $order_data['shipping']['last_name'];
				// $order_shipping_company = empty($order_data['shipping']['company']) ? $order_data['shipping']['first_name'] :  $order_data['shipping']['company'];
				// $order_shipping_address_1 = $order_data['shipping']['address_1'];
				// $order_shipping_address_2 = $order_data['shipping']['address_2'];
				// $order_shipping_city = $order_data['shipping']['city'];
				// $order_shipping_state = $order_data['shipping']['state'];
				// $order_shipping_postcode = $order_data['shipping']['postcode'];
				// $order_shipping_country = $order_data['shipping']['country'];
				// $order_shipping_phone = $order_data['billing']['phone'];
				// $order_shipping_email = $order_data['billing']['email'];

				$shipping_arr = (isset($order_data['shipping']['first_name']) && $order_data['shipping']['first_name'] != "") ? $order_data['shipping'] : $order_data['billing'];
                $order_shipping_first_name = $shipping_arr['first_name'];
                $order_shipping_last_name = $shipping_arr['last_name'];
                $order_shipping_company = empty($shipping_arr['company']) ? $shipping_arr['first_name'] :  $shipping_arr['company'];
                $order_shipping_address_1 = $shipping_arr['address_1'];
                $order_shipping_address_2 = $shipping_arr['address_2'];
                $order_shipping_city = $shipping_arr['city'];
                $order_shipping_state = $shipping_arr['state'];
                $order_shipping_postcode = $shipping_arr['postcode'];
                $order_shipping_country = $shipping_arr['country'];
                $order_shipping_phone = $order_data['billing']['phone'];
                $order_shipping_email = $order_data['billing']['email'];
				$shipping_charge = $order_data['shipping_total'];
				if(!empty($general_settings) && isset($general_settings['a2z_aramexexpress_integration_key'])){
					$mode = 'live';
					if(isset($general_settings['a2z_aramexexpress_test']) && $general_settings['a2z_aramexexpress_test']== 'yes'){
						$mode = 'test';
					}
					$execution = 'manual';
					if(isset($general_settings['a2z_aramexexpress_label_automation']) && $general_settings['a2z_aramexexpress_label_automation']== 'yes'){
						$execution = 'auto';
					}

					$boxes_to_shipo = array();
					if (isset($general_settings['a2z_aramexexpress_packing_type']) && $general_settings['a2z_aramexexpress_packing_type'] == "box") {
						if (isset($general_settings['a2z_aramexexpress_boxes']) && !empty($general_settings['a2z_aramexexpress_boxes'])) {
							foreach ($general_settings['a2z_aramexexpress_boxes'] as $box) {
								if ($box['enabled'] != 1) {
									continue;
								}else {
									$boxes_to_shipo[] = $box;
								}
							}
						}
					}


					foreach ($custom_settings as $key => $cvalue) {
						global $aramex_core;
						$frm_curr = get_option('woocommerce_currency');
						$to_curr = isset($aramex_core[$cvalue['a2z_aramexexpress_country']]) ? $aramex_core[$cvalue['a2z_aramexexpress_country']]['currency'] : '';
						$curr_con_rate = ( isset($cvalue['a2z_aramexexpress_con_rate']) && !empty($cvalue['a2z_aramexexpress_con_rate']) ) ? $cvalue['a2z_aramexexpress_con_rate'] : 0;

						if (!empty($frm_curr) && !empty($to_curr) && ($frm_curr != $to_curr) ) {
							if (isset($general_settings['a2z_aramexexpress_auto_con_rate']) && $general_settings['a2z_aramexexpress_auto_con_rate'] == "yes") {
								$current_date = date('m-d-Y', time());
								$ex_rate_data = get_option('a2z_aramex_ex_rate'.$key);
								$ex_rate_data = !empty($ex_rate_data) ? $ex_rate_data : array();
								if (empty($ex_rate_data) || (isset($ex_rate_data['date']) && $ex_rate_data['date'] != $current_date) ) {
									if (isset($cvalue['a2z_aramexexpress_country']) && !empty($cvalue['a2z_aramexexpress_country']) && isset($general_settings['a2z_aramexexpress_integration_key']) && !empty($general_settings['a2z_aramexexpress_integration_key'])) {
										
										$ex_rate_Request = json_encode(array('integrated_key' => $general_settings['a2z_aramexexpress_integration_key'],
															'from_curr' => $frm_curr,
															'to_curr' => $to_curr));

										$ex_rate_url = "https://app.hitshipo.com/get_exchange_rate.php";
										// $ex_rate_url = "http://localhost/hitshipo/get_exchange_rate.php";
										$ex_rate_response = wp_remote_post( $ex_rate_url , array(
														'method'      => 'POST',
														'timeout'     => 45,
														'redirection' => 5,
														'httpversion' => '1.0',
														'blocking'    => true,
														'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
														'body'        => $ex_rate_Request,
														'sslverify'   => FALSE
														)
													);

										$ex_rate_result = ( is_array($ex_rate_response) && isset($ex_rate_response['body'])) ? json_decode($ex_rate_response['body'], true) : array();

										if ( !empty($ex_rate_result) && isset($ex_rate_result['ex_rate']) && $ex_rate_result['ex_rate'] != "Not Found" ) {
											$ex_rate_result['date'] = $current_date;
											update_option('a2z_aramex_ex_rate'.$key, $ex_rate_result);
										}else {
											if (!empty($ex_rate_data)) {
												$ex_rate_data['date'] = $current_date;
												update_option('a2z_aramex_ex_rate'.$key, $ex_rate_data);
											}
										}
									}
								}
								$get_ex_rate = get_option('a2z_aramex_ex_rate'.$key, '');
								$get_ex_rate = !empty($get_ex_rate) ? $get_ex_rate : array();
								$curr_con_rate = ( !empty($get_ex_rate) && isset($get_ex_rate['ex_rate']) ) ? $get_ex_rate['ex_rate'] : 0;
							}
						}

						$c_codes = [];
						$ship_charge_con = "N";

						foreach($cvalue['products'] as $prod_to_shipo_key => $prod_to_shipo){
							if ($this->hpos_enabled) {
							    $hpos_prod_data = wc_get_product($prod_to_shipo['product_id']);
							    $saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
							} else {
								$saved_cc = get_post_meta( $prod_to_shipo['product_id'], 'hits_aramex_cc', true);
							}
							if(!empty($saved_cc)){
								$c_codes[] = $saved_cc;
							}

							if (!empty($frm_curr) && !empty($to_curr) && ($frm_curr != $to_curr) ) {
								if ($curr_con_rate > 0) {
									$cvalue['products'][$prod_to_shipo_key]['price'] = $prod_to_shipo['price'] * $curr_con_rate;
									if ($ship_charge_con = "N") {
										$shipping_charge = $shipping_charge * $curr_con_rate;
										$ship_charge_con = "Y";
									}
								}
							}
						}
						
								
						$pic_date = strtotime(date("Y-m-d H:i"));
						$pic_days_after = 0;
	                    if (isset($general_settings['a2z_aramexexpress_pickup_date']) && ($general_settings['a2z_aramexexpress_pickup_date'] > 0) ) {
	                      $pic_days_after = $general_settings['a2z_aramexexpress_pickup_date'];
	                      $pic_date = strtotime(date("Y-m-d H:i", strtotime("+".$pic_days_after." Weekday")));
	                    }

	                    $pic_open = ( isset($general_settings['a2z_aramexexpress_pickup_open_time']) && !empty($general_settings['a2z_aramexexpress_pickup_open_time']) ) ? strtotime(date("Y-m-d ".$general_settings['a2z_aramexexpress_pickup_open_time'], strtotime("+".$pic_days_after." Weekday"))) : '';
						$pic_close = ( isset($general_settings['a2z_aramexexpress_pickup_close_time']) && !empty($general_settings['a2z_aramexexpress_pickup_close_time']) ) ? strtotime(date("Y-m-d ".$general_settings['a2z_aramexexpress_pickup_close_time'], strtotime("+".$pic_days_after." Weekday"))) : '';

						//For Automatic Label Generation						
						
						$data = array();
						$data['integrated_key'] = $general_settings['a2z_aramexexpress_integration_key'];
						$data['carrier_type'] = 'aramex';
						$data['order_id'] = $order_id;
						$data['exec_type'] = $execution;
						$data['mode'] = $mode;
						$data['carrier_type'] = 'aramex';
						$data['ship_price'] = $order_data['shipping_total'];
						$data['meta'] = array(
							"site_id" => $cvalue['a2z_aramexexpress_site_id'],
							"password"  => $cvalue['a2z_aramexexpress_site_pwd'],
							"accountnum" => $cvalue['a2z_aramexexpress_acc_no'],
							"acc_entity" => $cvalue['a2z_aramexexpress_entity'],
							"acc_pin" => $cvalue['a2z_aramexexpress_acc_pin'],
							"t_company" => $order_shipping_company,
							"t_address1" => str_replace('"', '', $order_shipping_address_1),
							"t_address2" => str_replace('"', '', $order_shipping_address_2),
							"t_city" => $order_shipping_city,
							"t_state" => $order_shipping_state,
							"t_postal" => $order_shipping_postcode,
							"t_country" => $order_shipping_country,
							"t_name" => $order_shipping_first_name . ' '. $order_shipping_last_name,
							"t_phone" => $order_shipping_phone,
							"t_email" => $order_shipping_email,
							"dutiable" => $general_settings['a2z_aramexexpress_duty_payment'],
							"insurance" => $general_settings['a2z_aramexexpress_insure'],
							"pack_this" => "Y",
							"products" => $cvalue['products'],
							"pack_algorithm" => $general_settings['a2z_aramexexpress_packing_type'],
							"boxes" => $boxes_to_shipo,
							"max_weight" => $general_settings['a2z_aramexexpress_max_weight'],
							"sig_req" => ($general_settings['a2z_aramexexpress_sig_req'] == 'yes') ? "Y" : "N",
							"cod" => ($general_settings['a2z_aramexexpress_cod'] == 'yes') ? "Y" : "N",
							"service_code" => $service_code,
							"shipment_content" => $ship_content,
							"email_alert" => ( isset($general_settings['a2z_aramexexpress_email_alert']) && ($general_settings['a2z_aramexexpress_email_alert'] == 'yes') ) ? "Y" : "N",
							"s_company" => $cvalue['a2z_aramexexpress_company'],
							"s_address1" => $cvalue['a2z_aramexexpress_address1'],
							"s_address2" => $cvalue['a2z_aramexexpress_address2'],
							"s_city" => $cvalue['a2z_aramexexpress_city'],
							"s_state" => $cvalue['a2z_aramexexpress_state'],
							"s_postal" => $cvalue['a2z_aramexexpress_zip'],
							"s_country" => $cvalue['a2z_aramexexpress_country'],
							"gstin" => $cvalue['a2z_aramexexpress_gstin'],
							"s_name" => $cvalue['a2z_aramexexpress_shipper_name'],
							"s_phone" => $cvalue['a2z_aramexexpress_mob_num'],
							"s_email" => $cvalue['a2z_aramexexpress_email'],
							"sent_email_to" => $cvalue['a2z_aramexexpress_label_email'],
							"pic_exec_type" => (isset($general_settings['a2z_aramexexpress_pickup_automation']) && $general_settings['a2z_aramexexpress_pickup_automation'] == 'yes') ? "auto" : "manual",
				            "pic_pac_loc" => (isset($general_settings['a2z_aramexexpress_pickup_pac_loc']) ? $general_settings['a2z_aramexexpress_pickup_pac_loc'] : ''),
				            "pic_days_after" => (isset($general_settings['a2z_aramexexpress_pickup_date']) ? $general_settings['a2z_aramexexpress_pickup_date'] : ''),
				            "pic_open_time" => $pic_open,
				            "pic_close_time" => $pic_close,
				            "pic_mail_date" => date('c'),
				    		"pic_date" => $pic_date,
				    		"pic_status" => "Ready",
							"label" => $key,
							"translation" => ( (isset($general_settings['a2z_aramexexpress_translation']) && $general_settings['a2z_aramexexpress_translation'] == "yes" ) ? 'Y' : 'N'),
							"translation_key" => (isset($general_settings['a2z_aramexexpress_translation_key']) ? $general_settings['a2z_aramexexpress_translation_key'] : ''),
							"commodity_code" => $c_codes,
							"pay_type" => (isset($general_settings['a2z_aramexexpress_pay_type']) ? $general_settings['a2z_aramexexpress_pay_type'] : ''),
							"shipping_charge" => $shipping_charge,
							"label" => $key,
						);
					
						//Auto Shipment
						$auto_ship_url = "https://app.hitshipo.com/label_api/create_shipment.php";
						// $auto_ship_url = "http://localhost/hitshipo/label_api/create_shipment.php";
						wp_remote_post( $auto_ship_url , array(
							'method'      => 'POST',
							'timeout'     => 45,
							'redirection' => 5,
							'httpversion' => '1.0',
							'blocking'    => false,
							'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
							'body'        => json_encode($data),
							'sslverify'   => FALSE
							)
						);

					}
	       		
				}	
		    }

		    // Save the data of the Meta field
			public function hit_create_aramex_shipping( $order_id ) {
				if ($this->hpos_enabled) {
	 		        if ('shop_order' !== Automattic\WooCommerce\Utilities\OrderUtil::get_order_type($order_id)) {
	 		            return;
	 		        }
	 		    } else {
			    	$post = get_post($order_id);
			    	if($post->post_type !='shop_order' ){
			    		return;
			    	}
			    }
		    	
		    	if (  isset( $_POST[ 'hit_aramex_reset' ] ) ) {
		    		delete_option('hit_aramex_values_'.$order_id);
		    		delete_option('hit_aramex_pickup_values_'.$order_id);
		    	}

		    	if (  isset( $_POST['hit_aramex_create_label']) ) {
		    		$create_shipment_for = sanitize_text_field($_POST['hit_aramex_create_label']);
		           $service_code = sanitize_text_field($_POST['hit_aramex_express_service_code_'.$create_shipment_for]);
		           $ship_content = !empty($_POST['hit_aramex_shipment_content_'.$create_shipment_for]) ? sanitize_text_field($_POST['hit_aramex_shipment_content_'.$create_shipment_for]) : 'Shipment Content';
		           $pickup_mode = 'manual';
		           $order = wc_get_order( $order_id );
			       if($order){
		       		$order_data = $order->get_data();
			       		$order_id = $order_data['id'];
			       		$order_currency = $order_data['currency'];

			       		// $order_shipping_first_name = $order_data['shipping']['first_name'];
						// $order_shipping_last_name = $order_data['shipping']['last_name'];
						// $order_shipping_company = empty($order_data['shipping']['company']) ? $order_data['shipping']['first_name'] :  $order_data['shipping']['company'];
						// $order_shipping_address_1 = $order_data['shipping']['address_1'];
						// $order_shipping_address_2 = $order_data['shipping']['address_2'];
						// $order_shipping_city = $order_data['shipping']['city'];
						// $order_shipping_state = $order_data['shipping']['state'];
						// $order_shipping_postcode = $order_data['shipping']['postcode'];
						// $order_shipping_country = $order_data['shipping']['country'];
						// $order_shipping_phone = $order_data['billing']['phone'];
						// $order_shipping_email = $order_data['billing']['email'];

						$shipping_arr = (isset($order_data['shipping']['first_name']) && $order_data['shipping']['first_name'] != "") ? $order_data['shipping'] : $order_data['billing'];
						$order_shipping_first_name = $shipping_arr['first_name'];
						$order_shipping_last_name = $shipping_arr['last_name'];
						$order_shipping_company = empty($shipping_arr['company']) ? $shipping_arr['first_name'] :  $shipping_arr['company'];
						$order_shipping_address_1 = $shipping_arr['address_1'];
						$order_shipping_address_2 = $shipping_arr['address_2'];
						$order_shipping_city = $shipping_arr['city'];
						$order_shipping_state = $shipping_arr['state'];
						$order_shipping_postcode = $shipping_arr['postcode'];
						$order_shipping_country = $shipping_arr['country'];
						$order_shipping_phone = $order_data['billing']['phone'];
						$order_shipping_email = $order_data['billing']['email'];

						$shipping_charge = $order_data['shipping_total'];

						$items = $order->get_items();
						$pack_products = array();
						$general_settings = get_option('a2z_aramex_main_settings',array());

						foreach ( $items as $item ) {
							$product_data = $item->get_data();
						    $product = array();
						    $product['product_name'] = str_replace('"', '', $product_data['name']);
						    $product['product_quantity'] = $product_data['quantity'];
						   	$product['product_id'] = $product_data['product_id'];
						   	if ($this->hpos_enabled) {
							    $hpos_prod_data = wc_get_product($product_data['product_id']);
							    $saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
							} else {
							   	$saved_cc = get_post_meta( $product_data['product_id'], 'hits_aramex_cc', true);
							}
							if(!empty($saved_cc)){
								$product['commodity_code'] = $saved_cc;
							}

						    $product_variation_id = $item->get_variation_id();
						    if(empty($product_variation_id)){
						    	$getproduct = wc_get_product( $product_data['product_id'] );
						    }else{
						    	$getproduct = wc_get_product( $product_variation_id );
						    }
						    
						    $woo_weight_unit = get_option('woocommerce_weight_unit');
							$woo_dimension_unit = get_option('woocommerce_dimension_unit');

							$aramex_mod_weight_unit = $aramex_mod_dim_unit = '';

							if(!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'KG_CM')
							{
								$aramex_mod_weight_unit = 'kg';
								$aramex_mod_dim_unit = 'cm';
							}elseif(!empty($general_settings['a2z_aramexexpress_weight_unit']) && $general_settings['a2z_aramexexpress_weight_unit'] == 'LB_IN')
							{
								$aramex_mod_weight_unit = 'lbs';
								$aramex_mod_dim_unit = 'in';
							}
							else
							{
								$aramex_mod_weight_unit = 'kg';
								$aramex_mod_dim_unit = 'cm';
							}

						    $product['price'] = $getproduct->get_price();

						    if(!$product['price']){
								$product['price'] = (isset($product_data['total']) && isset($product_data['quantity'])) ? number_format(($product_data['total'] / $product_data['quantity']), 2) : 0;
							}

						    if ($woo_dimension_unit != $aramex_mod_dim_unit) {
					    	$prod_width = $getproduct->get_width();
					    	$prod_height = $getproduct->get_height();
					    	$prod_depth = $getproduct->get_length();

					    	//wc_get_dimension( $dimension, $to_unit, $from_unit );
					    	$product['width'] = (!empty($prod_width) && $prod_width > 0) ?  round(wc_get_dimension( $prod_width, $aramex_mod_dim_unit, $woo_dimension_unit ), 2) : 0.1 ;
					    	$product['height'] = (!empty($prod_height) && $prod_height > 0) ?  round(wc_get_dimension( $prod_height, $aramex_mod_dim_unit, $woo_dimension_unit ), 2) : 0.1 ;
							$product['depth'] = (!empty($prod_depth) && $prod_depth > 0) ?  round(wc_get_dimension( $prod_depth, $aramex_mod_dim_unit, $woo_dimension_unit ), 2) : 0.1 ;

						    }else {
						    	$product['width'] = $getproduct->get_width();
						    	$product['height'] = $getproduct->get_height();
						    	$product['depth'] = $getproduct->get_length();
						    }
						    
						    if ($woo_weight_unit != $aramex_mod_weight_unit) {
						    	$prod_weight = $getproduct->get_weight();
						    	$product['weight'] = (!empty($prod_weight) && $prod_weight > 0) ?  round(wc_get_weight( $prod_weight, $aramex_mod_weight_unit, $woo_weight_unit ), 2) : 0.1 ;
						    }else{
						    	$product['weight'] = $getproduct->get_weight();
							}

						    $pack_products[] = $product;
						    
						}
						
						$custom_settings = array();
						$custom_settings['default'] = array(
											'a2z_aramexexpress_site_id' => $general_settings['a2z_aramexexpress_site_id'],
											'a2z_aramexexpress_site_pwd' => $general_settings['a2z_aramexexpress_site_pwd'],
											'a2z_aramexexpress_acc_no' => $general_settings['a2z_aramexexpress_acc_no'],
											'a2z_aramexexpress_entity' => $general_settings['a2z_aramexexpress_entity'],
											'a2z_aramexexpress_acc_pin' => $general_settings['a2z_aramexexpress_acc_pin'],
											'a2z_aramexexpress_shipper_name' => $general_settings['a2z_aramexexpress_shipper_name'],
											'a2z_aramexexpress_company' => $general_settings['a2z_aramexexpress_company'],
											'a2z_aramexexpress_mob_num' => $general_settings['a2z_aramexexpress_mob_num'],
											'a2z_aramexexpress_email' => $general_settings['a2z_aramexexpress_email'],
											'a2z_aramexexpress_address1' => $general_settings['a2z_aramexexpress_address1'],
											'a2z_aramexexpress_address2' => $general_settings['a2z_aramexexpress_address2'],
											'a2z_aramexexpress_city' => $general_settings['a2z_aramexexpress_city'],
											'a2z_aramexexpress_state' => $general_settings['a2z_aramexexpress_state'],
											'a2z_aramexexpress_zip' => $general_settings['a2z_aramexexpress_zip'],
											'a2z_aramexexpress_country' => $general_settings['a2z_aramexexpress_country'],
											'a2z_aramexexpress_gstin' => $general_settings['a2z_aramexexpress_gstin'],
											'a2z_aramexexpress_con_rate' => $general_settings['a2z_aramexexpress_con_rate'],
											'service_code' => $service_code,
											'a2z_aramexexpress_label_email' => $general_settings['a2z_aramexexpress_label_email'],
										);
						$vendor_settings = array();
						if(isset($general_settings['a2z_aramexexpress_v_enable']) && $general_settings['a2z_aramexexpress_v_enable'] == 'yes' && isset($general_settings['a2z_aramexexpress_v_labels']) && $general_settings['a2z_aramexexpress_v_labels'] == 'yes'){
						// Multi Vendor Enabled
						foreach ($pack_products as $key => $value) {
							$product_id = $value['product_id'];
							$cus_aramex_account = apply_filters('hits_aramex_custom_account', "", $product_id);
							if ($this->hpos_enabled) {
							    $hpos_prod_data = wc_get_product($product_id);
							    $aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : $hpos_prod_data->get_meta("aramex_express_address");
							} else {
								$aramex_account = !empty($cus_aramex_account) ? $cus_aramex_account : get_post_meta($product_id,'aramex_express_address', true);
							}
							if(empty($aramex_account) || $aramex_account == 'default'){
								$aramex_account = 'default';
								if (!isset($vendor_settings[$aramex_account])) {
									$vendor_settings[$aramex_account] = $custom_settings['default'];
								}
								
								$vendor_settings[$aramex_account]['products'][] = $value;
							}

							if($aramex_account != 'default'){
								$cus_user_account_data = apply_filters('hits_aramex_custom_account_info', [], $aramex_account);
								$user_account = !empty($cus_user_account_data) ? $cus_user_account_data : get_post_meta($aramex_account,'a2z_aramex_vendor_settings', true);
								$user_account = empty($user_account) ? array() : $user_account;
								if(!empty($user_account)){
									if(!isset($vendor_settings[$aramex_account])){

										$vendor_settings[$aramex_account] = $custom_settings['default'];
										
									if($user_account['a2z_aramexexpress_site_id'] != '' && $user_account['a2z_aramexexpress_site_pwd'] != '' && $user_account['a2z_aramexexpress_acc_no'] != ''){
										
										$vendor_settings[$aramex_account]['a2z_aramexexpress_site_id'] = $user_account['a2z_aramexexpress_site_id'];

										if($user_account['a2z_aramexexpress_site_pwd'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_site_pwd'] = $user_account['a2z_aramexexpress_site_pwd'];
										}

										if($user_account['a2z_aramexexpress_acc_no'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_no'] = $user_account['a2z_aramexexpress_acc_no'];
										}

										if($user_account['a2z_aramexexpress_entity'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_entity'] = $user_account['a2z_aramexexpress_entity'];
										}

										if($user_account['a2z_aramexexpress_acc_pin'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_acc_pin'] = $user_account['a2z_aramexexpress_acc_pin'];
										}

									}

									if ($user_account['a2z_aramexexpress_address1'] != '' && $user_account['a2z_aramexexpress_city'] != '' && $user_account['a2z_aramexexpress_state'] != '' && $user_account['a2z_aramexexpress_zip'] != '' && $user_account['a2z_aramexexpress_country'] != '' && $user_account['a2z_aramexexpress_shipper_name'] != '') {
										
										if($user_account['a2z_aramexexpress_shipper_name'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_shipper_name'] = $user_account['a2z_aramexexpress_shipper_name'];
										}

										if($user_account['a2z_aramexexpress_company'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_company'] = $user_account['a2z_aramexexpress_company'];
										}

										if($user_account['a2z_aramexexpress_mob_num'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_mob_num'] = $user_account['a2z_aramexexpress_mob_num'];
										}

										if($user_account['a2z_aramexexpress_email'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_email'] = $user_account['a2z_aramexexpress_email'];
										}

										if ($user_account['a2z_aramexexpress_address1'] != '') {
											$vendor_settings[$aramex_account]['a2z_aramexexpress_address1'] = $user_account['a2z_aramexexpress_address1'];
										}

										$vendor_settings[$aramex_account]['a2z_aramexexpress_address2'] = $user_account['a2z_aramexexpress_address2'];
										
										if($user_account['a2z_aramexexpress_city'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_city'] = $user_account['a2z_aramexexpress_city'];
										}

										if($user_account['a2z_aramexexpress_state'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_state'] = $user_account['a2z_aramexexpress_state'];
										}

										if($user_account['a2z_aramexexpress_zip'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_zip'] = $user_account['a2z_aramexexpress_zip'];
										}

										if($user_account['a2z_aramexexpress_country'] != ''){
											$vendor_settings[$aramex_account]['a2z_aramexexpress_country'] = $user_account['a2z_aramexexpress_country'];
										}

										$vendor_settings[$aramex_account]['a2z_aramexexpress_gstin'] = $user_account['a2z_aramexexpress_gstin'];
										$vendor_settings[$aramex_account]['a2z_aramexexpress_con_rate'] = $user_account['a2z_aramexexpress_con_rate'];

									}
										
										if(isset($general_settings['a2z_aramexexpress_v_email']) && $general_settings['a2z_aramexexpress_v_email'] == 'yes'){
											$user_dat = get_userdata($aramex_account);
											$cus_ven_email = apply_filters('hits_aramex_custom_account_email', '', $user_account['a2z_aramexexpress_email'], $aramex_account);
											$vendor_settings[$aramex_account]['a2z_aramexexpress_label_email'] = !empty($cus_ven_email) ? $cus_ven_email : $user_dat->data->user_email;
										}
										

										if($order_data['shipping']['country'] != $vendor_settings[$aramex_account]['a2z_aramexexpress_country']){
											$vendor_settings[$aramex_account]['service_code'] = empty($service_code) ? $user_account['a2z_aramexexpress_def_inter'] : $service_code;
										}else{
											$vendor_settings[$aramex_account]['service_code'] = empty($service_code) ? $user_account['a2z_aramexexpress_def_dom'] : $service_code;
										}
									}
									$vendor_settings[$aramex_account]['products'][] = $value;
								}
							}

						}

					}

					if(empty($vendor_settings)){
						$custom_settings['default']['products'] = $pack_products;
					}else{
						$custom_settings = $vendor_settings;
					}

					if(!empty($general_settings) && isset($general_settings['a2z_aramexexpress_integration_key']) && isset($custom_settings[$create_shipment_for])){
						$mode = 'live';
						if(isset($general_settings['a2z_aramexexpress_test']) && $general_settings['a2z_aramexexpress_test']== 'yes'){
							$mode = 'test';
						}

						$execution = 'manual';
						
						$boxes_to_shipo = array();
						if (isset($general_settings['a2z_aramexexpress_packing_type']) && $general_settings['a2z_aramexexpress_packing_type'] == "box") {
							if (isset($general_settings['a2z_aramexexpress_boxes']) && !empty($general_settings['a2z_aramexexpress_boxes'])) {
								foreach ($general_settings['a2z_aramexexpress_boxes'] as $box) {
									if ($box['enabled'] != 1) {
										continue;
									}else {
										$boxes_to_shipo[] = $box;
									}
								}
							}
						}

						global $aramex_core;
						$frm_curr = get_option('woocommerce_currency');
						$to_curr = isset($aramex_core[$custom_settings[$create_shipment_for]['a2z_aramexexpress_country']]) ? $aramex_core[$custom_settings[$create_shipment_for]['a2z_aramexexpress_country']]['currency'] : '';
						$curr_con_rate = ( isset($custom_settings[$create_shipment_for]['a2z_aramexexpress_con_rate']) && !empty($custom_settings[$create_shipment_for]['a2z_aramexexpress_con_rate']) ) ? $custom_settings[$create_shipment_for]['a2z_aramexexpress_con_rate'] : 0;

						if (!empty($frm_curr) && !empty($to_curr) && ($frm_curr != $to_curr) ) {
							if (isset($general_settings['a2z_aramexexpress_auto_con_rate']) && $general_settings['a2z_aramexexpress_auto_con_rate'] == "yes") {
								$current_date = date('m-d-Y', time());
								$ex_rate_data = get_option('a2z_aramex_ex_rate'.$create_shipment_for);
								$ex_rate_data = !empty($ex_rate_data) ? $ex_rate_data : array();
								if (empty($ex_rate_data) || (isset($ex_rate_data['date']) && $ex_rate_data['date'] != $current_date) ) {
									if (isset($custom_settings[$create_shipment_for]['a2z_aramexexpress_country']) && !empty($custom_settings[$create_shipment_for]['a2z_aramexexpress_country']) && isset($general_settings['a2z_aramexexpress_integration_key']) && !empty($general_settings['a2z_aramexexpress_integration_key'])) {
													
										$ex_rate_Request = json_encode(array('integrated_key' => $general_settings['a2z_aramexexpress_integration_key'],
															'from_curr' => $frm_curr,
															'to_curr' => $to_curr));

										$ex_rate_url = "https://app.hitshipo.com/get_exchange_rate.php";
										// $ex_rate_url = "http://localhost/hitshipo/get_exchange_rate.php";
										$ex_rate_response = wp_remote_post( $ex_rate_url , array(
														'method'      => 'POST',
														'timeout'     => 45,
														'redirection' => 5,
														'httpversion' => '1.0',
														'blocking'    => true,
														'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
														'body'        => $ex_rate_Request,
														'sslverify'   => FALSE
														)
													);

										$ex_rate_result = ( is_array($ex_rate_response) && isset($ex_rate_response['body'])) ? json_decode($ex_rate_response['body'], true) : array();

										if ( !empty($ex_rate_result) && isset($ex_rate_result['ex_rate']) && $ex_rate_result['ex_rate'] != "Not Found" ) {
											$ex_rate_result['date'] = $current_date;
											update_option('a2z_aramex_ex_rate'.$create_shipment_for, $ex_rate_result);
										}else {
											if (!empty($ex_rate_data)) {
												$ex_rate_data['date'] = $current_date;
												update_option('a2z_aramex_ex_rate'.$create_shipment_for, $ex_rate_data);
											}
										}
									}
								}
								$get_ex_rate = get_option('a2z_aramex_ex_rate'.$create_shipment_for, '');
								$get_ex_rate = !empty($get_ex_rate) ? $get_ex_rate : array();
								$curr_con_rate = ( !empty($get_ex_rate) && isset($get_ex_rate['ex_rate']) ) ? $get_ex_rate['ex_rate'] : 0;
							}
						}

						$c_codes = [];
						$ship_charge_con = "N";

						foreach($custom_settings[$create_shipment_for]['products'] as $prod_to_shipo_key => $prod_to_shipo){
							if ($this->hpos_enabled) {
							    $hpos_prod_data = wc_get_product($prod_to_shipo['product_id']);
							    $saved_cc = $hpos_prod_data->get_meta("hits_aramex_cc");
							} else {
								$saved_cc = get_post_meta( $prod_to_shipo['product_id'], 'hits_aramex_cc', true);
							}
							if(!empty($saved_cc)){
								$c_codes[] = $saved_cc;
							}

							if (!empty($frm_curr) && !empty($to_curr) && ($frm_curr != $to_curr) ) {
								if ($curr_con_rate > 0) {
									$custom_settings[$create_shipment_for]['products'][$prod_to_shipo_key]['price'] = $prod_to_shipo['price'] * $curr_con_rate;
									if ($ship_charge_con = "N") {
										$shipping_charge = $shipping_charge * $curr_con_rate;
										$ship_charge_con = "Y";
									}
								}
							}
						}

						$pic_date = strtotime(date("Y-m-d H:i"));
						$pic_days_after = 0;
	                    if (isset($general_settings['a2z_aramexexpress_pickup_date']) && ($general_settings['a2z_aramexexpress_pickup_date'] > 0) ) {
	                      $pic_days_after = $general_settings['a2z_aramexexpress_pickup_date'];
	                      $pic_date = strtotime(date("Y-m-d H:i", strtotime("+".$pic_days_after." Weekday")));
	                    }

	                    $pic_open = ( isset($general_settings['a2z_aramexexpress_pickup_open_time']) && !empty($general_settings['a2z_aramexexpress_pickup_open_time']) ) ? strtotime(date("Y-m-d ".$general_settings['a2z_aramexexpress_pickup_open_time'], strtotime("+".$pic_days_after." Weekday"))) : '';
						$pic_close = ( isset($general_settings['a2z_aramexexpress_pickup_close_time']) && !empty($general_settings['a2z_aramexexpress_pickup_close_time']) ) ? strtotime(date("Y-m-d ".$general_settings['a2z_aramexexpress_pickup_close_time'], strtotime("+".$pic_days_after." Weekday"))) : '';
						
						$data = array();
						$data['integrated_key'] = $general_settings['a2z_aramexexpress_integration_key'];
						$data['carrier_type'] = 'aramex';
						$data['order_id'] = $order_id;
						$data['exec_type'] = $execution;
						$data['mode'] = $mode;
						$data['carrier_type'] = 'aramex';
						$data['meta'] = array(
							"site_id" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_site_id'],
							"password"  => $custom_settings[$create_shipment_for]['a2z_aramexexpress_site_pwd'],
							"accountnum" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_acc_no'],
							"acc_entity" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_entity'],
							"acc_pin" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_acc_pin'],
							"t_company" => $order_shipping_company,
							"t_address1" => str_replace('"', '', $order_shipping_address_1),
							"t_address2" => str_replace('"', '', $order_shipping_address_2),
							"t_city" => $order_shipping_city,
							"t_state" => $order_shipping_state,
							"t_postal" => $order_shipping_postcode,
							"t_country" => $order_shipping_country,
							"t_name" => $order_shipping_first_name . ' '. $order_shipping_last_name,
							"t_phone" => $order_shipping_phone,
							"t_email" => $order_shipping_email,
							"dutiable" => $general_settings['a2z_aramexexpress_duty_payment'],
							"insurance" => $general_settings['a2z_aramexexpress_insure'],
							"pack_this" => "Y",
							"products" => $custom_settings[$create_shipment_for]['products'],
							"pack_algorithm" => $general_settings['a2z_aramexexpress_packing_type'],
							"boxes" => $boxes_to_shipo,
							"max_weight" => $general_settings['a2z_aramexexpress_max_weight'],
							"sig_req" => ($general_settings['a2z_aramexexpress_sig_req'] == 'yes') ? "Y" : "N",
							"cod" => ($general_settings['a2z_aramexexpress_cod'] == 'yes') ? "Y" : "N",
							"service_code" => $custom_settings[$create_shipment_for]['service_code'],
							"shipment_content" => $ship_content,
							"email_alert" => ( isset($general_settings['a2z_aramexexpress_email_alert']) && ($general_settings['a2z_aramexexpress_email_alert'] == 'yes') ) ? "Y" : "N",
							"s_company" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_company'],
							"s_address1" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_address1'],
							"s_address2" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_address2'],
							"s_city" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_city'],
							"s_state" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_state'],
							"s_postal" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_zip'],
							"s_country" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_country'],
							"gstin" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_gstin'],
							"s_name" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_shipper_name'],
							"s_phone" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_mob_num'],
							"s_email" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_email'],
							"sent_email_to" => $custom_settings[$create_shipment_for]['a2z_aramexexpress_label_email'],
							"pic_exec_type" => $pickup_mode,
				            "pic_pac_loc" => (isset($general_settings['a2z_aramexexpress_pickup_pac_loc']) ? $general_settings['a2z_aramexexpress_pickup_pac_loc'] : ''),
				            "pic_days_after" => (isset($general_settings['a2z_aramexexpress_pickup_date']) ? $general_settings['a2z_aramexexpress_pickup_date'] : ''),
				            "pic_open_time" => $pic_open,
				            "pic_close_time" => $pic_close,
				            "pic_mail_date" => date('c'),
		    				"pic_date" => $pic_date,
		    				"pic_status" => "Ready",
							"translation" => ( (isset($general_settings['a2z_aramexexpress_translation']) && $general_settings['a2z_aramexexpress_translation'] == "yes" ) ? 'Y' : 'N'),
							"translation_key" => (isset($general_settings['a2z_aramexexpress_translation_key']) ? $general_settings['a2z_aramexexpress_translation_key'] : ''),
							"commodity_code" => $c_codes,
							"pay_type" => (isset($general_settings['a2z_aramexexpress_pay_type']) ? $general_settings['a2z_aramexexpress_pay_type'] : ''),
							"shipping_charge" => $shipping_charge,
							"label" => $create_shipment_for,
						);
						//Manual Shipment
						$manual_ship_url = "https://app.hitshipo.com/label_api/create_shipment.php";
						// $manual_ship_url = "http://localhost/hitshipo/label_api/create_shipment.php";
						$response = wp_remote_post( $manual_ship_url , array(
							'method'      => 'POST',
							'timeout'     => 45,
							'redirection' => 5,
							'httpversion' => '1.0',
							'blocking'    => true,
							'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
							'body'        => json_encode($data),
							'sslverify'   => FALSE
							)
						);

						$output = (is_array($response) && isset($response['body'])) ? json_decode($response['body'],true) : [];
							if($output){
								
								if(isset($output['status']) || isset($output['pickup_status'])){
									
									if(isset($output['status']) && $output['status'] != 'success'){
										   update_option('hit_aramex_status_'.$order_id, $output['status']);

									}else if(isset($output['status']) && $output['status'] == 'success'){								
										$output['user_id'] = $create_shipment_for;
										$val = get_option('hit_aramex_values_'.$order_id, []);
										$result_arr = array();
										if(!empty($val)){
											$result_arr = json_decode($val, true);
										}
										$result_arr[] = $output;
										update_option('hit_aramex_values_'.$order_id, json_encode($result_arr));
										
									}
									if (isset($output['pickup_status']) && $output['pickup_status'] != 'Success') {
										$pic_res['status'] = "failed";
										update_option('hit_aramex_pickup_values_'.$order_id, json_encode($pic_res));
									}elseif (isset($output['pickup_status']) && $output['pickup_status'] == 'Success') {
										$pic_res['confirm_no'] = $output['pickup_confirm_no'];
										$pic_res['status'] = "success";

										update_option('hit_aramex_pickup_values_'.$order_id, json_encode($pic_res));
									}
								}else{
									update_option('hit_aramex_status_'.$order_id, 'Site not Connected with HITShipo. Contact HITShipo Team.');
								}
							}else{
								update_option('hit_aramex_status_'.$order_id, 'Site not Connected with HITShipo. Contact HITShipo Team.');
							}
						}	
			       }
		        }
		    }

		    // Save the data of the Meta field
			public function hit_create_aramex_return_shipping( $order_id ) {

		    }
		}

		$aramex_core = array();
		$aramex_core['AD'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['AE'] = array('region' => 'AP', 'currency' =>'AED', 'weight' => 'KG_CM');
		$aramex_core['AF'] = array('region' => 'AP', 'currency' =>'AFN', 'weight' => 'KG_CM');
		$aramex_core['AG'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['AI'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['AL'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['AM'] = array('region' => 'AP', 'currency' =>'AMD', 'weight' => 'KG_CM');
		$aramex_core['AN'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'KG_CM');
		$aramex_core['AO'] = array('region' => 'AP', 'currency' =>'AOA', 'weight' => 'KG_CM');
		$aramex_core['AR'] = array('region' => 'AM', 'currency' =>'ARS', 'weight' => 'KG_CM');
		$aramex_core['AS'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['AT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['AU'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$aramex_core['AW'] = array('region' => 'AM', 'currency' =>'AWG', 'weight' => 'LB_IN');
		$aramex_core['AZ'] = array('region' => 'AM', 'currency' =>'AZN', 'weight' => 'KG_CM');
		$aramex_core['AZ'] = array('region' => 'AM', 'currency' =>'AZN', 'weight' => 'KG_CM');
		$aramex_core['GB'] = array('region' => 'EU', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$aramex_core['BA'] = array('region' => 'AP', 'currency' =>'BAM', 'weight' => 'KG_CM');
		$aramex_core['BB'] = array('region' => 'AM', 'currency' =>'BBD', 'weight' => 'LB_IN');
		$aramex_core['BD'] = array('region' => 'AP', 'currency' =>'BDT', 'weight' => 'KG_CM');
		$aramex_core['BE'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['BF'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['BG'] = array('region' => 'EU', 'currency' =>'BGN', 'weight' => 'KG_CM');
		$aramex_core['BH'] = array('region' => 'AP', 'currency' =>'BHD', 'weight' => 'KG_CM');
		$aramex_core['BI'] = array('region' => 'AP', 'currency' =>'BIF', 'weight' => 'KG_CM');
		$aramex_core['BJ'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['BM'] = array('region' => 'AM', 'currency' =>'BMD', 'weight' => 'LB_IN');
		$aramex_core['BN'] = array('region' => 'AP', 'currency' =>'BND', 'weight' => 'KG_CM');
		$aramex_core['BO'] = array('region' => 'AM', 'currency' =>'BOB', 'weight' => 'KG_CM');
		$aramex_core['BR'] = array('region' => 'AM', 'currency' =>'BRL', 'weight' => 'KG_CM');
		$aramex_core['BS'] = array('region' => 'AM', 'currency' =>'BSD', 'weight' => 'LB_IN');
		$aramex_core['BT'] = array('region' => 'AP', 'currency' =>'BTN', 'weight' => 'KG_CM');
		$aramex_core['BW'] = array('region' => 'AP', 'currency' =>'BWP', 'weight' => 'KG_CM');
		$aramex_core['BY'] = array('region' => 'AP', 'currency' =>'BYR', 'weight' => 'KG_CM');
		$aramex_core['BZ'] = array('region' => 'AM', 'currency' =>'BZD', 'weight' => 'KG_CM');
		$aramex_core['CA'] = array('region' => 'AM', 'currency' =>'CAD', 'weight' => 'LB_IN');
		$aramex_core['CF'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$aramex_core['CG'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$aramex_core['CH'] = array('region' => 'EU', 'currency' =>'CHF', 'weight' => 'KG_CM');
		$aramex_core['CI'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['CK'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
		$aramex_core['CL'] = array('region' => 'AM', 'currency' =>'CLP', 'weight' => 'KG_CM');
		$aramex_core['CM'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$aramex_core['CN'] = array('region' => 'AP', 'currency' =>'CNY', 'weight' => 'KG_CM');
		$aramex_core['CO'] = array('region' => 'AM', 'currency' =>'COP', 'weight' => 'KG_CM');
		$aramex_core['CR'] = array('region' => 'AM', 'currency' =>'CRC', 'weight' => 'KG_CM');
		$aramex_core['CU'] = array('region' => 'AM', 'currency' =>'CUC', 'weight' => 'KG_CM');
		$aramex_core['CV'] = array('region' => 'AP', 'currency' =>'CVE', 'weight' => 'KG_CM');
		$aramex_core['CY'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['CZ'] = array('region' => 'EU', 'currency' =>'CZK', 'weight' => 'KG_CM');
		$aramex_core['DE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['DJ'] = array('region' => 'EU', 'currency' =>'DJF', 'weight' => 'KG_CM');
		$aramex_core['DK'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
		$aramex_core['DM'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['DO'] = array('region' => 'AP', 'currency' =>'DOP', 'weight' => 'LB_IN');
		$aramex_core['DZ'] = array('region' => 'AM', 'currency' =>'DZD', 'weight' => 'KG_CM');
		$aramex_core['EC'] = array('region' => 'EU', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['EE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['EG'] = array('region' => 'AP', 'currency' =>'EGP', 'weight' => 'KG_CM');
		$aramex_core['ER'] = array('region' => 'EU', 'currency' =>'ERN', 'weight' => 'KG_CM');
		$aramex_core['ES'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['ET'] = array('region' => 'AU', 'currency' =>'ETB', 'weight' => 'KG_CM');
		$aramex_core['FI'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['FJ'] = array('region' => 'AP', 'currency' =>'FJD', 'weight' => 'KG_CM');
		$aramex_core['FK'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$aramex_core['FM'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['FO'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
		$aramex_core['FR'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['GA'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$aramex_core['GB'] = array('region' => 'EU', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$aramex_core['GD'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['GE'] = array('region' => 'AM', 'currency' =>'GEL', 'weight' => 'KG_CM');
		$aramex_core['GF'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['GG'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$aramex_core['GH'] = array('region' => 'AP', 'currency' =>'GHS', 'weight' => 'KG_CM');
		$aramex_core['GI'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$aramex_core['GL'] = array('region' => 'AM', 'currency' =>'DKK', 'weight' => 'KG_CM');
		$aramex_core['GM'] = array('region' => 'AP', 'currency' =>'GMD', 'weight' => 'KG_CM');
		$aramex_core['GN'] = array('region' => 'AP', 'currency' =>'GNF', 'weight' => 'KG_CM');
		$aramex_core['GP'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['GQ'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$aramex_core['GR'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['GT'] = array('region' => 'AM', 'currency' =>'GTQ', 'weight' => 'KG_CM');
		$aramex_core['GU'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['GW'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['GY'] = array('region' => 'AP', 'currency' =>'GYD', 'weight' => 'LB_IN');
		$aramex_core['HK'] = array('region' => 'AM', 'currency' =>'HKD', 'weight' => 'KG_CM');
		$aramex_core['HN'] = array('region' => 'AM', 'currency' =>'HNL', 'weight' => 'KG_CM');
		$aramex_core['HR'] = array('region' => 'AP', 'currency' =>'HRK', 'weight' => 'KG_CM');
		$aramex_core['HT'] = array('region' => 'AM', 'currency' =>'HTG', 'weight' => 'LB_IN');
		$aramex_core['HU'] = array('region' => 'EU', 'currency' =>'HUF', 'weight' => 'KG_CM');
		$aramex_core['IC'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['ID'] = array('region' => 'AP', 'currency' =>'IDR', 'weight' => 'KG_CM');
		$aramex_core['IE'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['IL'] = array('region' => 'AP', 'currency' =>'ILS', 'weight' => 'KG_CM');
		$aramex_core['IN'] = array('region' => 'AP', 'currency' =>'INR', 'weight' => 'KG_CM');
		$aramex_core['IQ'] = array('region' => 'AP', 'currency' =>'IQD', 'weight' => 'KG_CM');
		$aramex_core['IR'] = array('region' => 'AP', 'currency' =>'IRR', 'weight' => 'KG_CM');
		$aramex_core['IS'] = array('region' => 'EU', 'currency' =>'ISK', 'weight' => 'KG_CM');
		$aramex_core['IT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['JE'] = array('region' => 'AM', 'currency' =>'GBP', 'weight' => 'KG_CM');
		$aramex_core['JM'] = array('region' => 'AM', 'currency' =>'JMD', 'weight' => 'KG_CM');
		$aramex_core['JO'] = array('region' => 'AP', 'currency' =>'JOD', 'weight' => 'KG_CM');
		$aramex_core['JP'] = array('region' => 'AP', 'currency' =>'JPY', 'weight' => 'KG_CM');
		$aramex_core['KE'] = array('region' => 'AP', 'currency' =>'KES', 'weight' => 'KG_CM');
		$aramex_core['KG'] = array('region' => 'AP', 'currency' =>'KGS', 'weight' => 'KG_CM');
		$aramex_core['KH'] = array('region' => 'AP', 'currency' =>'KHR', 'weight' => 'KG_CM');
		$aramex_core['KI'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$aramex_core['KM'] = array('region' => 'AP', 'currency' =>'KMF', 'weight' => 'KG_CM');
		$aramex_core['KN'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['KP'] = array('region' => 'AP', 'currency' =>'KPW', 'weight' => 'LB_IN');
		$aramex_core['KR'] = array('region' => 'AP', 'currency' =>'KRW', 'weight' => 'KG_CM');
		$aramex_core['KV'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['KW'] = array('region' => 'AP', 'currency' =>'KWD', 'weight' => 'KG_CM');
		$aramex_core['KY'] = array('region' => 'AM', 'currency' =>'KYD', 'weight' => 'KG_CM');
		$aramex_core['KZ'] = array('region' => 'AP', 'currency' =>'KZF', 'weight' => 'LB_IN');
		$aramex_core['LA'] = array('region' => 'AP', 'currency' =>'LAK', 'weight' => 'KG_CM');
		$aramex_core['LB'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['LC'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'KG_CM');
		$aramex_core['LI'] = array('region' => 'AM', 'currency' =>'CHF', 'weight' => 'LB_IN');
		$aramex_core['LK'] = array('region' => 'AP', 'currency' =>'LKR', 'weight' => 'KG_CM');
		$aramex_core['LR'] = array('region' => 'AP', 'currency' =>'LRD', 'weight' => 'KG_CM');
		$aramex_core['LS'] = array('region' => 'AP', 'currency' =>'LSL', 'weight' => 'KG_CM');
		$aramex_core['LT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['LU'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['LV'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['LY'] = array('region' => 'AP', 'currency' =>'LYD', 'weight' => 'KG_CM');
		$aramex_core['MA'] = array('region' => 'AP', 'currency' =>'MAD', 'weight' => 'KG_CM');
		$aramex_core['MC'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['MD'] = array('region' => 'AP', 'currency' =>'MDL', 'weight' => 'KG_CM');
		$aramex_core['ME'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['MG'] = array('region' => 'AP', 'currency' =>'MGA', 'weight' => 'KG_CM');
		$aramex_core['MH'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['MK'] = array('region' => 'AP', 'currency' =>'MKD', 'weight' => 'KG_CM');
		$aramex_core['ML'] = array('region' => 'AP', 'currency' =>'COF', 'weight' => 'KG_CM');
		$aramex_core['MM'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['MN'] = array('region' => 'AP', 'currency' =>'MNT', 'weight' => 'KG_CM');
		$aramex_core['MO'] = array('region' => 'AP', 'currency' =>'MOP', 'weight' => 'KG_CM');
		$aramex_core['MP'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['MQ'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['MR'] = array('region' => 'AP', 'currency' =>'MRO', 'weight' => 'KG_CM');
		$aramex_core['MS'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['MT'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['MU'] = array('region' => 'AP', 'currency' =>'MUR', 'weight' => 'KG_CM');
		$aramex_core['MV'] = array('region' => 'AP', 'currency' =>'MVR', 'weight' => 'KG_CM');
		$aramex_core['MW'] = array('region' => 'AP', 'currency' =>'MWK', 'weight' => 'KG_CM');
		$aramex_core['MX'] = array('region' => 'AM', 'currency' =>'MXN', 'weight' => 'KG_CM');
		$aramex_core['MY'] = array('region' => 'AP', 'currency' =>'MYR', 'weight' => 'KG_CM');
		$aramex_core['MZ'] = array('region' => 'AP', 'currency' =>'MZN', 'weight' => 'KG_CM');
		$aramex_core['NA'] = array('region' => 'AP', 'currency' =>'NAD', 'weight' => 'KG_CM');
		$aramex_core['NC'] = array('region' => 'AP', 'currency' =>'XPF', 'weight' => 'KG_CM');
		$aramex_core['NE'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['NG'] = array('region' => 'AP', 'currency' =>'NGN', 'weight' => 'KG_CM');
		$aramex_core['NI'] = array('region' => 'AM', 'currency' =>'NIO', 'weight' => 'KG_CM');
		$aramex_core['NL'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['NO'] = array('region' => 'EU', 'currency' =>'NOK', 'weight' => 'KG_CM');
		$aramex_core['NP'] = array('region' => 'AP', 'currency' =>'NPR', 'weight' => 'KG_CM');
		$aramex_core['NR'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$aramex_core['NU'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
		$aramex_core['NZ'] = array('region' => 'AP', 'currency' =>'NZD', 'weight' => 'KG_CM');
		$aramex_core['OM'] = array('region' => 'AP', 'currency' =>'OMR', 'weight' => 'KG_CM');
		$aramex_core['PA'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['PE'] = array('region' => 'AM', 'currency' =>'PEN', 'weight' => 'KG_CM');
		$aramex_core['PF'] = array('region' => 'AP', 'currency' =>'XPF', 'weight' => 'KG_CM');
		$aramex_core['PG'] = array('region' => 'AP', 'currency' =>'PGK', 'weight' => 'KG_CM');
		$aramex_core['PH'] = array('region' => 'AP', 'currency' =>'PHP', 'weight' => 'KG_CM');
		$aramex_core['PK'] = array('region' => 'AP', 'currency' =>'PKR', 'weight' => 'KG_CM');
		$aramex_core['PL'] = array('region' => 'EU', 'currency' =>'PLN', 'weight' => 'KG_CM');
		$aramex_core['PR'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['PT'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['PW'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['PY'] = array('region' => 'AM', 'currency' =>'PYG', 'weight' => 'KG_CM');
		$aramex_core['QA'] = array('region' => 'AP', 'currency' =>'QAR', 'weight' => 'KG_CM');
		$aramex_core['RE'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['RO'] = array('region' => 'EU', 'currency' =>'RON', 'weight' => 'KG_CM');
		$aramex_core['RS'] = array('region' => 'AP', 'currency' =>'RSD', 'weight' => 'KG_CM');
		$aramex_core['RU'] = array('region' => 'AP', 'currency' =>'RUB', 'weight' => 'KG_CM');
		$aramex_core['RW'] = array('region' => 'AP', 'currency' =>'RWF', 'weight' => 'KG_CM');
		$aramex_core['SA'] = array('region' => 'AP', 'currency' =>'SAR', 'weight' => 'KG_CM');
		$aramex_core['SB'] = array('region' => 'AP', 'currency' =>'SBD', 'weight' => 'KG_CM');
		$aramex_core['SC'] = array('region' => 'AP', 'currency' =>'SCR', 'weight' => 'KG_CM');
		$aramex_core['SD'] = array('region' => 'AP', 'currency' =>'SDG', 'weight' => 'KG_CM');
		$aramex_core['SE'] = array('region' => 'EU', 'currency' =>'SEK', 'weight' => 'KG_CM');
		$aramex_core['SG'] = array('region' => 'AP', 'currency' =>'SGD', 'weight' => 'KG_CM');
		$aramex_core['SH'] = array('region' => 'AP', 'currency' =>'SHP', 'weight' => 'KG_CM');
		$aramex_core['SI'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['SK'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['SL'] = array('region' => 'AP', 'currency' =>'SLL', 'weight' => 'KG_CM');
		$aramex_core['SM'] = array('region' => 'EU', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['SN'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['SO'] = array('region' => 'AM', 'currency' =>'SOS', 'weight' => 'KG_CM');
		$aramex_core['SR'] = array('region' => 'AM', 'currency' =>'SRD', 'weight' => 'KG_CM');
		$aramex_core['SS'] = array('region' => 'AP', 'currency' =>'SSP', 'weight' => 'KG_CM');
		$aramex_core['ST'] = array('region' => 'AP', 'currency' =>'STD', 'weight' => 'KG_CM');
		$aramex_core['SV'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['SY'] = array('region' => 'AP', 'currency' =>'SYP', 'weight' => 'KG_CM');
		$aramex_core['SZ'] = array('region' => 'AP', 'currency' =>'SZL', 'weight' => 'KG_CM');
		$aramex_core['TC'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['TD'] = array('region' => 'AP', 'currency' =>'XAF', 'weight' => 'KG_CM');
		$aramex_core['TG'] = array('region' => 'AP', 'currency' =>'XOF', 'weight' => 'KG_CM');
		$aramex_core['TH'] = array('region' => 'AP', 'currency' =>'THB', 'weight' => 'KG_CM');
		$aramex_core['TJ'] = array('region' => 'AP', 'currency' =>'TJS', 'weight' => 'KG_CM');
		$aramex_core['TL'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['TN'] = array('region' => 'AP', 'currency' =>'TND', 'weight' => 'KG_CM');
		$aramex_core['TO'] = array('region' => 'AP', 'currency' =>'TOP', 'weight' => 'KG_CM');
		$aramex_core['TR'] = array('region' => 'AP', 'currency' =>'TRY', 'weight' => 'KG_CM');
		$aramex_core['TT'] = array('region' => 'AM', 'currency' =>'TTD', 'weight' => 'LB_IN');
		$aramex_core['TV'] = array('region' => 'AP', 'currency' =>'AUD', 'weight' => 'KG_CM');
		$aramex_core['TW'] = array('region' => 'AP', 'currency' =>'TWD', 'weight' => 'KG_CM');
		$aramex_core['TZ'] = array('region' => 'AP', 'currency' =>'TZS', 'weight' => 'KG_CM');
		$aramex_core['UA'] = array('region' => 'AP', 'currency' =>'UAH', 'weight' => 'KG_CM');
		$aramex_core['UG'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		$aramex_core['US'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['UY'] = array('region' => 'AM', 'currency' =>'UYU', 'weight' => 'KG_CM');
		$aramex_core['UZ'] = array('region' => 'AP', 'currency' =>'UZS', 'weight' => 'KG_CM');
		$aramex_core['VC'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['VE'] = array('region' => 'AM', 'currency' =>'VEF', 'weight' => 'KG_CM');
		$aramex_core['VG'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['VI'] = array('region' => 'AM', 'currency' =>'USD', 'weight' => 'LB_IN');
		$aramex_core['VN'] = array('region' => 'AP', 'currency' =>'VND', 'weight' => 'KG_CM');
		$aramex_core['VU'] = array('region' => 'AP', 'currency' =>'VUV', 'weight' => 'KG_CM');
		$aramex_core['WS'] = array('region' => 'AP', 'currency' =>'WST', 'weight' => 'KG_CM');
		$aramex_core['XB'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
		$aramex_core['XC'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
		$aramex_core['XE'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'LB_IN');
		$aramex_core['XM'] = array('region' => 'AM', 'currency' =>'EUR', 'weight' => 'LB_IN');
		$aramex_core['XN'] = array('region' => 'AM', 'currency' =>'XCD', 'weight' => 'LB_IN');
		$aramex_core['XS'] = array('region' => 'AP', 'currency' =>'SIS', 'weight' => 'KG_CM');
		$aramex_core['XY'] = array('region' => 'AM', 'currency' =>'ANG', 'weight' => 'LB_IN');
		$aramex_core['YE'] = array('region' => 'AP', 'currency' =>'YER', 'weight' => 'KG_CM');
		$aramex_core['YT'] = array('region' => 'AP', 'currency' =>'EUR', 'weight' => 'KG_CM');
		$aramex_core['ZA'] = array('region' => 'AP', 'currency' =>'ZAR', 'weight' => 'KG_CM');
		$aramex_core['ZM'] = array('region' => 'AP', 'currency' =>'ZMW', 'weight' => 'KG_CM');
		$aramex_core['ZW'] = array('region' => 'AP', 'currency' =>'USD', 'weight' => 'KG_CM');
		
	}
	$a2z_aramexexpress = new a2z_aramexexpress_parent();
}

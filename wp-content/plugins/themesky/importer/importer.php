<?php 
if( !class_exists('TS_Importer') ){
	class TS_Importer{

		public $selected_import_data = array();
		
		function __construct(){
			add_filter( 'ocdi/plugin_page_title', array($this, 'import_notice') );
			
			add_filter( 'ocdi/plugin_page_setup', array($this, 'import_page_setup') );
			add_action( 'ocdi/before_widgets_import', array($this, 'before_widgets_import') );
			add_filter( 'ocdi/import_files', array($this, 'import_files') );
			add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
			add_action( 'ocdi/after_import', array($this, 'after_import_setup') );
		}
		
		function import_notice( $plugin_title ){
			$allowed_html = array(
				'a' => array( 'href' => array(), 'target' => array() )
			);
			ob_start();
			?>
			<div class="ts-ocdi-notice-info">
				<p>
					<i class="fas fa-exclamation-circle"></i>
					<span><?php echo wp_kses( __('If you have any problem with importer, please read this article <a href="https://ocdi.com/import-issues/" target="_blank">https://ocdi.com/import-issues/</a> and check your hosting configuration, or contact our support team here <a href="https://skygroup.ticksy.com/" target="_blank">https://skygroup.ticksy.com/</a>.', 'themesky'), $allowed_html ); ?></span>
				</p>
			</div>
			<?php
			$plugin_title .= ob_get_clean();
			return $plugin_title;
		}
		
		function import_page_setup( $default_settings ){
			$default_settings['parent_slug'] = 'themes.php';
			$default_settings['page_title']  = esc_html__( 'Loobek - Import Demo Content' , 'themesky' );
			$default_settings['menu_title']  = esc_html__( 'Loobek Importer' , 'themesky' );
			$default_settings['capability']  = 'import';
			$default_settings['menu_slug']   = 'loobek-importer';
			return $default_settings;
		}
		
		function set_selected_import_data( $selected_import ){
			switch( $selected_import['import_file_name'] ){
				case 'Fashion Modern - Market':
					$this->selected_import_data = array(
						'folder_name' 		=> 'fashion-modern-market'
						,'homepage_name' 	=> 'Fashion Modern - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek'
					);
				break;
				case 'Fashion Beige':
					$this->selected_import_data = array(
						'folder_name' 		=> 'fashion-beige'
						,'homepage_name' 	=> 'Fashion Beige - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek-fashion-beige'
					);
				break;
				case 'Cosmetics':
					$this->selected_import_data = array(
						'folder_name' 		=> 'cosmetics'
						,'homepage_name' 	=> 'Cosmetics - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek-cosmetics'
					);
				break;
				case 'Accessories':
					$this->selected_import_data = array(
						'folder_name' 		=> 'accessories'
						,'homepage_name' 	=> 'Accessories - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek-accessories'
					);
				break;
				case 'Sport':
					$this->selected_import_data = array(
						'folder_name' 		=> 'sport'
						,'homepage_name' 	=> 'Fashion Sport - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek-sport'
					);
				break;
				case 'Cases':
					$this->selected_import_data = array(
						'folder_name' 		=> 'cases'
						,'homepage_name' 	=> 'Case - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek-cases'
					);
				break;
				case 'Drone':
					$this->selected_import_data = array(
						'folder_name' 		=> 'drone'
						,'homepage_name' 	=> 'Drone - 1'
						,'import_url'		=> 'https://import.theme-sky.com/loobek-drone'
					);
				break;
			}
		}
		
		function before_widgets_import( $selected_import ){
			$this->set_selected_import_data( $selected_import );
			
			global $wp_registered_sidebars;
			$file_path = dirname(__FILE__) . '/data/' . $this->selected_import_data['folder_name'] . '/custom_sidebars.txt';
			if( file_exists($file_path) ){
				$file_url = plugin_dir_url(__FILE__) . 'data/' . $this->selected_import_data['folder_name'] . '/custom_sidebars.txt';
				$custom_sidebars = wp_remote_get( $file_url );
				$custom_sidebars = maybe_unserialize( trim( $custom_sidebars['body'] ) );
				update_option('ts_custom_sidebars', $custom_sidebars);
				
				if( is_array($custom_sidebars) && !empty($custom_sidebars) ){
					foreach( $custom_sidebars as $name ){
						$custom_sidebar = array(
											'name' 			=> ''.$name.''
											,'id' 			=> sanitize_title($name)
											,'description' 	=> ''
											,'class'		=> 'ts-custom-sidebar'
										);
						if( !isset($wp_registered_sidebars[$custom_sidebar['id']]) ){
							$wp_registered_sidebars[$custom_sidebar['id']] = $custom_sidebar;
						}
					}
				}
			}
		}
		
		function import_files(){
			$import_files = array();
			$folder_names = array(
							'fashion-modern-market'	=> 'Fashion Modern - Market'
							,'fashion-beige'		=> 'Fashion Beige'
							,'cosmetics'			=> 'Cosmetics'
							,'accessories'			=> 'Accessories'
							,'sport'				=> 'Sport'
							,'cases'				=> 'Cases'
							,'drone'				=> 'Drone'
							);
			
			foreach( $folder_names as $folder => $name ){
				$import_files[] = array(
					'import_file_name'            => $name
					,'import_file_url'            => plugin_dir_url( __FILE__ ) . 'data/' . $folder . '/content.xml'
					,'import_widget_file_url'     => plugin_dir_url( __FILE__ ) . 'data/' . $folder . '/widget_data.wie'
					,'import_preview_image_url'   => plugin_dir_url( __FILE__ ) . 'data/' . $folder . '/preview.jpg'
					,'import_redux'               => array(
						array(
							'file_url'     => plugin_dir_url( __FILE__ ) . 'data/' . $folder . '/redux.json'
							,'option_name' => 'loobek_theme_options'
						)
					)
				);
			}
			
			return $import_files;
		}
		
		function after_import_setup( $selected_import ){
			set_time_limit(0);
			
			$this->set_selected_import_data( $selected_import );
			
			$this->woocommerce_settings();
			$this->menu_locations();
			$this->set_homepage();
			$this->import_revslider();
			$this->change_url();
			$this->set_elementor_site_settings();
			$this->set_product_image_size_settings();
			$this->update_category_ids_in_homepage_content();
			$this->update_mega_menu_content();
			$this->update_footer_content();
			$this->update_theme_options();
			$this->update_page_options();
			$this->delete_transients();
			$this->update_woocommerce_lookup_table();
			$this->update_menu_term_count();
		}
		
		function get_post_by_title($post_title, $post_type = 'page'){
			$query = new WP_Query(
						array(
							'post_type'               => $post_type
							,'title'                  => $post_title
							,'post_status'            => 'publish'
							,'posts_per_page'         => 1
							,'no_found_rows'          => true
							,'ignore_sticky_posts'    => true
							,'update_post_term_cache' => false
							,'update_post_meta_cache' => false
							,'orderby'                => 'post_date ID'
							,'order'                  => 'ASC'
						)
					);
		 
			if( ! empty( $query->post ) ){
				return $query->post;
			}
			return null;
		}
		
		/* WooCommerce Settings */
		function woocommerce_settings(){
			$woopages = array(
				'woocommerce_shop_page_id' 			=> 'Shop'
				,'woocommerce_cart_page_id' 		=> 'Cart'
				,'woocommerce_checkout_page_id' 	=> 'Checkout'
				,'woocommerce_myaccount_page_id' 	=> 'My Account'
				,'yith_wcwl_wishlist_page_id' 		=> 'Wishlist'
			);
			foreach( $woopages as $woo_page_name => $woo_page_title ) {
				$woopage = $this->get_post_by_title( $woo_page_title );
				if( isset( $woopage->ID ) && $woopage->ID ) {
					update_option($woo_page_name, $woopage->ID);
				}
			}
			
			if( class_exists('YITH_Woocompare') ){
				update_option('yith_woocompare_compare_button_in_products_list', 'yes');
			}
			
			if( class_exists('YITH_WCWL') ){
				update_option('yith_wcwl_show_on_loop', 'yes');
			}

			if( class_exists('WC_Admin_Notices') ){
				WC_Admin_Notices::remove_notice('install');
			}
			delete_transient( '_wc_activation_redirect' );
			
			flush_rewrite_rules();
		}
		
		/* Menu Locations */
		function menu_locations(){
			$locations = get_theme_mod( 'nav_menu_locations' );
			$menus = wp_get_nav_menus();
			
			switch( $this->selected_import_data['folder_name'] ){
				case 'drone':
					$main_menu_name = 'Menu Drone';
				break;
				case 'cases':
					$main_menu_name = 'Menu Case 01';
				break;
				default:
					$main_menu_name = 'Main Menu';
			}

			if( $menus ){
				foreach( $menus as $menu ){
					if( $menu->name == $main_menu_name ){
						$locations['primary'] = $menu->term_id;
					}
				}
			}
			set_theme_mod( 'nav_menu_locations', $locations );
		}
		
		/* Set Homepage */
		function set_homepage(){
			$homepage = $this->get_post_by_title( $this->selected_import_data['homepage_name'] );
			if( isset( $homepage->ID ) ){
				update_option('show_on_front', 'page');
				update_option('page_on_front', $homepage->ID);
			}
		}
		
		/* Import Revolution Slider */
		function import_revslider(){
			if ( class_exists( 'RevSliderSliderImport' ) ) {
				$rev_directory = dirname(__FILE__) . '/data/' . $this->selected_import_data['folder_name'] . '/revslider/';
			
				foreach( glob( $rev_directory . '*.zip' ) as $file ){
					$import = new RevSliderSliderImport();
					$import->import_slider(true, $file);  
				}
			}
		}
		
		/* Change url */
		function change_url(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			$import_url = $this->selected_import_data['import_url'];
			$site_url = get_option( 'siteurl', '' );
			$wpdb->query("update `{$wp_prefix}posts` set `guid` = replace(`guid`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}posts` set `post_content` = replace(`post_content`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}posts` set `post_title` = replace(`post_title`, '{$import_url}', '{$site_url}') where post_type='nav_menu_item';");
			$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '{$import_url}', '{$site_url}');");
			$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . str_replace( '/', '\\\/', $import_url ) . "', '" . str_replace( '/', '\\\/', $site_url ) . "') where `meta_key` = '_elementor_data';");
			
			$option_name = 'loobek_theme_options';
			$option_ids = array(
						'ts_logo'
						,'ts_logo_mobile'
						,'ts_logo_sticky'
						,'ts_logo_transparent_header'
						,'ts_favicon'
						,'ts_custom_loading_image'
						,'ts_tiny_account_custom_links'
						,'ts_store_notice'
						,'ts_bg_breadcrumbs'
						,'ts_prod_placeholder_img'
						);
			$theme_options = get_option($option_name);
			if( is_array($theme_options) ){
				foreach( $option_ids as $option_id ){
					if( isset($theme_options[$option_id]) ){
						$theme_options[$option_id] = str_replace($import_url, $site_url, $theme_options[$option_id]);
					}
				}
				update_option($option_name, $theme_options);
			}
			
			/* Update Widgets */
			$widgets = array(
				'media_image' 					=> array('url', 'link_url')
				,'ts_mailchimp_subscription' 	=> array('bg_img')
			);
			foreach( $widgets as $base => $fields ){
				$widget_instances = get_option( 'widget_' . $base, array() );
				if( is_array($widget_instances) ){
					foreach( $widget_instances as $number => $instance ){
						if( $number == '_multiwidget' ){
							continue;
						}
						foreach( $fields as $field ){
							if( isset($widget_instances[$number][$field]) ){
								$widget_instances[$number][$field] = str_replace($import_url, $site_url, $widget_instances[$number][$field]);
							}
						}
					}
					update_option( 'widget_' . $base, $widget_instances );
				}
			}
			
			/* Slider Revolution */
			if ( class_exists( 'RevSliderSliderImport' ) ) {
				$slides = $wpdb->get_results('select * from '.$wp_prefix.'revslider_slides');
				if( is_array($slides) ){
					foreach( $slides as $slide ){
						$layers = json_decode($slide->layers);
						if( is_object($layers) ){
							foreach( $layers as $key => $layer ){
								if( isset($layers->$key->actions->action) && is_array($layers->$key->actions->action) ){
									foreach( $layers->$key->actions->action as $k => $a ){
										if( isset($layers->$key->actions->action[$k]->image_link) ){
											$layers->$key->actions->action[$k]->image_link = str_replace($import_url, $site_url, $layers->$key->actions->action[$k]->image_link);
										}
									}
								}
							}
						}
						
						$layers = addslashes(json_encode($layers));
						
						$wpdb->query( "update `{$wp_prefix}revslider_slides` set `layers`='{$layers}' where `id`={$slide->id}" );
					}
				}
			}
		}
		
		/* Set Elementor Site Settings */
		function set_elementor_site_settings(){
			$id = 0;
			
			$args = array(
				'post_type' 		=> 'elementor_library'
				,'post_status' 		=> 'public'
				,'posts_per_page'	=> 1
				,'orderby'			=> 'date'
				,'order'			=> 'ASC' /* Date is not changed when import. Use imported post */
			);
			
			$posts = new WP_Query( $args );
			if( $posts->have_posts() ){
				$id = $posts->post->ID;
				update_option('elementor_active_kit', $id);
			}
			
			if( $id ){ /* Fixed width, space, ... if query does not return the imported post */
				$page_settings = get_post_meta($id, '_elementor_page_settings', true);
			
				if( !is_array($page_settings) ){
					$page_settings = array();
				}
					
				if( !isset($page_settings['container_width']) ){
					$page_settings['container_width'] = array();
				}
				
				$page_settings['container_width']['unit'] = '%';
				$page_settings['container_width']['size'] = 100;
				$page_settings['container_width']['sizes'] = array();
				
				if( !isset($page_settings['space_between_widgets']) ){
					$page_settings['space_between_widgets'] = array();
				}
				
				$page_settings['space_between_widgets']['unit'] = 'px';
				$page_settings['space_between_widgets']['column'] = 20;
				$page_settings['space_between_widgets']['row'] = 20;
				$page_settings['space_between_widgets']['sizes'] = array();
				
				$page_settings['page_title_selector'] = 'h1.entry-title';
				$page_settings['stretched_section_container'] = '#main';
				
				update_post_meta($id, '_elementor_page_settings', $page_settings);
			}
			
			/* Use color, font from theme */
			update_option('elementor_disable_color_schemes', 'yes');
			update_option('elementor_disable_typography_schemes', 'yes');
			
			/* Flexbox Container */
			update_option('elementor_experiment-container', 'active'); /* check later */
		}
		
		/* Set Product Image Size Settings */
		function set_product_image_size_settings(){
			$options = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market': case 'fashion-beige': case 'sport': case 'cases':
					$options = array(
						'woocommerce_single_image_width' 				=> 1000
						,'woocommerce_thumbnail_image_width' 			=> 600
						,'woocommerce_thumbnail_cropping' 				=> 'custom'
						,'woocommerce_thumbnail_cropping_custom_width' 	=> 600
						,'woocommerce_thumbnail_cropping_custom_height' => 799
						,'yith_woocompare_image_size'					=> array( 'width' => '600', 'height' => '799', 'crop' => 1 )
					);
				break;
				
				case 'cosmetics': case 'accessories': case 'drone':
					$options = array(
						'woocommerce_single_image_width' 				=> 1000
						,'woocommerce_thumbnail_image_width' 			=> 600
						,'woocommerce_thumbnail_cropping' 				=> 'custom'
						,'woocommerce_thumbnail_cropping_custom_width' 	=> 600
						,'woocommerce_thumbnail_cropping_custom_height' => 600
						,'yith_woocompare_image_size'					=> array( 'width' => '600', 'height' => '600', 'crop' => 1 )
					);
				break;
			}
			
			foreach( $options as $key => $value ){
				update_option( $key, $value );
			}
		}
		
		/* Update Category Ids In Homepage Content */
		function update_category_ids_in_homepage_content(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			
			$pages = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market':
					$pages = array(
						'Fashion Modern - 1'	=> array(
								array(
									'774,840,839,838,841,837,836'
									,array( 'Shoes', 'Handbags', 'Glasses', 'Jewelry', 'Cosmetics', 'Watches', 'Perfumes' )
									,'ids'
								)
						)
						,'Fashion Modern – 2'	=> array(
								array(
									'774,692,855,697,766,840,837,856,839,690,769'
									,array( 'Shoes', 'Jeans', 'Hats', 'Dresses', 'T-Shirts', 'Handbags', 'Watches', 'Socks', 'Glasses', 'Jackets', 'Lingerie' )
									,'ids'
								)
								,array(
									'901'
									,array( 'New Arrivals' )
									,'categories'
									,'category'
								)
						)
						,'Fashion Modern - 4'	=> array(
								array(
									'687'
									,array( 'Men' )
									,'product_cats'
								)
								,array(
									'693'
									,array( 'Women' )
									,'product_cats'
								)
						)
						,'Fashion Modern - 5'	=> array(
								array(
									'901'
									,array( 'New Arrivals' )
									,'categories'
									,'category'
								)
						)
						,'Fashion Market - 1'	=> array(
								array(
									'693'
									,array( 'Women' )
									,'product_cats'
								)
								,array(
									'779,761,855,694,766,769,840,697,841,856,839,692'
									,array( 'Sneakers', 'Shorts', 'Hats', 'Hoddies', 'T-Shirts', 'Lingerie', 'Handbags', 'Dresses', 'Cosmetics', 'Socks', 'Glasses', 'Jeans' )
									,'ids'
								)
								,array(
									'697'
									,array( 'Dresses' )
									,'product_cats'
								)
								,array(
									'715'
									,array( 'Sport' )
									,'categories'
									,'category'
								)
						)
						,'Fashion Market - 2'	=> array(
								array(
									'779,692,855,688,773,762,837,856,839,690'
									,array( 'Sneakers', 'Jeans', 'Hats', 'Pullovers', 'T-Shirts', 'Suits', 'Watches', 'Socks', 'Glasses', 'Jackets' )
									,'ids'
								)
								,array(
									'687'
									,array( 'Men' )
									,'product_cats'
								)
								,array(
									'761'
									,array( 'Shorts' )
									,'product_cats'
								)
								,array(
									'766'
									,array( 'T-Shirts' )
									,'product_cats'
								)
								,array(
									'715'
									,array( 'Sport' )
									,'categories'
									,'category'
								)
						)
					);
				break;
				
				case 'fashion-beige':
					$pages = array(
						'Fashion Beige - 1'	=> array(
								array(
									'774,840,839,837,766,692,697,690'
									,array( 'Shoes', 'Handbags', 'Glasses', 'Watches', 'T-Shirts', 'Jeans', 'Dresses', 'Jackets' )
									,'ids'
								)
								,array(
									'754'
									,array( 'Accessories' )
									,'product_cats'
								)
						)
						,'Fashion Beige - 2 - Men'	=> array(
								array(
									'687'
									,array( 'Men' )
									,'parent'
								)
								,array(
									'687'
									,array( 'Men' )
									,'product_cats'
								)
						)
						,'Fashion Beige - 2 - Shoes'	=> array(
								array(
									'774'
									,array( 'Shoes' )
									,'product_cats'
								)
						)
						,'Fashion Beige - 2 - Women'	=> array(
								array(
									'693'
									,array( 'Women' )
									,'parent'
								)
								,array(
									'693'
									,array( 'Women' )
									,'product_cats'
								)
								,array(
									'754'
									,array( 'Accessories' )
									,'product_cats'
								)
								,array(
									'841'
									,array( 'Cosmetics' )
									,'product_cats'
								)
						)
					);
				break;
				
				case 'cosmetics':
					$pages = array(
						'Cosmetics - 2'	=> array(
								array(
									'908,909,910,911,912,907,913,914'
									,array( 'Lips', 'Eyes', 'Palettes', 'Sun Protection', 'Creams', 'Accessories', 'Face-maska', 'Lotions' )
									,'ids'
								)
						)
					);
				break;
				
				case 'accessories':
					$pages = array(
						'Accessories - 1'	=> array(
								array(
									'902,903,904,905,906,907,908'
									,array( 'Chargers', 'Power banks', 'Cables', 'Wireless', 'Hubs', 'Headphones', 'Speakers' )
									,'ids'
								)
						)
						,'Accessories - 2'	=> array(
								array(
									'902,903,904,905,906,907,908'
									,array( 'Chargers', 'Power banks', 'Cables', 'Wireless', 'Hubs', 'Headphones', 'Speakers' )
									,'ids'
								)
						)
					);
				break;
				
				case 'sport':
					$pages = array(
						'Fashion Sport - 1'	=> array(
								array(
									'754,862,861,906,839,840,838,855,836,856'
									,array( 'Football', 'Basketball', 'Volleyball', 'Rugby', 'Swimming', 'Ice-skating', 'Golf', 'Skateboard', 'Snowboard', 'Hiking' )
									,'ids'
								)
								,array(
									'901'
									,array( 'New Arrivals' )
									,'categories'
									,'category'
								)
						)
						,'Fashion Sport - 3'	=> array(
								array(
									'754,862,861,692,839,906,838,903,836,904,902,905'
									,array( 'Football', 'Basketball', 'Volleyball', 'Tennis', 'Swimming', 'Rugby', 'Golf', 'Surfing', 'Snowboard', 'Badminton', 'Boxing', 'Running' )
									,'ids'
								)
								,array(
									'901'
									,array( 'New Arrivals' )
									,'categories'
									,'category'
								)
						)
					);
				break;
				
				case 'cases':
					$pages = array(
						'Case - 2'	=> array(
								array(
									'957,958,959,960,961,962,963'
									,array( 'Phones', 'Tablets', 'Watches', 'Laptops', 'Headphones', 'Cables', 'Computer' )
									,'ids'
								)
								,array(
									'914'
									,array( 'Apple' )
									,'parent'
								)
								,array(
									'915'
									,array( 'Samsung' )
									,'parent'
								)
						)
					);
				break;
				
				case 'drone':
					$pages = array(
						'Drone - 1'	=> array(
								array(
									'905'
									,array( 'Sports' )
									,'parent'
								)
						)
						,'Drone - 2'	=> array(
								array(
									'929,930,931,932,909,933'
									,array( 'Wings', 'Antenne', 'Batteries', 'Covers', 'Memory', 'Lens' )
									,'ids'
								)
						)
						,'Drone - 3'	=> array(
								array(
									'906'
									,array( 'Drones' )
									,'product_cats'
								)
								,array(
									'907'
									,array( 'Cameras' )
									,'product_cats'
								)
						)
					);
				break;
			}
			
			$loaded_categories = array();
			
			foreach( $pages as $page_title => $cat_ids_names ){
				$page = $this->get_post_by_title( $page_title );
				if( is_object( $page ) ){
					foreach( $cat_ids_names as $cat_id_name ){
						$key = isset($cat_id_name[2]) ? $cat_id_name[2] : 'ids';
						$taxonomy = isset($cat_id_name[3]) ? $cat_id_name[3] : 'product_cat';
						
						$old_ids = explode(',', $cat_id_name[0]);
						
						$new_ids = array();
						foreach( $cat_id_name[1] as $cat_name ){
							$loaded_id = array_search($cat_name, $loaded_categories);
							if( $loaded_id ){
								$new_ids[] = $loaded_id;
							}
							else{
								$cat = get_term_by('name', $cat_name, $taxonomy);
								if( isset($cat->term_id) ){
									$new_ids[] = $cat->term_id;
									$loaded_categories[$cat->term_id] = $cat_name;
								}
							}
						}
						
						if( $key == 'parent' || $key == 'parent_cat' ){ /* not multi */
							$old_string = '"' . $key . '":"' . implode('', $old_ids) . '"';
							$new_string = '"' . $key . '":"' . implode('', $new_ids) . '"';
						}
						else{
							$old_string = '"' . $key . '":["' . implode('","', $old_ids) . '"]';
							$new_string = '"' . $key . '":["' . implode('","', $new_ids) . '"]';
						}
						
						$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . $old_string . "', '" . $new_string . "') where `meta_key` = '_elementor_data' and post_id=" . $page->ID . ";");
					}
				}
			}
			
			/* Specific Products */
			if( $this->selected_import_data['folder_name'] == 'fashion-beige' ){
				$pages = array(
					'Fashion Beige - 2 - Men'	=> array(
							array(
								'20392,20156,20221,20217,20260'
								,array( 'Dakine women\'s tiffany', 'Asso cat eye sunglasses', 'GUESS men\'s wallet', 'Mini shoulder bag', 'Training socks (3 Pair)' )
								,'ids'
								,'product'
							)
					)
				);
				
				foreach( $pages as $page_title => $post_ids_names ){
					$page = $this->get_post_by_title( $page_title );
					if( is_object( $page ) ){
						foreach( $post_ids_names as $post_id_name ){
							$key = isset($post_id_name[2]) ? $post_id_name[2] : 'ids';
							$post_type = isset($post_id_name[3]) ? $post_id_name[3] : 'post';
							
							$old_ids = explode(',', $post_id_name[0]);
							
							$new_ids = array();
							foreach( $post_id_name[1] as $post_title ){
								$post = $this->get_post_by_title( $post_title, $post_type );
								if( isset($post->ID) ){
									$new_ids[] = $post->ID;
								}
							}
							
							$old_string = '"' . $key . '":["' . implode('","', $old_ids) . '"]';
							$new_string = '"' . $key . '":["' . implode('","', $new_ids) . '"]';
							
							$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . $old_string . "', '" . $new_string . "') where `meta_key` = '_elementor_data' and post_id=" . $page->ID . ";");
						}
					}
				}
			}
		}
		
		/* Update Mega Menu Content */
		function update_mega_menu_content(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			
			$mega_menus = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market': case 'fashion-beige':
					$mega_menus = array(
							'Menu Shop'	=> array(
									array(
										'774,840,839,838,841,837,836,690,779'
										,array( 'Shoes', 'Handbags', 'Glasses', 'Jewelry', 'Cosmetics', 'Watches', 'Perfumes', 'Jackets', 'Sneakers' )
										,'ids'
									)
									,array(
										'687'
										,array( 'Men' )
										,'parent'
									)
									,array(
										'693'
										,array( 'Women' )
										,'parent'
									)
									,array(
										'754'
										,array( 'Accessories' )
										,'parent'
									)
							)
							,'Menu Shoes'	=> array(
									array(
										'774'
										,array( 'Shoes' )
										,'parent'
									)
							)
							,'Menu Accessories'	=> array(
									array(
										'754'
										,array( 'Accessories' )
										,'parent'
									)
							)
							,'Menu Men'	=> array(
									array(
										'687'
										,array( 'Men' )
										,'parent'
									)
							)
							,'Menu 1 - Fashion Market'	=> array(
									array(
										'687'
										,array( 'Men' )
										,'parent'
									)
									,array(
										'754'
										,array( 'Accessories' )
										,'parent'
									)
							)
							,'Menu 4 – Fashion Market'	=> array(
									array(
										'766'
										,array( 'T-Shirts' )
										,'product_cats'
									)
							)
							,'Menu 4 - Fashion Market - Women'	=> array(
									array(
										'693'
										,array( 'Women' )
										,'product_cats'
									)
							)
							,'Menu 6 - Fashion Market'	=> array(
									array(
										'687'
										,array( 'Men' )
										,'parent'
									)
									,array(
										'754'
										,array( 'Accessories' )
										,'parent'
									)
							)
							,'Menu Women'	=> array(
									array(
										'693'
										,array( 'Women' )
										,'parent'
									)
									,array(
										'89'
										,array( 'Brand Dark' )
										,'categories'
										,'ts_logo_cat'
									)
							)
							,'Menu 7 - Fashion Begie'	=> array(
									array(
										'687'
										,array( 'Men' )
										,'parent'
									)
									,array(
										'754'
										,array( 'Accessories' )
										,'parent'
									)
							)
					);
				break;
				
				case 'cosmetics':
					$mega_menus = array(
							'Menu 3'	=> array(
									array(
										'903'
										,array( 'Perfumers' )
										,'product_cats'
									)
							)
							,'Menu 4'	=> array(
									array(
										'908,909,910,911,912,907,913,914'
										,array( 'Lips', 'Eyes', 'Palettes', 'Sun Protection', 'Creams', 'Accessories', 'Face-maska', 'Lotions' )
										,'ids'
									)
							)
					);
				break;
				
				case 'accessories':
					$mega_menus = array(
							'Menu Wireless'	=> array(
									array(
										'902,903,904,905,906,907,908'
										,array( 'Chargers', 'Power banks', 'Cables', 'Wireless', 'Hubs', 'Headphones', 'Speakers' )
										,'ids'
									)
							)
							,'Menu Chargers'	=> array(
									array(
										'902'
										,array( 'Chargers' )
										,'product_cats'
									)
							)
					);
				break;
				
				case 'sport':
					$mega_menus = array(
							'Menu Shop'	=> array(
									array(
										'754,862,861,906,839,840,838,855,836,856'
										,array( 'Football', 'Basketball', 'Volleyball', 'Rugby', 'Swimming', 'Ice-skating', 'Golf', 'Skateboard', 'Snowboard', 'Hiking' )
										,'ids'
									)
							)
							,'Menu Accessories'	=> array(
									array(
										'754,862,861,692,839,906,838,903,836,904,902,905'
										,array( 'Football', 'Basketball', 'Volleyball', 'Tennis', 'Swimming', 'Rugby', 'Golf', 'Surfing', 'Snowboard', 'Badminton', 'Boxing', 'Running' )
										,'ids'
									)
							)
					);
				break;
				
				case 'cases':
					$mega_menus = array(
							'Menu Device'	=> array(
									array(
										'957,958,959,960,961,962,963'
										,array( 'Phones', 'Tablets', 'Watches', 'Laptops', 'Headphones', 'Cables', 'Computer' )
										,'ids'
									)
									,array(
										'914'
										,array( 'Apple' )
										,'parent'
									)
									,array(
										'915'
										,array( 'Samsung' )
										,'parent'
									)
							)
					);
				break;
			}
			
			$loaded_categories = array();
			
			foreach( $mega_menus as $title => $cat_ids_names ){
				$mega_menu_post = $this->get_post_by_title( $title, 'ts_mega_menu' );
				if( is_object( $mega_menu_post ) ){
					foreach( $cat_ids_names as $cat_id_name ){
						$key = isset($cat_id_name[2]) ? $cat_id_name[2] : 'ids';
						$taxonomy = isset($cat_id_name[3]) ? $cat_id_name[3] : 'product_cat';
						
						$old_ids = explode(',', $cat_id_name[0]);
						
						$new_ids = array();
						foreach( $cat_id_name[1] as $cat_name ){
							$loaded_id = array_search($cat_name, $loaded_categories);
							if( $loaded_id ){
								$new_ids[] = $loaded_id;
							}
							else{
								$cat = get_term_by('name', $cat_name, $taxonomy);
								if( isset($cat->term_id) ){
									$new_ids[] = $cat->term_id;
									$loaded_categories[$cat->term_id] = $cat_name;
								}
							}
						}
						
						if( $key == 'parent' || $key == 'parent_cat' ){ /* not multi */
							$old_string = '"' . $key . '":"' . implode('', $old_ids) . '"';
							$new_string = '"' . $key . '":"' . implode('', $new_ids) . '"';
						}
						else{
							$old_string = '"' . $key . '":["' . implode('","', $old_ids) . '"]';
							$new_string = '"' . $key . '":["' . implode('","', $new_ids) . '"]';
						}
						
						$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . $old_string . "', '" . $new_string . "') where `meta_key` = '_elementor_data' and post_id=" . $mega_menu_post->ID . ";");
					}
				}
			}
		}
		
		/* Update Footer Content */
		function update_footer_content(){
			global $wpdb;
			$wp_prefix = $wpdb->prefix;
			
			$footers = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market':
					$footers = array(
							'Footer Market - 1'	=> array(
									array(
										'693'
										,array( 'Women' )
										,'parent'
									)
							)
							,'Footer Market - 2'	=> array(
									array(
										'687'
										,array( 'Men' )
										,'parent'
									)
							)
					);
				break;
			}
			
			$loaded_categories = array();
			
			foreach( $footers as $title => $cat_ids_names ){
				$footer_post = $this->get_post_by_title( $title, 'ts_footer_block' );
				if( is_object( $footer_post ) ){
					foreach( $cat_ids_names as $cat_id_name ){
						$key = isset($cat_id_name[2]) ? $cat_id_name[2] : 'ids';
						
						$old_ids = explode(',', $cat_id_name[0]);
						
						$new_ids = array();
						foreach( $cat_id_name[1] as $cat_name ){
							$loaded_id = array_search($cat_name, $loaded_categories);
							if( $loaded_id ){
								$new_ids[] = $loaded_id;
							}
							else{
								$cat = get_term_by('name', $cat_name, 'product_cat');
								if( isset($cat->term_id) ){
									$new_ids[] = $cat->term_id;
									$loaded_categories[$cat->term_id] = $cat_name;
								}
							}
						}
						
						if( $key == 'parent' || $key == 'parent_cat' ){ /* not multi */
							$old_string = '"' . $key . '":"' . implode('', $old_ids) . '"';
							$new_string = '"' . $key . '":"' . implode('', $new_ids) . '"';
						}
						else{
							$old_string = '"' . $key . '":["' . implode('","', $old_ids) . '"]';
							$new_string = '"' . $key . '":["' . implode('","', $new_ids) . '"]';
						}
						
						$wpdb->query("update `{$wp_prefix}postmeta` set `meta_value` = replace(`meta_value`, '" . $old_string . "', '" . $new_string . "') where `meta_key` = '_elementor_data' and post_id=" . $footer_post->ID . ";");
					}
				}
			}
		}
		
		/* Update Theme Options */
		function update_theme_options(){
			$option_name = 'loobek_theme_options';
			$theme_options = get_option($option_name);
			if( !is_array($theme_options) ){
				return;
			}
			
			/* Menu */
			$menus = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market':
					$menus = array(
						array(
							'ts_second_menu_middle_header'
							,'Second Menu - Middle Header'
						)
						,array(
							'ts_second_menu_top_header'
							,'Second Menu - Top Header'
						)
					);
				break;
				
				case 'fashion-beige':
					$menus = array(
						array(
							'ts_second_menu_middle_header'
							,'Second Menu - Middle Header'
						)
					);
				break;
				
				case 'cosmetics':
					$menus = array(
						array(
							'ts_second_menu_top_header'
							,'Second Menu - Top Header'
						)
					);
				break;
				
				case 'accessories':
					$menus = array(
						array(
							'ts_second_menu_top_header'
							,'Second Menu - Top Header'
						)
					);
				break;
				
				case 'sport':
					$menus = array(
						array(
							'ts_second_menu_top_header'
							,'Second Menu - Top Header'
						)
					);
				break;
				
				case 'drone':
					$menus = array(
						array(
							'ts_second_menu_top_header'
							,'Second Menu - Top Header'
						)
					);
				break;
			}
			
			foreach( $menus as $menu ){
				$key = $menu[0];
				$menu_name = $menu[1];
				
				$menu_obj = get_term_by( 'name', $menu_name, 'nav_menu' );
				if( isset( $menu_obj->term_id ) ){
					$theme_options[$key] = $menu_obj->term_id;
				}
			}
			
			/* Select Post */
			$posts = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Modern - 3'
							,'ts_footer_block'
						)
						,array(
							'ts_shop_bottom_description'
							,'Bottom - Mordern Shop'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_bottom_summary_content'
							,'Feature - Product Detail in Summary'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_custom_tab_content'
							,'Size & Shape'
							,'ts_custom_block'
						)
					);
				break;
				
				case 'fashion-beige':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Modern - 1'
							,'ts_footer_block'
						)
						,array(
							'ts_prod_bottom_summary_content'
							,'Feature - Product Detail in Summary'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_custom_tab_content'
							,'Size & Shape'
							,'ts_custom_block'
						)
					);
				break;
				
				case 'cosmetics':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Cosmetics - 1'
							,'ts_footer_block'
						)
						,array(
							'ts_shop_bottom_description'
							,'Bottom - Shop'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_bottom_summary_content'
							,'Feature - Product Detail in Summary'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_custom_tab_content'
							,'Ingredients'
							,'ts_custom_block'
						)
					);
				break;
				
				case 'accessories':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Accessories - 1'
							,'ts_footer_block'
						)
						,array(
							'ts_shop_bottom_description'
							,'Bottom - Shop'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_custom_tab_content'
							,'Specification'
							,'ts_custom_block'
						)
					);
				break;
				
				case 'sport':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Sport - 1'
							,'ts_footer_block'
						)
						,array(
							'ts_prod_custom_tab_content'
							,'Size & Shape'
							,'ts_custom_block'
						)
					);
				break;
				
				case 'cases':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Case - 1'
							,'ts_footer_block'
						)
						,array(
							'ts_shop_description'
							,'Top - Case Categories'
							,'ts_custom_block'
						)
						,array(
							'ts_shop_bottom_description'
							,'Bottom -  Case Categories'
							,'ts_custom_block'
						)
						,array(
							'ts_prod_custom_tab_content'
							,'Information'
							,'ts_custom_block'
						)
					);
				break;
				
				case 'drone':
					$posts = array(
						array(
							'ts_footer_block'
							,'Footer Drone - 1'
							,'ts_footer_block'
						)
						,array(
							'ts_prod_custom_content'
							,'Custom Content - Bottom - Product Detail'
							,'ts_custom_block'
						)
					);
				break;
			}
			
			foreach( $posts as $post ){
				$key = $post[0];
				$post_title = $post[1];
				$post_type = $post[2];
				
				$p = $this->get_post_by_title( $post_title, $post_type );
				if( isset( $p->ID ) ){
					$theme_options[$key] = $p->ID;
				}
			}
			
			update_option($option_name, $theme_options);
		}
		
		/* Update Page Options */
		function update_page_options(){
			$menus = array();
			$footers = array();
			switch( $this->selected_import_data['folder_name'] ){
				case 'fashion-modern-market':
					$menus = array(
						'Fashion Modern – 2' 	=> 'Main Menu 2'
						,'Fashion Modern - 4' 	=> 'Main Menu 2'
						,'Fashion Modern - 5' 	=> 'Main Menu 3'
						,'Fashion Market - 1' 	=> 'Menu Women'
						,'Fashion Market - 2' 	=> 'Menu Men'
					);
					
					$footers = array(
						'Fashion Modern - 1' 	=> 'Footer Modern - 1'
						,'Fashion Modern – 2' 	=> 'Footer Modern - 2'
						,'Fashion Modern - 4' 	=> 'Footer Modern - 4'
						,'Fashion Modern - 5' 	=> 'Footer Modern - 5'
						,'Fashion Market - 1' 	=> 'Footer Market - 1'
						,'Fashion Market - 2' 	=> 'Footer Market - 2'
					);
				break;
				
				case 'fashion-beige':
					$menus = array(
						'Fashion Beige - 2 - Men' 		=> 'Menu Men'
						,'Fashion Beige - 2 - Shoes' 	=> 'Menu Shoes'
						,'Fashion Beige - 2 - Women' 	=> 'Menu Women'
					);
				break;
				
				case 'cosmetics':
					$footers = array(
						'Cosmetics - 2'	=> 'Footer Cosmetics - 2'
					);
				break;
				
				case 'accessories':
					$footers = array(
						'Accessories - 2'	=> 'Footer Accessories - 2'
					);
				break;
				
				case 'sport':
					$footers = array(
						'Fashion Sport - 2'		=> 'Footer Sport - 2'
						,'Fashion Sport - 3'	=> 'Footer Sport - 3'
					);
				break;
				
				case 'cases':
					$menus = array(
						'Case - 2' => 'Menu Case 02'
					);
					
					$footers = array(
						'Case - 2'	=> 'Footer Case - 2'
					);
				break;
				
				case 'drone':
					$menus = array(
						'Drone - 2' 	=> 'Menu Drone 02'
						,'Drone - 3' 	=> 'Menu Drone'
					);
					
					$footers = array(
						'Drone - 2'		=> 'Footer Drone - 2'
						,'Drone - 3' 	=> 'Footer Drone - 3'
					);
				break;
			}
			
			foreach( $menus as $page_title => $menu_name ){
				$page = $this->get_post_by_title( $page_title );
				if( is_object($page) ){
					$menu = get_term_by( 'name', $menu_name, 'nav_menu' );
					if( isset( $menu->term_id ) ){
						update_post_meta( $page->ID, 'ts_menu_id', $menu->term_id );
					}
				}				
			}
			
			foreach( $footers as $page_title => $footer_title ){
				$page = $this->get_post_by_title( $page_title );
				if( is_object($page) ){
					$footer = $this->get_post_by_title( $footer_title, 'ts_footer_block' );
					if( is_object($footer) ){
						update_post_meta( $page->ID, 'ts_footer_block', $footer->ID );
					}
				}
			}
		}
		
		/* Delete transient */
		function delete_transients(){
			delete_transient('ts_mega_menu_custom_css');
			delete_transient('ts_product_deals_ids');
			delete_transient('wc_products_onsale');
		}
		
		/* Update WooCommerce Loolup Table */
		function update_woocommerce_lookup_table(){
			if( function_exists('wc_update_product_lookup_tables_is_running') && function_exists('wc_update_product_lookup_tables') ){
				if( !wc_update_product_lookup_tables_is_running() ){
					if( !defined('WP_CLI') ){
						define('WP_CLI', true);
					}
					wc_update_product_lookup_tables();
				}
			}
		}
		
		/* Update Menu Term Count - Keep this function until One Click Demo Import fixed */
		function update_menu_term_count(){
			$args = array(
						'taxonomy'		=> 'nav_menu'
						,'hide_empty'	=> 0
						,'fields'		=> 'ids'
					);
			$menus = get_terms( $args );
			if( is_array($menus) ){
				wp_update_term_count_now( $menus, 'nav_menu' );
			}
		}
	}
	new TS_Importer();
}
?>
<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Products extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-products';
    }
	
	public function get_title(){
        return esc_html__( 'TS Products', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-products';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 400 ) );
		
		$this->add_control(
            'title'
            ,array(
                'label' 		=> esc_html__( 'Title', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'product_type'
            ,array(
                'label' 		=> esc_html__( 'Product Type', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'recent'
				,'options'		=> array(
					'recent' 			=> esc_html__('Recent', 'themesky')
					,'sale' 			=> esc_html__('Sale', 'themesky')
					,'featured' 		=> esc_html__('Featured', 'themesky')
					,'best_selling' 	=> esc_html__('Best Selling', 'themesky')
					,'top_rated' 		=> esc_html__('Top Rated', 'themesky')
					,'mixed_order' 		=> esc_html__('Mixed Order', 'themesky')
					,'recently_viewed' 	=> esc_html__('Recently Viewed', 'themesky')
				)		
                ,'description' 	=> esc_html__( 'Select type of product', 'themesky' )
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'viewed_by_all_users'
            ,array(
                'label' 		=> esc_html__( 'Viewed by All Users', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Get products which were viewed by all users or only the current user', 'themesky' )
				,'condition'	=> array( 
					'product_type' => 'recently_viewed' 
				)
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 4
				,'min'      	=> 1
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 4
				,'min'      	=> 1
				,'description' 	=> esc_html__( 'Number of Products', 'themesky' )
            )
        );
		
		$this->add_control(
            'product_cats'
            ,array(
                'label' 		=> esc_html__( 'Product Categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_cat'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific Products', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'post'
					,'name'		=> 'product'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
				,'condition'	=> array( 
					'product_type!' => 'recently_viewed' 
				)
            )
        );
		
		$this->add_view_more_button_controls();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_item'
            ,array(
                'label' 	=> esc_html__( 'Item', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_product_meta_controls();
		
		$this->add_product_color_swatch_controls();
		
		$this->add_product_gallery_controls();
		
		$this->add_control(
            'product_style'
            ,array(
                'label' 		=> esc_html__( 'Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'default'
				,'options'		=> array(
					'default'				=> esc_html__( 'Default', 'themesky' )
					,'has-border-bottom'	=> esc_html__( 'Has Border Bottom', 'themesky' )
					,'has-background'		=> esc_html__( 'Has Background', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_style'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_control(
            'heading_title'
            ,array(
                'label'     => esc_html__( 'Heading Title', 'themesky' )
                ,'type' 	=> Controls_Manager::HEADING		
            )
        );
		
		$this->add_control(
            'title_style'
            ,array(
                'label' 		=> esc_html__( 'Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'title-default'
				,'options'		=> array(
					'title-default'			=> esc_html__( 'Default', 'themesky' )
					,'title-center'			=> esc_html__( 'Center', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_title_and_style_controls();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_slider'
            ,array(
                'label' 	=> esc_html__( 'Slider', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'is_slider'
            ,array(
                'label' 		=> esc_html__( 'Enable Slider', 'themesky' )
                ,'type'			=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'only_slider_mobile'
            ,array(
                'label' 		=> esc_html__( 'Only Enable Slider on Device', 'themesky' )
                ,'type'			=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'				
                ,'description' 	=> esc_html__( 'Show Grid on desktop and only enable Slider on device', 'themesky' )
            )
        );
		
		$this->add_control(
            'rows'
            ,array(
                'label' 		=> esc_html__( 'Rows', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '1'
				,'options'		=> array(
						'1'		=> '1'
						,'2'	=> '2'
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_product_slider_controls_full();
		
		$this->add_control(
            'disable_slider_responsive'
            ,array(
                'label' 		=> esc_html__( 'Disable Slider Responsive', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'You should only enable this option when Columns is 1 or 2', 'themesky' )
            )
        );

		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'					=> 0
			,'title'					=> ''
			,'description'				=> ''
			,'title_style'				=> 'title-default'
			,'product_type'				=> 'recent'
			,'viewed_by_all_users'		=> 1
			,'columns' 					=> 4
			,'limit' 					=> 4
			,'product_cats'				=> array()
			,'ids'						=> array()
			,'show_image' 				=> 1
			,'show_title' 				=> 1
			,'show_sku' 				=> 0
			,'show_price' 				=> 1
			,'show_short_desc'  		=> 0
			,'show_rating' 				=> 0
			,'show_label' 				=> 1	
			,'show_categories'			=> 0	
			,'show_add_to_cart' 		=> 1
			,'show_color_swatch'		=> 0
			,'number_color_swatch'		=> 3
			,'show_gallery'				=> 0
			,'number_gallery'			=> -1
			,'gallery_position'			=> 'top'
			,'product_style'			=> 'default'
			,'show_view_more_button' 	=> 0
			,'view_more_style'			=> 'text'
			,'view_more_text'			=> ''
			,'view_more_link'			=> ''
			,'view_more_position'		=> 'top'
			,'is_slider'				=> 0
			,'only_slider_mobile'		=> 0
			,'rows' 					=> 1
			,'show_dots'				=> 0
			,'show_scrollbar'			=> 0
			,'show_nav'					=> 0
			,'auto_play'				=> 0
			,'disable_slider_responsive'=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product' ) ){
			return;
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = false;
		}
		
		$options = array(
				'show_image'			=> $show_image
				,'show_label'			=> $show_label
				,'show_title'			=> $show_title
				,'show_sku'				=> $show_sku
				,'show_price'			=> $show_price
				,'show_short_desc'		=> $show_short_desc
				,'show_categories'		=> $show_categories
				,'show_rating'			=> $show_rating
				,'show_add_to_cart'		=> $show_add_to_cart
				,'show_color_swatch'	=> $show_color_swatch
				,'number_color_swatch'	=> $number_color_swatch
				,'show_gallery'			=> $show_gallery
				,'number_gallery'		=> $number_gallery
				,'gallery_position'		=> $gallery_position
			);
		ts_remove_product_hooks( $options );
		
		$args = array(
			'post_type'				=> 'product'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> $limit
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'meta_query' 			=> WC()->query->get_meta_query()
			,'tax_query'           	=> WC()->query->get_tax_query()
		);
		
		ts_filter_product_by_product_type($args, $product_type);

		if( is_array($product_cats) && count($product_cats) > 0 ){
			$args['tax_query'][] = array(
										'taxonomy' 	=> 'product_cat'
										,'terms' 	=> $product_cats
										,'field' 	=> 'term_id'
									);
		}
		
		if( $product_type == 'recently_viewed' ){
			$ids = ts_get_recently_viewed_products( $viewed_by_all_users );
		}
		
		if( is_array($ids) && count($ids) > 0 ){
			$args['post__in'] = $ids;
			$args['orderby'] = 'post__in';
		}
		
		global $post;
		if( (int)$columns <= 0 ){
			$columns = 5;
		}
		
		$old_woocommerce_loop_columns = wc_get_loop_prop('columns');
		wc_set_loop_prop('columns', $columns);

		$products = new WP_Query( $args );
		
		$classes = array();
		$classes[] = 'ts-product-wrapper ts-shortcode ts-product woocommerce';
		$classes[] = 'columns-' . $columns;
		$classes[] = $product_type;
		$classes[] = $title_style;
		$classes[] = 'product-' . $product_style;
		if( $show_view_more_button ){
			$classes[] = 'view-more-style-' . $view_more_style;
		}
		if( $show_color_swatch ){
			$classes[] = 'show-color-swatch';
		}
		
		if( $is_slider ){
			$classes[] = 'ts-slider';
			$classes[] = 'rows-' . $rows;
			if( $show_scrollbar ){
				$classes[] = 'show-scrollbar';
				$show_dots = 0;
			}
			if( $show_dots ){
				$classes[] = 'show-dots';
			}
			if( $show_nav ){
				$classes[] = 'show-nav';
					if( $rows < 2 ){
						$classes[] = 'middle-thumbnail';
					}
			}
		}
		
		$data_attr = array();
		if( $is_slider ){
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-dots="'.$show_dots.'"';
			$data_attr[] = 'data-scrollbar="'.$show_scrollbar.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-columns="'.$columns.'"';
			$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
		}
		
		if( $products->have_posts() ): 
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo implode(' ', $data_attr) ?>>
		
			<?php if( $title ): ?>
			<header class="shortcode-heading-wrapper">
			
				<?php if( $title ): ?>
				<h2 class="shortcode-title">
					<?php echo esc_html($title); ?>
				</h2>
				<?php endif; ?>
				
				<?php 
				if( $view_more_position == 'top' ){
					$this->view_more_button_html( $view_more_text, $view_more_link, $view_more_style );
				}
				?>
				
			</header>
			<?php endif; ?>
			
			<div class="content-wrapper <?php echo ($is_slider)?'loading':'' ?>">
				<?php
				$count = 0;
				woocommerce_product_loop_start();
				while( $products->have_posts() ){ 
					$products->the_post();	
					if( $is_slider && $rows > 1 && $count % $rows == 0 ){
						echo '<div class="product-group">';
					}
					wc_get_template_part( 'content', 'product' );
					if( $is_slider && $rows > 1 && ($count % $rows == $rows - 1 || $count == $products->post_count - 1) ){
						echo '</div>';
					}
					$count++;
				}

				woocommerce_product_loop_end();
				?>
				
			</div>
			
			<?php
			if( $view_more_position == 'bottom' ){
				$this->view_more_button_html( $view_more_text, $view_more_link, $view_more_style );
			}
			?>
		</div>
		<?php
		endif;
		
		wp_reset_postdata();

		/* restore hooks */
		ts_restore_product_hooks();

		wc_set_loop_prop('columns', $old_woocommerce_loop_columns);
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Products() );
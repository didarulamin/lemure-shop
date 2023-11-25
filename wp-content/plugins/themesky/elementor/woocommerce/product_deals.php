<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Product_Deals extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-product-deals';
    }
	
	public function get_title(){
        return esc_html__( 'TS Product Deals', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-product-upsell';
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
					'recent' 		=> esc_html__('Recent', 'themesky')
					,'featured' 	=> esc_html__('Featured', 'themesky')
					,'best_selling' => esc_html__('Best Selling', 'themesky')
					,'top_rated' 	=> esc_html__('Top Rated', 'themesky')
					,'mixed_order' 	=> esc_html__('Mixed Order', 'themesky')
				)		
                ,'description' 	=> esc_html__( 'Select type of product', 'themesky' )
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
				,'default'  	=> 5
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
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'show_counter'
            ,array(
                'label' 		=> esc_html__( 'Show Counter', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Show counter on each product', 'themesky' )
            )
        );
		
		$this->add_control(
            'show_counter_today'
            ,array(
                'label' 		=> esc_html__( 'Show Counter Today', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Show only one counter at the top', 'themesky' )
				,'condition'	=> array( 
					'show_counter' => '1' 
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
            'heading_title_font'
            ,array(
                'label'     	=> esc_html__( 'Heading Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'title_style'
            ,array(
                'label' 		=> esc_html__( 'Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'title-default'
				,'options'		=> array(
					'title-default'		=> esc_html__( 'Default', 'themesky' )
					,'title-center'		=> esc_html__( 'Center', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_title_and_style_controls();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_border'
            ,array(
                'label' 	=> esc_html__( 'Border', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_responsive_control(
			'content_padding'
			,array(
				'label' 		=> esc_html__( 'Content Padding', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-product-deals-wrapper .content-deals' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'separator'	=> 'after'
			)
		);
		
		$this->add_control(
			'border_style'
			,array(
				'label' 	=> esc_html__( 'Border Type', 'themesky' )
				,'type'		=> Controls_Manager::SELECT
				,'options' 	=> array(
					'' 			=> esc_html__( 'Default', 'elementor' )
					,'none' 	=> esc_html__( 'None', 'elementor' )
					,'solid' 	=> esc_html__( 'Solid', 'Border Control', 'elementor' )
					,'double' 	=> esc_html__( 'Double', 'Border Control', 'elementor' )
					,'dotted' 	=> esc_html__( 'Dotted', 'Border Control', 'elementor' )
					,'dashed' 	=> esc_html__( 'Dashed', 'Border Control', 'elementor' )
					,'groove' 	=> esc_html__( 'Groove', 'Border Control', 'elementor' )
				)
				,'selectors' => array(
					'{{WRAPPER}} .ts-product-deals-wrapper .content-deals' => 'border-style: {{VALUE}};'
				)
			)
		);

		$this->add_responsive_control(
			'border_width'
			,array(
				'label' 		=> esc_html__( 'Width', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', 'em', 'rem', 'vw', 'custom' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-product-deals-wrapper .content-deals' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
				,'condition' 	=> array(
					'border_style!' 	=> array( '', 'none' )
				)
			)
		);

		$this->add_control(
			'border_color'
			,array(
				'label' 		=> esc_html__( 'Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'default' 		=> ''
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-product-deals-wrapper .content-deals' => 'border-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'border_style!' 	=> array( '', 'none' )
				)
			)
		);
		
		$this->add_responsive_control(
			'border_radius'
			,array(
				'label' 		=> esc_html__( 'Border Radius', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-product-deals-wrapper .content-deals' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
			)
		);
		
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
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
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
			,'title_style' 				=> 'title-default'
			,'product_type'				=> 'recent'
			,'columns' 					=> 4
			,'limit' 					=> 5
			,'product_cats'				=> array()
			,'ids'						=> array()
			,'show_counter'				=> 1
			,'show_counter_today'		=> 0
			,'show_image' 				=> 1
			,'show_title' 				=> 1
			,'show_sku' 				=> 0
			,'show_price' 				=> 1
			,'show_short_desc'  		=> 0
			,'show_rating' 				=> 0
			,'show_label' 				=> 1	
			,'show_categories'			=> 0	
			,'show_add_to_cart' 		=> 1
			,'show_view_more_button' 	=> 0
			,'view_more_style'			=> 'text'
			,'view_more_text'			=> ''
			,'view_more_link'			=> ''
			,'view_more_position'		=> 'top'
			,'show_gallery'				=> 0
			,'number_gallery'			=> -1
			,'gallery_position'			=> 'top'
			,'product_style'				=> 'default'
			,'is_slider' 				=> 1
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
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product' ) ){
			return;
		}
		
		$product_ids_on_sale = ts_get_product_deals_ids();
		
		if( $ids ){
			$product_ids_on_sale = array_intersect($product_ids_on_sale, $ids);
		}
		
		if( !$product_ids_on_sale ){
			return;
		}
		
		if( $show_counter_today ){
			$show_counter = 0;
		}
		
		if( $show_counter ){
			add_action('woocommerce_before_shop_loop_item_title', 'ts_template_loop_time_deals', 15);
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = false;
		}
		
		/* Remove hook */
		$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
				,'show_gallery'		=> $show_gallery
				,'number_gallery'	=> $number_gallery
				,'gallery_position'	=> $gallery_position
			);
		ts_remove_product_hooks( $options );

		global $post, $product;
		if( (int)$columns <= 0 ){
			$columns = 5;
		}
		
		$old_woocommerce_loop_columns = wc_get_loop_prop('columns');
		wc_set_loop_prop('columns', $columns);
		
		$args = array(
			'post_type'				=> 'product'
			,'post_status' 			=> 'publish'
			,'posts_per_page' 		=> $limit
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'post__in'				=> $product_ids_on_sale
			,'meta_query' 			=> WC()->query->get_meta_query()
			,'tax_query'           	=> WC()->query->get_tax_query()
		);
		
		ts_filter_product_by_product_type($args, $product_type);
		
		if( $product_cats ){
			$args['tax_query'][] = array(
							'taxonomy' 	=> 'product_cat'
							,'terms' 	=> $product_cats
							,'field' 	=> 'term_id'
						);
		}
		
		$products = new WP_Query($args);
		
		if( $products->have_posts() ): 
			$classes = array();
			$classes[] = 'ts-product-deals-wrapper ts-shortcode ts-product woocommerce';
			$classes[] = 'columns-' . $columns;
			$classes[] = $show_image?'':'no-thumbnail';
			$classes[] = $title_style;
			$classes[] = 'product-' . $product_style;
			if( $show_view_more_button ){
				$classes[] = 'view-more-style-' . $view_more_style;
			}
			if( $show_counter_today ){
				$classes[] = 'show-counter-today';
			}
			if( $show_counter ){
				$classes[] = 'show-counter';
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
					$classes[] = 'show-nav middle-thumbnail';
				}
			}
			
			$classes = array_filter($classes);
			
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-dots="'.$show_dots.'"';
				$data_attr[] = 'data-scrollbar="'.$show_scrollbar.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-columns="'.$columns.'"';
				$data_attr[] = 'data-disable_responsive="'.$disable_slider_responsive.'"';
			}
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>" <?php echo implode(' ', $data_attr); ?>>
			
				<?php if( $title || $show_counter_today ): ?>
				<header class="shortcode-heading-wrapper">
					<?php if( $title ): ?>
					<h2 class="shortcode-title">
						<?php echo esc_html($title); ?>
					</h2>
					<?php endif;
					if( $show_counter_today ){
						ts_daily_time_remain_html();
					}
					
					if( $view_more_position == 'top' ){
						$this->view_more_button_html( $view_more_text, $view_more_link, $view_more_style );
					}
					?>
					
				</header>
				<?php endif; ?>
				
				<div class="content-deals">
					<div class="content-wrapper <?php echo $is_slider?'loading':''; ?>">
						<?php woocommerce_product_loop_start(); ?>				

						<?php 
						$count = 0;
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
						?>			

						<?php woocommerce_product_loop_end(); ?>
					</div>
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
		if( $show_counter ){
			remove_action('woocommerce_before_shop_loop_item_title', 'ts_template_loop_time_deals', 15);
		}

		ts_restore_product_hooks();

		wc_set_loop_prop('columns', $old_woocommerce_loop_columns);
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Product_Deals() );
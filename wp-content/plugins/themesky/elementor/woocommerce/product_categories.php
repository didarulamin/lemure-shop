<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Product_Categories extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-product-categories';
    }
	
	public function get_title(){
        return esc_html__( 'TS Product Categories', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-product-categories';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 140 ) );
		
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
			'layout_wrap'
			,array(
				'label' 	=> esc_html__( 'Layout', 'themesky' )
				,'type' 	=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'layout-nowrap' 	=> array(
						'title' => esc_html__( 'No Wrap', 'themesky' )
						,'icon' => 'eicon-flex eicon-nowrap'
					)
					,'layout-wrap' 	=> array(
						'title' => esc_html__( 'Wrap','themesky' )
						,'icon' => 'eicon-flex eicon-wrap'
					)
				)
				,'description' 	=> esc_html__( 'Items within the container can stay in a single line (No wrap), or break into multiple lines (Wrap).', 'themesky' )
				,'default' 		=> ''
			)
		);
		
		$this->add_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 7
				,'min'      	=> 1
				,'description' 	=> esc_html__( 'Use for layout Wrap and Slider.', 'themesky' )
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 7
				,'min'      	=> 1
				,'description' 	=> esc_html__( 'Use for layout Wrap and Slider.', 'themesky' )
            )
        );
		
		$this->add_control(
            'first_level'
			,array(
                'label' 			=> esc_html__( 'Only display the first level', 'themesky' )
                ,'type' 			=> Controls_Manager::SWITCHER
                ,'default' 			=> '0'
				,'return_value' 	=> '1'			
                ,'description' 		=> ''
            )
        );
		
		$this->add_control(
            'parent'
            ,array(
                'label' 		=> esc_html__( 'Parent', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_cat'
				)
				,'multiple' 	=> false
				,'sortable' 	=> false
				,'label_block' 	=> true
				,'description' 	=> esc_html__( 'Get direct children of this category', 'themesky' )
				,'condition'	=> array( 'first_level' => '0' )
            )
        );
		
		$this->add_control(
            'child_of'
            ,array(
                'label' 		=> esc_html__( 'Child of', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_cat'
				)
				,'multiple' 	=> false
				,'sortable' 	=> false
				,'label_block' 	=> true
				,'description' 	=> esc_html__( 'Get all descendents of this category', 'themesky' )
				,'condition'	=> array( 'first_level' => '0' )
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific Categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_cat'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'hide_empty'
			,array(
                'label' 		=> esc_html__( 'Hide Empty Product Categories', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> ''
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
		
		$this->add_control(
            'item_style'
            ,array(
                'label' 		=> esc_html__( 'Item Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'style-vertical'
				,'options'		=> array(
					'style-vertical'		=> esc_html__( 'Vertical', 'themesky' )
					,'style-horizontal'		=> esc_html__( 'Horizontal', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_icon'
            ,array(
                'label' 		=> esc_html__( 'Thumbnail Icon', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> esc_html__( 'Use Icon instead of Thumbnail', 'themesky' )
            )
        );
		
		$this->add_control(
            'show_title'
            ,array(
                'label' 		=> esc_html__( 'Product Category Title', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_product_count'
            ,array(
                'label' 		=> esc_html__( 'Product Count', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'thumbnail_radius'
            ,array(
                'label'     	=> esc_html__( 'Thumbnail Radius', 'themesky' )
                ,'type'     	=> Controls_Manager::SWITCHER
				,'default'  	=> '0'
				,'return_value' => '1'
				,'separator'	=> 'before'
				,'condition'	=> array( 
					'show_icon!' => '1' 
				)
            )
        );
		
		$this->add_responsive_control(
			'thumbnail_max_width'
			,array(
				'label' 		=> esc_html__( 'Thumbnail Max Width', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px'	=> array(
							'min' 	=> 0
							,'max' 	=> 500
					)
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .product-wrapper >  a' => 'max-width: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_responsive_control(
			'padding'
			,array(
				'label' 		=> esc_html__( 'Padding', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .products .product-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->add_control(
            'bg_item'
            ,array(
                'label'     	=> esc_html__( 'Background Item Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#F2F2F2'
				,'selectors'	=> array(
					'{{WRAPPER}} .products .product-wrapper' => 'background-color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'effect_style'
            ,array(
                'label' 		=> esc_html__( 'Thumbnail Animation', 'themesky' )
                 ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
				,'separator'	=> 'before'
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
                'label'     	=> esc_html__( 'Heading Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
            )
        );
		
		$this->add_control(
            'title_style'
            ,array(
                'label' 		=> esc_html__( 'Title Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'title-default'
				,'options'		=> array(
					'title-default'		=> esc_html__( 'Default', 'themesky' )
					,'title-center'		=> esc_html__( 'Center', 'themesky' )
					,'title-float'		=> esc_html__( 'Float', 'themesky' )
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
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'only_slider_mobile'
            ,array(
                'label' 		=> esc_html__( 'Only Enable Slider on Device', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
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
						,'3'	=> '3'
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_product_slider_controls_full();
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'					=> 0
			,'title'					=> ''
			,'layout_wrap'				=> 'layout-nowrap'
			,'item_style'				=> 'style-vertical'
			,'title_style'				=> 'title-default'
			,'limit' 					=> 7
			,'columns' 					=> 7
			,'first_level' 				=> 0
			,'parent' 					=> ''
			,'child_of' 				=> 0
			,'ids'	 					=> ''
			,'hide_empty'				=> 1
			,'thumbnail_radius'			=> 0
			,'effect_style'				=> 0
			,'show_icon'				=> 0
			,'show_title'				=> 1
			,'show_product_count'		=> 0
			,'show_view_more_button' 	=> 0
			,'view_more_style'			=> 'text'
			,'view_more_text'			=> ''
			,'view_more_link'			=> ''
			,'view_more_position'		=> 'top'
			,'is_slider'				=> 0
			,'only_slider_mobile'		=> 0
			,'rows' 					=> 1
			,'show_nav' 				=> 0
			,'show_dots'				=> 0
			,'show_scrollbar'			=> 0
			,'auto_play' 				=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-category' ) ){
			return;
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = false;
		}
		
		if( is_admin() && !wp_doing_ajax() ){ /* WooCommerce does not include hook below in Elementor editor */
			add_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
		}
		
		if( $first_level ){
			$parent = $child_of = 0;
		}
		
		$parent = is_array($parent) ? implode('', $parent) : $parent;
		$child_of = is_array($child_of) ? implode('', $child_of) : $child_of;

		$args = array(
			'taxonomy'	  => 'product_cat'
			,'orderby'    => 'name'
			,'order'      => 'ASC'
			,'hide_empty' => $hide_empty
			,'pad_counts' => true
			,'parent'     => $parent
			,'child_of'   => $child_of
			,'number'     => $limit
		);
		
		if( $ids ){
			$args['include'] = $ids;
			$args['orderby'] = 'include';
		}
		
		$product_categories = get_terms( $args );
		
		$old_woocommerce_loop_columns = wc_get_loop_prop('columns');
		wc_set_loop_prop('columns', $columns);
		
		wc_set_loop_prop( 'is_shortcode', true );
		
		$total_categories = count($product_categories);
		
		if( $total_categories ):
			$classes = array();
			$classes[] = 'ts-product-category-wrapper ts-product ts-shortcode woocommerce';
			$classes[] = 'columns-' . $columns;
			$classes[] = $title_style;
			$classes[] = $is_slider?'ts-slider':'grid';
			$classes[] = $item_style;
			$classes[] = $layout_wrap;
			$classes[] = 'limit-'. $limit;
			if( $show_view_more_button ){
				$classes[] = 'view-more-style-' . $view_more_style;
			}
			
			if( $is_slider ){
				$classes[] = 'rows-' . $rows;
				if( $show_scrollbar ){
					$classes[] = 'show-scrollbar';
					$show_dots = 0;
				}
				if( $show_dots ){
					$classes[] = 'show-dots';
					$show_nav = 0;
				}
				if( $show_nav ){
					$classes[] = 'show-nav';
					if( $rows < 2 ){
						$classes[] = 'middle-thumbnail';
					}
				}
			}
			
			if( $show_icon ){
				$classes[] = 'show-icon';
			}
			
			if( $effect_style ){
				$classes[] = 'effect-thumbnail';
			}
			
			if( $thumbnail_radius ){
				$classes[] = 'thumbnail-radius';
			}
		
			$data_attr = array();
			if( $is_slider ){
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-dots="'.$show_dots.'"';
				$data_attr[] = 'data-scrollbar="'.$show_scrollbar.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-columns="'.$columns.'"';
			}
		?>
			<div class="<?php echo esc_attr(implode(' ', $classes)) ?>" <?php echo implode(' ', $data_attr); ?>>
				
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
				
				<div class="content-wrapper <?php echo $is_slider?'loading':''; ?>">
					<?php 
					$count = 0;
					woocommerce_product_loop_start();
					foreach ( $product_categories as $category ) {
						if( $is_slider && $rows > 1 && $count % $rows == 0 ){
							echo '<div class="product-group">';
						}
					
						wc_get_template( 'content-product-cat.php', array(
							'category' 					=> $category
							,'show_icon' 				=> $show_icon
							,'show_title' 				=> $show_title
							,'show_product_count' 		=> $show_product_count
						) );
						
						if( $is_slider && $rows > 1 && ($count % $rows == $rows - 1 || $count == $total_categories - 1) ){
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
		
		wc_set_loop_prop('columns', $old_woocommerce_loop_columns);
		
		wc_set_loop_prop( 'is_shortcode', false );
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Product_Categories() );
<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Products_In_Product_Type_Tabs extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-products-in-product-type-tabs';
    }
	
	public function get_title(){
        return esc_html__( 'TS Products In Product Type Tabs', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-product-tabs';
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
		
		$repeater = new Elementor\Repeater();
		
		$repeater->add_control(
			'heading'
			,array(
				'label' 		=> esc_html__( 'Heading', 'themesky' )
				,'type' 		=> Controls_Manager::TEXT
				,'default' 		=> 'Tab Heading'
				,'description' 	=> ''
			)
		);
		
		$repeater->add_control(
			'product_type'
			,array(
				'label' 		=> esc_html__( 'Product Type', 'themesky' )
				,'type' 		=> Controls_Manager::SELECT
				,'default' 		=> 'recent'
				,'options'		=> array(
					'recent' 		=> esc_html__('Recent', 'themesky')
					,'sale' 		=> esc_html__('Sale', 'themesky')
					,'featured' 	=> esc_html__('Featured', 'themesky')
					,'best_selling' => esc_html__('Best Selling', 'themesky')
					,'top_rated' 	=> esc_html__('Top Rated', 'themesky')
					,'mixed_order' 	=> esc_html__('Mixed Order', 'themesky')
				)		
				,'description' 	=> ''
			)
		);
		
		$repeater->add_control(
			'view_more_text'
			,array(
				'label' 		=> esc_html__( 'View More Text', 'themesky' )
				,'type' 		=> Controls_Manager::TEXT
				,'default' 		=> ''
				,'description' 	=> ''
			)
		);
		
		$repeater->add_control(
			'view_more_link'
			,array(
				'label' 		=> esc_html__( 'View More Link', 'themesky' )
				,'type' 		=> Controls_Manager::TEXT
				,'default' 		=> ''
				,'description' 	=> ''
			)
		);
		
		$this->add_control(
			'tabs'
			,array(
				'label' 	=> esc_html__( 'Tabs', 'themesky' )
				,'type' 	=> Controls_Manager::REPEATER
				,'fields' 	=> $repeater->get_controls()
				,'default' 	=> array(
					array(
						'heading' 		=> 'New Arrivals'
						,'product_type' => 'recent'
					)
					,array(
						'heading' 		=> 'Bestsellers'
						,'product_type' => 'best_selling'
					)
					,array(
						'heading' 		=> 'On Sale'
						,'product_type' => 'sale'
					)
				)
				,'title_field' => '{{{ heading }}}'
			)
		);
		
		$this->add_control(
            'active_tab'
            ,array(
                'label' 		=> esc_html__( 'Active Tab', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '1'
				,'options'		=> array(
						'1'		=> '1'
						,'2'	=> '2'
						,'3'	=> '3'
						,'4'	=> '4'
						,'5'	=> '5'
						,'6'	=> '6'
				)			
                ,'description' 	=> ''
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
				,'default'  	=> 6
				,'min'      	=> 1
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
				,'label_block' 	=> true
            )
        );
		
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
            'tab_title_font'
            ,array(
                'label'     	=> esc_html__( 'Tab Title', 'themesky' )
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
					'title-default'			=> esc_html__( 'Default', 'themesky' )
					,'title-center'			=> esc_html__( 'Center', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'heading_typography'
				,'selector'			=> '{{WRAPPER}} .column-tabs ul.tabs li'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '30'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
					,'line_height'		=> array(
						'default' 		=> array(
							'size' 		=> '42'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'	=> array('text_decoration', 'text_transform', 'font_style', 'word_spacing')
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
			'lazy_load'						=> 0
			,'tabs'							=> array()
			,'title_style'					=> 'title-default'
			,'active_tab'					=> 1
			,'columns' 						=> 4
			,'limit' 						=> 6
			,'product_cats'					=> array()
			,'include_children' 			=> 1
			,'show_image' 					=> 1
			,'show_title' 					=> 1
			,'show_sku' 					=> 0
			,'show_price' 					=> 1
			,'show_short_desc'  			=> 0
			,'show_rating' 					=> 0
			,'show_label' 					=> 1
			,'show_categories'				=> 0	
			,'show_add_to_cart' 			=> 1
			,'show_color_swatch' 			=> 0
			,'number_color_swatch' 			=> 3
			,'show_gallery'					=> 0
			,'number_gallery'				=> -1
			,'gallery_position'				=> 'top'
			,'product_style'				=> 'default'
			,'is_slider' 					=> 1
			,'only_slider_mobile'			=> 0
			,'rows' 						=> 1
			,'show_dots'					=> 1
			,'show_scrollbar'				=> 0
			,'show_nav' 					=> 0
			,'auto_play' 					=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( empty($tabs) ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-tabs' ) ){
			return;
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = 0;
		}
		
		if( $active_tab > count($tabs) ){
			$active_tab = 1;
		}
		
		$product_type = $tabs[$active_tab-1]['product_type'];
		
		$product_cats = implode(',', $product_cats);
		
		$atts = compact('columns', 'rows', 'limit', 'product_cats', 'include_children', 'product_type', 'show_gallery', 'number_gallery', 'gallery_position'
						,'show_image', 'show_title', 'show_sku', 'show_price', 'show_short_desc', 'show_rating', 'show_label'
						,'show_categories', 'show_add_to_cart', 'show_color_swatch', 'number_color_swatch', 'is_slider', 'show_nav', 'show_dots', 'auto_play');
		
		$classes = array();
		$classes[] = 'ts-product-in-product-type-tab-wrapper ts-shortcode ts-product';
		$classes[] = $title_style;
		$classes[] = 'product-' . $product_style;
		
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
		}
		
		$classes = array_filter($classes);
		
		$rand_id = 'ts-product-in-product-type-tab-' . mt_rand(0, 1000);
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($rand_id) ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>" <?php echo implode(' ', $data_attr); ?>>
			<div class="column-tabs">		
				<ul class="tabs">
				<?php foreach( $tabs as $i => $tab ){ ?>
					<li class="tab-item <?php echo ($active_tab == $i + 1)?'current':''; ?>" data-id="tab-<?php echo esc_attr($tab['_id']) ?>" data-product_type="<?php echo esc_attr($tab['product_type']) ?>"><?php echo esc_html($tab['heading']) ?></li>
				<?php } ?>
				</ul>
			</div>
			
			<div class="content-wrapper column-products woocommerce columns-<?php echo esc_attr($columns) ?> <?php echo $product_type; ?> <?php echo $is_slider?'loading':''; ?>">
				<?php ts_get_product_content_in_category_tab($atts, $product_cats); ?>
			</div>
			
			<?php
			foreach( $tabs as $i => $tab ){
				if( $tab['view_more_link'] && $tab['view_more_text'] ){
			?>
				<div class="view-more-wrapper tab-<?php echo esc_attr($tab['_id']) ?>" style="<?php echo ( $active_tab == $i + 1 ? '' : 'display: none' ) ?>">
					<a class="button-text" href="<?php echo esc_url($tab['view_more_link']); ?>"><?php echo esc_html($tab['view_more_text']) ?></a>
				</div>
			<?php 
				}
			}
			?>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Products_In_Product_Type_Tabs() );
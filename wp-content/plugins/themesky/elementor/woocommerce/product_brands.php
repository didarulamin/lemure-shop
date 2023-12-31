<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Product_Brands extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-product-brands';
    }
	
	public function get_title(){
        return esc_html__( 'TS Product Brands', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	public function get_icon(){
		return 'eicon-posts-carousel';
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
            'use_logo_setting'
            ,array(
                'label' 		=> esc_html__( 'Use Logo\'s Settings', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'If enabled, you go to Logos > Settings to configure image size and slider responsive', 'themesky' )
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 5
				,'min'      	=> 1
				,'condition'	=> array( 
					'use_logo_setting!' => '1' 
				)
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
            'first_level'
            ,array(
                'label' 		=> esc_html__( 'Only Display the First Level', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'hide_empty'
            ,array(
                'label' 		=> esc_html__( 'Hide Empty Product Brands', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_title'
            ,array(
                'label' 		=> esc_html__( 'Product Brand Title', 'themesky' )
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
            'slider_options'
            ,array(
                'label' 		=> esc_html__( 'Slider Options', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'separator'    => 'before'
            )
        );
		
		$this->add_product_slider_controls_basic();
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'lazy_load'				=> 0
			,'title'				=> ''
			,'title_style'			=> ''
			,'use_logo_setting'		=> 1
			,'limit' 				=> 6
			,'columns' 				=> 5
			,'first_level' 			=> 0
			,'hide_empty'			=> 1
			,'show_title'			=> 1
			,'show_product_count'	=> 0
			,'show_nav' 			=> 0
			,'auto_play' 			=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if ( !class_exists('WooCommerce') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'product-brand' ) ){
			return;
		}

		$args = array(
			'taxonomy'	  => 'ts_product_brand'
			,'orderby'    => 'name'
			,'order'      => 'ASC'
			,'hide_empty' => $hide_empty
			,'pad_counts' => true
			,'number'     => $limit
		);
		
		if( $first_level ){
			$args['parent'] = 0;
		}
		
		$product_brands = get_terms( $args );
		
		if( count($product_brands) > 0 ):
			$classes = array();
			$classes[] = 'ts-product-brand-wrapper ts-product ts-shortcode ts-slider woocommerce rows-1';
			$classes[] = 'columns-' . $columns;
			$classes[] = $title_style;
			$classes[] = $use_logo_setting?'use-logo-setting':'';
			if( $show_nav ){
				$classes[] = 'show-nav middle-thumbnail';
			}
		
			$data_attr = array();
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-columns="'.$columns.'"';
			
			if( $use_logo_setting ){
				$settings_option = get_option('ts_logo_setting', array());
				$data_break_point = isset($settings_option['responsive']['break_point'])?$settings_option['responsive']['break_point']:array();
				$data_item = isset($settings_option['responsive']['item'])?$settings_option['responsive']['item']:array();
				
				$data_attr[] = 'data-break_point="'.htmlentities(json_encode( $data_break_point )).'"';
				$data_attr[] = 'data-item="'.htmlentities(json_encode( $data_item )).'"';
			}
		?>
			<div class="<?php echo esc_attr(implode(' ', $classes)) ?>" <?php echo implode(' ', $data_attr); ?>>
				<?php if( $title ): ?>
					<header class="shortcode-heading-wrapper">
						<h2 class="shortcode-title"><?php echo esc_html($title); ?></h2>
					</header>
				<?php endif; ?>
				
				<div class="content-wrapper loading items">
					<?php 
					foreach( $product_brands as $brand ){
						$brand_link = get_term_link($brand, 'ts_product_brand');
						$thumbnail_id = absint(get_term_meta( $brand->term_id, 'thumbnail_id', true ));
						$image_size = $use_logo_setting?'ts_logo_thumb':'woocommerce_thumbnail';
						?>
						<div class="item">
							<div class="product-wrapper">
								<a href="<?php echo esc_url( $brand_link ) ?>">
								<?php
								if( $thumbnail_id ){
									echo wp_get_attachment_image($thumbnail_id, $image_size);
								}
								else{
									echo wc_placeholder_img();
								}
								?>
								</a>
								<div class="meta-wrapper">
									<?php if( $show_title ): ?>
									<h3 class="heading-title">
										<a href="<?php echo esc_url($brand_link); ?>"><?php echo $brand->name; ?></a>
									</h3>
									<?php endif; ?>
									<?php if( $show_product_count ): ?>
									<div class="count"><?php echo sprintf( _n( '%s Product', '%s Products', $brand->count, 'themesky' ), $brand->count ); ?></div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		<?php
		endif;
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Product_Brands() );
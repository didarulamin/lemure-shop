<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Logos extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-logos';
    }
	
	public function get_title(){
        return esc_html__( 'TS Logos', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-logo';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_lazy_load_controls( array( 'thumb-height' => 50 ) );
		
		$this->add_control(
            'layout'
            ,array(
                'label' 		=> esc_html__( 'Layout', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'slider'
				,'options'		=> array(
					'slider'	=> esc_html__( 'Slider', 'themesky' )
					,'grid'		=> esc_html__( 'Grid', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'only_slider_mobile'
            ,array(
                'label' 		=> esc_html__( 'Only Enalble Slider on Device', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
                ,'description' 	=> esc_html__( 'Show Grid on desktop and only enable Slider on device', 'themesky' )
				,'condition'	=> array( 
					'layout' 	=> 'slider' 
				)
            )
        );
		
		$this->add_control(
            'limit'
            ,array(
                'label'     => esc_html__( 'Limit', 'themesky' )
                ,'type'     => Controls_Manager::NUMBER
				,'default'  => 8
				,'min'      => 1
            )
        );
		
		$this->add_control(
            'columns'
            ,array(
                'label' 		=> esc_html__( 'Columns', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '6'
				,'options'		=> array(
						'1'		=> '1'
						,'2'	=> '2'
						,'3'	=> '3'
						,'4'	=> '4'
						,'5'	=> '5'
						,'6'	=> '6'
				)
                ,'condition'	=> array( 
					'layout' 	=> 'grid' 
				)
            )
        );
		
		$this->add_control(
            'categories'
            ,array(
                'label' 		=> esc_html__( 'Categories', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'ts_logo_cat'
				)
				,'multiple' 	=> true
				,'sortable' 	=> false
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'active_link'
            ,array(
                'label' 		=> esc_html__( 'Activate Link', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
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
			,'layout'				=> 'slider'
			,'only_slider_mobile'	=> 0
			,'categories' 			=> array()
			,'limit' 				=> 8
			,'columns' 				=> 6
			,'active_link'			=> 1
			,'show_nav' 			=> 0
			,'auto_play' 			=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('TS_Logos') ){
			return;
		}
		
		if( $this->lazy_load_placeholder( $settings, 'logo' ) ){
			return;
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$layout = 'grid';
		}
		
		$args = array(
			'post_type'				=> 'ts_logo'
			,'post_status'			=> 'publish'
			,'posts_per_page' 		=> $limit
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
		);
		
		if( is_array($categories) && count($categories) > 0 ){
			$args['tax_query'] = array(
									array(
										'taxonomy' => 'ts_logo_cat'
										,'terms' => $categories
										,'field' => 'term_id'
										,'include_children' => false
									)
								);
		}
		
		$logos = new WP_Query($args);
		
		global $post;
		
		if( $logos->have_posts() ){
			$count_posts = $logos->post_count;
			
			$classes = array();
			$classes[] = 'ts-logo-slider-wrapper use-logo-setting ts-shortcode rows-1';
			
			if( $layout == 'slider' ){
				$classes[] = 'ts-slider';
				if( $count_posts > 1 ){
					$classes[] = 'loading';
				}
				if( $show_nav ){
					$classes[] = 'show-nav middle-thumbnail';
				}
			}
			$classes[] = 'columns-' . $columns;
			
			$data_attr = array();
			if( $layout == 'slider' ){
				$settings_option = get_option('ts_logo_setting', array());
				$data_break_point = isset($settings_option['responsive']['break_point'])?$settings_option['responsive']['break_point']:array();
				$data_item = isset($settings_option['responsive']['item'])?$settings_option['responsive']['item']:array();
				
				$data_attr[] = 'data-nav="'.$show_nav.'"';
				$data_attr[] = 'data-autoplay="'.$auto_play.'"';
				$data_attr[] = 'data-break_point="'.htmlentities(json_encode( $data_break_point )).'"';
				$data_attr[] = 'data-item="'.htmlentities(json_encode( $data_item )).'"';
			}
			?>
			<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>" <?php echo implode(' ', $data_attr); ?>>
				
				<div class="content-wrapper">
					<div class="items logos">
					<?php 
					while( $logos->have_posts() ): $logos->the_post(); 
					?>
						<div class="item">
							<?php if( $active_link ):
							$logo_url = get_post_meta($post->ID, 'ts_logo_url', true);
							$logo_target = get_post_meta($post->ID, 'ts_logo_target', true);
							?>
								<a href="<?php echo esc_url($logo_url); ?>" target="<?php echo esc_attr($logo_target); ?>">
							<?php endif; ?>
								<?php 
								if( has_post_thumbnail() ){
									the_post_thumbnail('ts_logo_thumb');
								}
								?>
							<?php if( $active_link ): ?>
								</a>
							<?php endif; ?>
						</div>
					<?php 
					endwhile; 
					?>
					</div>
				</div>
			</div>
		<?php
		}
		wp_reset_postdata();
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Logos() );
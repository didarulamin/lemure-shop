<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Instagram extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-instagram';
    }
	
	public function get_title(){
        return esc_html__( 'TS Instagram', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-instagram-gallery';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 		=> esc_html__( 'General', 'themesky' )
                ,'tab'   		=> Controls_Manager::TAB_CONTENT
            )
        );
		
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
            'style'
            ,array(
                'label' 		=> esc_html__( 'Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'style-default'
				,'options'		=> array(
					'style-default'		=> esc_html__( 'Default', 'themesky' )
					,'style-ziczac'		=> esc_html__( 'Ziczac', 'themesky' )
				)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'access_token'
            ,array(
                'label' 		=> esc_html__( 'Access Token', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'number'
            ,array(
                'label'     	=> esc_html__( 'Number Of Photos', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 9
				,'min'      	=> 1
            )
        );
		
		$this->add_control(
            'column'
            ,array(
                'label'     	=> esc_html__( 'Column', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 5
				,'min'      	=> 1
            )
        );
		
		$this->add_control(
            'show_username'
            ,array(
                'label' 		=> esc_html__( 'Instagram Username', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'margin'
            ,array(
                'label' 		=> esc_html__( 'Margin', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> ''
				,'options'		=> array(
					''				=> esc_html__( '10', 'themesky' )
					,'margin-small'	=> esc_html__( '5', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'target'
            ,array(
                'label' 		=> esc_html__( 'Target', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '_self'
				,'options'		=> array(
					'_self'		=> esc_html__( 'Self', 'themesky' )
					,'_blank'	=> esc_html__( 'New window tab', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'cache_time'
            ,array(
                'label'     	=> esc_html__( 'Cache Time (hours)', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 12
				,'min'      	=> 1
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
		
		$this->add_product_slider_controls_basic();
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title'					=> ''
			,'title_style'			=> 'title-default'
			,'style'				=> 'style-default'
			,'margin'				=> ''
			,'access_token'			=> ''
			,'number'				=> 9
			,'column'				=> 5
			,'show_username'		=> 0
			,'target'				=> '_self'
			,'cache_time'			=> 12
			,'is_slider'			=> 0
			,'only_slider_mobile'	=> 0
			,'show_nav'				=> 0
			,'auto_play'			=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('TS_Instagram_Widget') ){
			return;
		}
		
		if( $only_slider_mobile && !wp_is_mobile() ){
			$is_slider = 0;
		}
		
		$args = array(
			'before_widget' => '<section class="widget-container %s">'
			,'after_widget' => '</section>'
			,'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title heading-title">'
			,'after_title'  => '</h3></div>'
		);
		
		$classes = array('ts-instagram-elementor-widget');
		$classes[] = $style;
		$classes[] = $title_style;
		if( $margin ){
			$classes[] = 'margin-small';
		}
		?>
		<div class="<?php echo implode(' ', $classes); ?>">
			<header class="shortcode-heading-wrapper">
				<?php if( $title ): ?>
				<h2 class="shortcode-title">
					<?php echo esc_html($title); ?>
				</h2>
				<?php endif; ?>
			</header>
		<?php
			$title = ''; /* dont show title of wp widget */
			$instance = compact('title', 'access_token', 'number', 'column', 'show_username', 'target', 'cache_time', 'is_slider', 'show_nav', 'auto_play');
			the_widget('TS_Instagram_Widget', $instance, $args);
		?>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Instagram() );
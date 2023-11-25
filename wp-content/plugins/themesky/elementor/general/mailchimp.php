<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Mailchimp extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-mailchimp';
    }
	
	public function get_title(){
        return esc_html__( 'TS Mailchimp', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-email-field';
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
            'form'
            ,array(
                'label' 		=> esc_html__( 'Form', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> ''
				,'options'		=> $this->get_custom_post_options( 'mc4wp-form' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'layout'
            ,array(
                'label' 		=> esc_html__( 'Layout', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'layout-default'
				,'options'		=> array(
					'layout-default'			=> esc_html__( 'Default', 'themesky' )
					,'layout-simple'			=> esc_html__( 'Simple', 'themesky' )
					,'layout-vertical'			=> esc_html__( 'Vertical', 'themesky' )
				)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'title'
            ,array(
                'label' 		=> esc_html__( 'Title', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'intro_text'
            ,array(
                'label' 		=> esc_html__( 'Intro Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXTAREA
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$this->add_responsive_control(
            'text_align'
			,array(
				'label' 		=> esc_html__( 'Alignment', 'themesky' )
				,'type' 		=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'left' 		=> array(
						'title' 	=> esc_html__( 'Left', 'themesky' )
						,'icon' 	=> 'eicon-text-align-left'
					)
					,'center'	=> array(
						'title' 	=> esc_html__( 'Center', 'themesky' )
						,'icon' 	=> 'eicon-text-align-center'
					)
					,'right' 	=> array(
						'title' 	=> esc_html__( 'Right', 'themesky' )
						,'icon' 	=> 'eicon-text-align-right'
					)
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .mailchimp-subscription' => 'text-align: {{VALUE}};'
				)
				,'default' 		=> ''
				,'toggle' 		=> true
				,'prefix_class' => 'text%s-'
			)
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_button'
            ,array(
                'label' 		=> esc_html__( 'Button', 'themesky' )
                ,'tab'   		=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_responsive_control(
			'button_padding'
			,array(
				'label' 		=> esc_html__( 'Padding', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'.woocommerce {{WRAPPER}} .subscribe-email .button,
					{{WRAPPER}} .subscribe-email .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 		=> esc_html__( 'Typography', 'themesky' )
				,'name' 		=> 'button_typography'
				,'selector'		=> '.woocommerce {{WRAPPER}} .subscribe-email .button,
									{{WRAPPER}} .subscribe-email .button'
				,'exclude'		=> array('font_weight', 'font_family' ,'text_transform', 'font_style', 'text_decoration', 'word_spacing', 'letter_spacing')
			)
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);
		
		$this->start_controls_tab(
			'style_normal_tab'
			,array(
				'label' => esc_html__( 'Normal', 'themesky' )
			)
		);
		
		$this->add_control(
            'button_text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'#page .subscribe-email .button,
					#colophon .subscribe-email .button' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'button_background_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'#page .subscribe-email .button,
					#colophon .subscribe-email .button' => 'background-color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'button_border_color'
            ,array(
                'label'     	=> esc_html__( 'Border Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'#page .subscribe-email .button,
					#colophon .subscribe-email .button' => 'border-color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'style_hover_tab'
			,array(
				'label' => esc_html__( 'Hover', 'themesky' )
			)
		);
		
		$this->add_control(
            'button_text_hover'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'#page .subscribe-email .button:hover,
					#colophon .subscribe-email .button:hover' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'button_background_hover'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#C6213B'
				,'selectors'	=> array(
					'#page .subscribe-email .button:hover,
					#colophon .subscribe-email .button:hover' => 'background-color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'button_border_hover'
            ,array(
                'label'     	=> esc_html__( 'Border Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#C6213B'
				,'selectors'	=> array(
					'#page .subscribe-email .button:hover,
					#colophon .subscribe-email .button:hover' => 'border-color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_color'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_control(
            'heading_title'
            ,array(
                'label'     	=> esc_html__( 'Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'heading_typography'
				,'selector'			=> '{{WRAPPER}} .mailchimp-subscription .widget-title'
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
				,'exclude'	=> array('text_decoration', 'font_style', 'word_spacing')
			)
		);
		
		$this->add_control(
            'title_color'
            ,array(
                'label'     	=> esc_html__( 'Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'.mailchimp-subscription .widget-title' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_responsive_control(
			'title_max_width'
			,array(
				'label' 		=> esc_html__( 'Max Width', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px'	=> array(
						'min' 	=> 200
						,'max' 	=> 1000
					)
				)
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .mailchimp-subscription .widget-title' => 'max-width: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_responsive_control(
			'title_spacing'
			,array(
				'label' 		=> esc_html__( 'Spacing', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px'	=> array(
						'min' 	=> 0
						,'max' 	=> 100
					)
				)
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .mailchimp-subscription .widget-title' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_control(
            'intro_color'
            ,array(
                'label'     	=> esc_html__( 'Intro - Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'.mailchimp-subscription .newsletter' => 'color: {{VALUE}}'
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'input_text_color'
            ,array(
                'label'     	=> esc_html__( 'Input - Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'.mailchimp-subscription input[type="email"], 
					.mailchimp-subscription input[type="tel"]' => 'color: {{VALUE}}'
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'input_text_hover'
            ,array(
                'label'     	=> esc_html__( 'Input - Text Color Hover', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'.mailchimp-subscription input[type="email"], 
					.mailchimp-subscription input[type="tel"],
					.mailchimp-subscription input[type="email"]:hover, 
					.mailchimp-subscription input[type="tel"]:hover,
					.mailchimp-subscription input[type="email"]:focus, 
					.mailchimp-subscription input[type="tel"]:focus,
					.mailchimp-subscription input[type="email"]:focus:invalid:focus, 
					.mailchimp-subscription input[type="tel"]:focus:invalid:focus' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'input_background_color'
            ,array(
                'label'     	=> esc_html__( 'Input - Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'.mailchimp-subscription input[type="email"], 
					.mailchimp-subscription input[type="tel"],
					.mailchimp-subscription input[type="email"]:focus, 
					.mailchimp-subscription input[type="tel"]:focus' => 'background-color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'input_border_color'
            ,array(
                'label'     	=> esc_html__( 'Input - Border Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'.mailchimp-subscription input[type="email"], 
					.mailchimp-subscription input[type="tel"],
					.mailchimp-subscription input[type="email"]:focus, 
					.mailchimp-subscription input[type="tel"]:focus' => 'border-color: {{VALUE}}'
				)
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title'				=> ''
			,'intro_text'		=> ''
			,'form'				=> ''
			,'style'			=> ''
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('TS_Mailchimp_Subscription_Widget') ){
			return;
		}
		
		$intro_html = '';
		if( $intro_text ){
			$intro_html = '<div class="newsletter"><p>'.wp_kses( $intro_text, array( 'br' => array() ) ).'</p></div>';
			$intro_text = '';
		}
		
		$args = array(
			'before_widget' => '<section class="widget-container %s">'
			,'after_widget' => '</section>'
			,'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title heading-title">'
			,'after_title'  => '</h3>'.$intro_html.'</div>'
		);
		
		$is_elementor_widget = 1;
		
		$instance = compact('title', 'intro_text', 'form', 'is_elementor_widget');
		
		$classes = array();
		$classes[] = $style;
		$classes[] = $layout;
		
		echo '<div class="ts-mailchimp-subscription-shortcode '.implode(' ', $classes).'" >';
		
		the_widget('TS_Mailchimp_Subscription_Widget', $instance, $args);
		
		echo '</div>';
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Mailchimp() );
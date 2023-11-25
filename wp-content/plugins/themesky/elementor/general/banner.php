<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Banner extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-banner';
    }
	
	public function get_title(){
        return esc_html__( 'TS Banner', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-image';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_control(
            'style'
            ,array(
                'label' 			=> esc_html__( 'Style', 'themesky' )
                ,'type' 			=> Controls_Manager::SELECT
                ,'default' 			=> 'style-default'
				,'options'			=> array(
					'style-default'			=> esc_html__( 'Default', 'themesky' )
					,'style-simple'			=> esc_html__( 'Simple', 'themesky' )
					,'style-special'		=> esc_html__( 'Special', 'themesky' )
					,'style-special-inline'	=> esc_html__( 'Special Inline', 'themesky' )
					,'style-coupon'			=> esc_html__( 'Coupon', 'themesky' )
				)			
                ,'description' 		=> ''
            )
        );
		
		$this->add_control(
            'img_bg'
            ,array(
                'label' 		=> esc_html__( 'Background Image', 'themesky' )
                ,'type' 		=> Controls_Manager::MEDIA
                ,'default' 		=> array( 
					'id' 		=> ''
					,'url' 		=> '' 
				)		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'bg_image_device'
            ,array(
                'label' 		=> esc_html__( 'Background Image Device', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'none'
				,'options'		=> array(
					'none'					=> esc_html__( 'None', 'themesky' )
					,'img-mobile'			=> esc_html__( 'Mobile', 'themesky' )
					,'img-tablet'			=> esc_html__( 'Tablet', 'themesky' )
					,'img-mobile-tablet'	=> esc_html__( 'Mobile & Tablet', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'img_bg_mobile'
            ,array(
                'type' 			=> Controls_Manager::MEDIA
                ,'default' 		=> array( 'id' => '', 'url' => '' )		
                ,'description' 	=> esc_html__( 'Use this image for device. If not selected, it will show image above', 'themesky' )
				,'condition'	=> array( 
					'bg_image_device!' 	=> 'none'
				)
            )
        );
		
		$this->add_control(
            'link'
            ,array(
                'label'     		=> esc_html__( 'Link', 'themesky' )
                ,'type'     		=> Controls_Manager::URL
				,'default'  		=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
				,'show_external'	=> true
				,'description' 		=> ''
				,'condition'		=> array( 
					'show_button!' 	=> '1'
					,'style' 		=> array('style-default', 'style-simple', 'style-special', 'style-special-inline')
				)
            )
        );
		
		$this->add_control(
            'heading_title'
            ,array(
                'label' 		=> esc_html__( 'Heading Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'text_description'
            ,array(
                'label' 		=> esc_html__( 'Description Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'description_position'
            ,array(
                'label' 		=> esc_html__( 'Description Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'description-top'
				,'options'		=> array(
					'description-top'		=> esc_html__( 'Top', 'themesky' )
					,'description-bottom'	=> esc_html__( 'Bottom', 'themesky' )
				)			
                ,'description' 	=> esc_html__( 'Position relative to heading text', 'themesky' )
				,'condition'	=> array( 
					'style' 	=> array('style-default', 'style-coupon' )
				)
            )
        );
		
		$this->add_control(
            'text_description_2'
            ,array(
                'label' 		=> esc_html__( 'Description Text 2', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'condition'	=> array( 
					'style' 	=> array('style-special', 'style-special-inline')
				)
            )
        );
		
		$this->add_control(
            'coupon_code'
            ,array(
                'label' 		=> esc_html__( 'Coupon Code', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
            )
        );
		
		$this->add_control(
            'text_align'
			,array(
				'label' 		=> esc_html__( 'Alignment', 'themesky' )
				,'type' 		=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'text-left' 	=> array(
						'title' 	=> esc_html__( 'Left', 'themesky' )
						,'icon' 	=> 'eicon-text-align-left'
					)
					,'text-center'	=> array(
						'title' 	=> esc_html__( 'Center', 'themesky' )
						,'icon' 	=> 'eicon-text-align-center'
					)
					,'text-right' 	=> array(
						'title' 	=> esc_html__( 'Right', 'themesky' )
						,'icon' 	=> 'eicon-text-align-right'
					)
				)
				,'default' 		=> 'text-left'
				,'toggle' 		=> true
				,'separator'	=> 'before'
			)
        );
		
		$this->add_control(
            'content_position'
            ,array(
                'label' 		=> esc_html__( 'Content Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'left-top'
				,'options'		=> array(
					'left-top'			=> esc_html__( 'Left Top', 'themesky' )
					,'left-bottom'		=> esc_html__( 'Left Bottom', 'themesky' )
					,'left-center'		=> esc_html__( 'Left Center', 'themesky' )
					,'right-top'		=> esc_html__( 'Right Top', 'themesky' )
					,'right-bottom'		=> esc_html__( 'Right Bottom', 'themesky' )
					,'right-center'		=> esc_html__( 'Right Center', 'themesky' )
					,'center-top'		=> esc_html__( 'Center Top', 'themesky' )
					,'center-bottom'	=> esc_html__( 'Center Bottom', 'themesky' )
					,'center-center'	=> esc_html__( 'Center Center', 'themesky' )
				)			
                ,'description' 	=> ''

            )
        );
		
		$this->add_responsive_control(
			'content_spacing'
			,array(
				'label' 		=> esc_html__( 'Spacing', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .banner-wrapper .box-content' => 'padding: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_responsive_control(
			'content_max_width'
			,array(
				'label' 		=> esc_html__( 'Content Max Width', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px'	=> array(
						'min' 	=> 0
						,'max' 	=> 500
					)
				)
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .banner-wrapper .box-content h2' => 'max-width: {{SIZE}}{{UNIT}}; display: inline-block'
				)
			)
		);
		
		$this->add_control(
            'show_button'
			,array(
                'label' 		=> esc_html__( 'Show Button', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
				,'condition'	=> array( 
					'style' 	=> array('style-default', 'style-simple', 'style-special', 'style-special-inline')
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_button'
            ,array(
                'label' 		=> esc_html__( 'Button', 'themesky' )
                ,'tab'   		=> Controls_Manager::TAB_CONTENT
				,'condition'	=> array( 
					'show_button' 	=> '1'
				)
            )
        );
		
		$this->add_control(
            'button_style'
            ,array(
                'label' 		=> esc_html__( 'Button Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'button-default'
				,'options'		=> array(
					'button-default'		=> esc_html__( 'Button', 'themesky' )
					,'button-style-text'	=> esc_html__( 'Text', 'themesky' )
				)			
                ,'description' 	=> ''
				,'condition'	=> array( 
					'show_button' 	=> '1'
				)
            )
        );
		
		$this->add_control(
            'heading_button_title'
            ,array(
                'label'     	=> esc_html__( 'Button 01', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'condition'		=> array( 
					'show_button' 	=> '1'
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'button_text'
            ,array(
                'label'     	=> esc_html__( 'Button Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'condition'		=> array( 
					'show_button' 	=> '1' 
				)
            )
        );
		
		$this->add_control(
            'link_button_1'
            ,array(
                'label'     		=> esc_html__( 'Link', 'themesky' )
                ,'type'     		=> Controls_Manager::URL
				,'default'  		=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
				,'show_external'	=> true
				,'description' 		=> ''
				,'condition'		=> array( 
					'show_button' 	=> '1'
				)
            )
        );
		
		$this->add_control(
            'heading_button_2_title'
            ,array(
                'label'     	=> esc_html__( 'Button 02', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'condition'		=> array( 
					'show_button' 	=> '1'
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'button_text_2'
            ,array(
                'label'     	=> esc_html__( 'Button Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
				,'condition'		=> array( 
					'show_button' 	=> '1'
				)
            )
        );
		
		$this->add_control(
            'link_button_2'
            ,array(
                'label'     		=> esc_html__( 'Link', 'themesky' )
                ,'type'     		=> Controls_Manager::URL
				,'default'  		=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
				,'show_external'	=> true
				,'description' 		=> ''
				,'condition'		=> array( 
					'show_button' 	=> '1'
				)
            )
        );
		
		$this->add_responsive_control(
			'button_padding'
			,array(
				'label' 		=> esc_html__( 'Padding', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'.woocommerce {{WRAPPER}} .ts-banner .button,
					{{WRAPPER}} .ts-banner .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'condition'	=> array( 
					'show_button' 	=> '1'
					,'button_style' => 'button-default'
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 		=> esc_html__( 'Typography', 'themesky' )
				,'name' 		=> 'button_typography'
				,'selector'		=> '.woocommerce {{WRAPPER}} .ts-banner .button,
									{{WRAPPER}} .ts-banner .button,
									{{WRAPPER}} .ts-banner .button-text'
				,'exclude'		=> array('font_weight', 'font_family' ,'text_transform', 'font_style', 'text_decoration', 'word_spacing', 'letter_spacing')
			)
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);
		
		$this->start_controls_tab(
			'style_normal_tab'
			,array(
				'label' 		=> esc_html__( 'Normal', 'themesky' )
				,'condition'	=> array( 
					'show_button' 	=> '1'
				)
			)
		);
		
		$this->add_control(
            'button_text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .button,
					{{WRAPPER}} .button-text' => 'color: {{VALUE}}'
					,'{{WRAPPER}} .button-text:before' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'show_button'  => '1'
				)
            )
        );
		
		$this->add_control(
            'button_background_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .button' => 'background: {{VALUE}}'
				)
				,'condition'	=> array( 
					'show_button'  	=> '1'
					,'button_style' => 'button-default' 
				)
            )
        );
		
		$this->add_control(
            'button_border_color'
            ,array(
                'label'     	=> esc_html__( 'Border Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .button' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'show_button'  	=> '1'
					,'button_style' => 'button-default' 
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'style_hover_tab'
			,array(
				'label' 		=> esc_html__( 'Hover', 'themesky' )
				,'condition'	=> array( 
					'show_button'  => '1'
				)
			)
		);
		
		$this->add_control(
            'button_text_hover'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .button:hover,
					{{WRAPPER}} .button-text:hover' => 'color: {{VALUE}}'
					,'{{WRAPPER}} .button-text:hover:before' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'show_button'  => '1'
				)
            )
        );
		
		$this->add_control(
            'button_background_hover'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .button:hover' => 'background: {{VALUE}}'
				)
				,'condition'	=> array( 
					'show_button'  => '1'
					,'button_style' => 'button-default' 
				)
            )
        );
		
		$this->add_control(
            'button_border_hover'
            ,array(
                'label'     	=> esc_html__( 'Border Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .button:hover' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'show_button'  => '1'
					,'button_style' => 'button-default' 
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
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
                'label'     	=> esc_html__( 'Heading', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'heading_text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content h2' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'heading_typography'
				,'selector'			=> '{{WRAPPER}} .box-content h2'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '50'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
					,'line_height'		=> array(
						'default' 		=> array(
							'size' 		=> '60'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'	=> array('text_transform', 'font_style', 'word_spacing')
			)
		);
		
		$this->add_responsive_control(
			'heading_margin'
			,array(
				'label' 		=> esc_html__( 'Margin', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .box-content h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
			)
		);
		
		$this->add_control(
            'heading_description_font'
            ,array(
                'label'     	=> esc_html__( 'Description', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'description_text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content h4,
					{{WRAPPER}} .special-inline .inline-1' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'description_background_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content h4' => 'background-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> array('style-simple')
				)
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'description_typography'
				,'selector'			=> '{{WRAPPER}} .box-content h4, {{WRAPPER}} .special-inline .inline-1'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '16'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
					,'line_height'		=> array(
						'default' 		=> array(
							'size' 		=> '20'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'	=> array('text_transform', 'font_style', 'word_spacing')
			)
		);
		
		$this->add_responsive_control(
			'description_margin'
			,array(
				'label' 		=> esc_html__( 'Margin', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .box-content h4' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'condition'	=> array( 
					'style' 	=> array('style-default', 'style-simple', 'style-special', 'style-coupon')
				)
			)
		);
		
		$this->add_control(
            'heading_description_2_font'
            ,array(
                'label'     	=> esc_html__( 'Description 2', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'condition'	=> array( 
					'style' 	=> array('style-special', 'style-special-inline')
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'description_2_text_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content h3,
					{{WRAPPER}} .special-inline .inline-2' => 'color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> array('style-special', 'style-special-inline')
				)
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'description_2_typography'
				,'selector'			=> '{{WRAPPER}} .box-content h3, {{WRAPPER}} .special-inline .inline-2'
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
				,'exclude'		=> array('text_transform', 'font_style', 'word_spacing')
				,'condition'	=> array( 
					'style' 	=> array('style-special', 'style-special-inline')
				)
			)
		);
		
		$this->add_responsive_control(
			'description_2_margin'
			,array(
				'label' 		=> esc_html__( 'Margin', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .box-content h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'condition'	=> array( 
					'style' 	=> array('style-special')
				)
			)
		);
		
		$this->add_responsive_control(
			'description_inline_margin'
			,array(
				'label' 		=> esc_html__( 'Description - Margin', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .box-content .special-inline' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'condition'	=> array( 
					'style' 	=> array('style-special-inline')
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->add_control(
            'heading_coupon_font'
            ,array(
                'label'     	=> esc_html__( 'Coupon', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
				,'separator'	=> 'before'
            )
        );
		
		$this->start_controls_tabs(
			'style_tabs_coupon'
		);
		
		$this->start_controls_tab(
			'style_normal_tab_coupon'
			,array(
				'label' 		=> esc_html__( 'Normal', 'themesky' )
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
			)
		);
		
		$this->add_control(
            'coupon_code_color'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .coupon-code' 		=> 'color: {{VALUE}}'
					,'{{WRAPPER}} .box-content .coupon-code:before' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
            )
        );
		
		$this->add_control(
            'coupon_bg_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .coupon-code' => 'background-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'style_hover_tab_coupon'
			,array(
				'label' 		=> esc_html__( 'Hover', 'themesky' )
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
			)
		);
		
		$this->add_control(
            'coupon_code_color_hover'
            ,array(
                'label'     	=> esc_html__( 'Text Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .coupon-code:hover' 		=> 'color: {{VALUE}}'
					,'{{WRAPPER}} .box-content .coupon-code:hover:before' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
            )
        );
		
		$this->add_control(
            'coupon_bg_color_hover'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .box-content .coupon-code:hover' => 'background-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> array('style-coupon')
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_border'
            ,array(
                'label' 	=> esc_html__( 'Border', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
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
					'{{WRAPPER}} .ts-banner' => 'border-style: {{VALUE}};'
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
					'{{WRAPPER}} .ts-banner' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .ts-banner' => 'border-color: {{VALUE}};'
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
					'{{WRAPPER}} .ts-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
			)
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_background_overlay'
            ,array(
                'label' 	=> esc_html__( 'Background Overlay', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_control(
            'background_overlay_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
            )
        );
		
		$this->add_control(
			'background_overlay_location'
			,array(
				'label' 		=> esc_html__( 'Location', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'default' => array(
					'unit' => '%'
					,'size' => 0
				)
			)
		);
		
		$this->add_control(
            'background_overlay_second_color'
            ,array(
                'label'     	=> esc_html__( 'Background Second Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
            )
        );
		
		$this->add_control(
			'background_overlay_location_2'
			,array(
				'label' 		=> esc_html__( 'Location', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'default' 	=> array(
					'unit' => '%'
					,'size' => 0
				)
			)
		);
		
		$this->add_control(
			'background_overlay_gradient_angle'
			,array(
				'label' 		=> esc_html__( 'Angle', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'default' 		=> array(
					'unit' => 'deg'
					,'size' => 180
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .background-overlay' => 'background-color: transparent; background-image: linear-gradient({{SIZE}}{{UNIT}}, {{background_overlay_color.VALUE}} {{background_overlay_location.SIZE}}{{background_overlay_location.UNIT}}, {{background_overlay_second_color.VALUE}} {{background_overlay_location_2.SIZE}}{{background_overlay_location_2.UNIT}})'
				)
			)
		);
		
		$this->add_responsive_control(
			'background_overlay_opacity'
			,array(
				'label' 	=> esc_html__( 'Opacity', 'themesky' )
				,'type' 	=> Controls_Manager::SLIDER
				,'devices' 	=> array( 
					'desktop'
					,'tablet' 
					,'mobile' 
				)
				,'range' 	=> array(
					'px' 	=> array(
							'min' 	=> 0
							,'max' 	=> 1
					)
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .background-overlay' => 'opacity: {{SIZE}}'
				)
			)
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_effect'
            ,array(
                'label' 	=> esc_html__( 'Motion Effect', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_control(
            'effect_style'
            ,array(
                'label' 		=> esc_html__( 'Entrance Animation', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'eff-image eff-scale'
				,'options'		=> array(									
					'eff-image eff-scale'			=> esc_html__('Scale', 'themesky')
					,'eff-image eff-grow-rotate' 	=> esc_html__('Grow Rotate', 'themesky')
					,'eff-image eff-opacity' 		=> esc_html__('Opacity', 'themesky')
					,'eff-image eff-gray' 			=> esc_html__('Gray', 'themesky')
					,'no-effect' 					=> esc_html__('None', 'themesky')
				)			
                ,'description' 	=> ''
				,'label_block' 	=> true
				,'separator'	=> 'before'
            )
        );
		
		$this->add_control(
            'opacity_hover'
            ,array(
                'label'     	=> esc_html__( 'Opacity Hover', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
				,'default'  	=> '0'
				,'options'		=> array(
					'1'		=> esc_html__( 'Yes', 'themesky' )
					,'0'	=> esc_html__( 'No', 'themesky' )
				)
            )
        );
		
		$this->add_control(
            'background_color'
            ,array(
                'label'     	=> esc_html__( 'Background Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#ffffff'
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-banner' => 'background-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'opacity_hover' => array( '1' ) 
				)
            )
        );
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'img_bg'							=> array( 'id' => '', 'url' => '' )
			,'img_bg_mobile'					=> array( 'id' => '', 'url' => '' )
			,'bg_image_device'					=> 'none'
			,'style'							=> 'style-default'
			,'heading_title'					=> ''
			,'text_description'					=> ''
			,'text_description_2'				=> ''
			,'description_position'				=> 'description-top'
			,'heading_text_color'				=> '#ffffff'
			,'text_align'						=> 'text-left'
			,'content_position'					=> 'left-top'
			,'show_button'						=> 0
			,'button_style'						=> 'button-default'
			,'button_text'						=> ''
			,'button_text_2'					=> ''
			,'button_text_color'				=> '#000000'
			,'button_text_hover'				=> '#ffffff'
			,'button_background_color'			=> '#ffffff'
			,'button_background_hover'			=> '#000000'
			,'button_border_color'				=> '#ffffff'
			,'button_border_hover'				=> '#000000'
			,'link' 							=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
			,'link_button_1' 					=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
			,'link_button_2' 					=> array( 'url' => '', 'is_external' => true, 'nofollow' => true )
			,'effect_style'						=> 'eff-image eff-scale'
			,'opacity_hover'					=> '0'
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( $show_button ){
			$link_button_1_attr = $this->generate_link_attributes( $link_button_1 );
			$link_button_2_attr = $this->generate_link_attributes( $link_button_2 );
		}elseif( $style != 'style-coupon' ){
			$link_attr = $this->generate_link_attributes( $link );
		}
		
		$classes = array();
		$classes[] = $text_align;
		$classes[] = $effect_style;
		$classes[] = $content_position;
		$classes[] = $style;
		$classes[] = $description_position;
		$classes[] = $button_style;
		if( $opacity_hover ){
			$classes[] = 'has-opacity';
		}
		if( $bg_image_device != 'none' ){
			$classes[] = $bg_image_device;
		}
		
		$allowed_html = array( 'br' => array() );
		?>
		<div class="ts-banner <?php echo esc_attr( implode(' ', $classes) ); ?>" >
			<div class="banner-wrapper">
			
				<?php if( !$show_button && $style != 'style-coupon' && $link_attr != '' ): ?>
				<a class="banner-link" <?php echo implode(' ', $link_attr); ?>></a>
				<?php endif;?>
				
				<div class="background-overlay"></div>
							
				<div class="banner-bg">
				<?php 
					if( !empty( $img_bg_mobile['id'] ) && $bg_image_device != 'none' ){
						echo wp_get_attachment_image($img_bg_mobile['id'], 'full', 0, array('class' => 'bg-image mobile-banner', 'loading' => 'lazy'));
					}
					echo wp_get_attachment_image($img_bg['id'], 'full', 0, array('class' => 'bg-image bg-image main-banner', 'loading' => 'lazy'));
				?>
				</div>
							
				<div class="box-content">
					<div class="header-content">
						
						<?php if( ($text_description &&  $description_position == 'description-top') || ( $text_description && $style == 'style-special') || ( $text_description && $style == 'style-simple')): ?>
						<h4><?php echo wp_kses( $text_description, $allowed_html ); ?></h4>
						<?php endif;?>
						
						<?php if( $heading_title ): ?>
						<h2><?php echo wp_kses( $heading_title, $allowed_html ); ?></h2>
						<?php endif; ?>
						
						<?php if( $text_description &&  $description_position == 'description-bottom'): ?>
						<h4><?php echo wp_kses( $text_description, $allowed_html ); ?></h4>
						<?php endif;?>
						
						<?php if( $text_description_2 && $style == 'style-special'): ?>
						<h3><?php echo wp_kses( $text_description_2, $allowed_html ); ?></h3>
						<?php endif; ?>
						
						<?php if( $text_description && $style == 'style-special-inline' ): ?>
						<div class="special-inline">
							<span class="inline-1"><?php echo wp_kses( $text_description, $allowed_html ); ?></span>
							<span class="inline-2"><?php echo wp_kses( $text_description_2, $allowed_html ); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if( $coupon_code && $style == 'style-coupon'): ?>
						<div class="coupon-code ts-copy-button" data-copy="<?php echo esc_attr( $coupon_code ); ?>">
							<span><?php echo esc_html( $coupon_code ); ?></span>
							<span class="copy-button"></span>
							<span class="copy-message"><?php esc_html_e('Copied!', 'themesky'); ?></span>
						</div>
						<?php endif; ?>
						
						<?php if( $show_button && ( $button_text || $button_text_2 ) ): ?>
						<div class="ts-banner-button">
						
							<?php if( $button_text ): ?>
							<a class="<?php echo ($button_style == 'button-default')?'button':'button-text' ?>" <?php echo implode(' ', $link_button_1_attr); ?>>
								<?php echo esc_html($button_text); ?>
							</a>
							<?php endif; ?>
							
							<?php if( $button_text_2 ): ?>
							<a class="<?php echo ($button_style == 'button-default')?'button':'button-text' ?>" <?php echo implode(' ', $link_button_2_attr); ?>>
								<?php echo esc_html($button_text_2); ?>
							</a>
							<?php endif; ?>
							
						</div>
						<?php endif; ?>
						
					</div>
				</div>
				
			</div>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Banner() );
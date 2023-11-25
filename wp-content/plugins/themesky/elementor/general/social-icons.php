<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Social_Icons extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-social-icons';
    }
	
	public function get_title(){
        return esc_html__( 'TS Social Icons', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-social-icons';
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
            'social_style'
			,array(
				'label' 		=> esc_html__( 'Layout', 'themesky' )
				,'type' 		=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'style-vertical' 	=> array(
						'title' 	=> esc_html__( 'Default', 'themesky' )
						,'icon' 	=> 'eicon-editor-list-ul'
					)
					,'style-horizontal'	=> array(
						'title' 	=> esc_html__( 'Inline', 'themesky' )
						,'icon' 	=> 'eicon-ellipsis-h'
					)
				)
				,'default' 		=> 'style-horizontal'
			)
        );
		
		$repeater = new Elementor\Repeater();

		$repeater->add_control(
			'social_icon'
			,array(
				'label' 	=> esc_html__( 'Icon', 'themesky' )
				,'type' 	=> Controls_Manager::ICONS
				,'default' 	=> array(
					'value' 	=> 'fab fa-wordpress'
					,'library' 	=> 'fa-brands'
				)
				,'recommended' => array(
					'fa-brands' => array(
						'android'
						,'apple'
						,'behance'
						,'bitbucket'
						,'codepen'
						,'delicious'
						,'deviantart'
						,'digg'
						,'dribbble'
						,'elementor'
						,'facebook'
						,'flickr'
						,'foursquare'
						,'free-code-camp'
						,'github'
						,'gitlab'
						,'globe'
						,'houzz'
						,'instagram'
						,'jsfiddle'
						,'linkedin'
						,'medium'
						,'meetup'
						,'mix'
						,'mixcloud'
						,'odnoklassniki'
						,'pinterest'
						,'product-hunt'
						,'reddit'
						,'shopping-cart'
						,'skype'
						,'slideshare'
						,'snapchat'
						,'soundcloud'
						,'spotify'
						,'stack-overflow'
						,'steam'
						,'telegram'
						,'thumb-tack'
						,'tripadvisor'
						,'tumblr'
						,'twitch'
						,'twitter'
						,'viber'
						,'vimeo'
						,'vk'
						,'weibo'
						,'weixin'
						,'whatsapp'
						,'wordpress'
						,'xing'
						,'yelp'
						,'youtube'
						,'500px'
					)
					,'fa-solid' => array(
						'envelope'
						,'link'
						,'rss'
					)
				)
			)
		);
		
		$repeater->add_control(
            'link'
            ,array(
                'label' 		=> esc_html__( 'Link', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$repeater->add_control(
            'name'
            ,array(
                'label' 		=> esc_html__( 'Social Name', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''		
                ,'description' 	=> ''
            )
        );
		
		$repeater->add_control(
			'item_color'
			,array(
				'label' 	=> esc_html__( 'Color', 'themesky' )
				,'type' 	=> Controls_Manager::SELECT
				,'default' 	=> 'default'
				,'options' 	=> array(
					'default' 	=> esc_html__( 'Official Color', 'themesky' )
					,'custom' 	=> esc_html__( 'Custom', 'themesky' )
				)
			)
		);
		
		$repeater->add_control(
			'item_background_color'
			,array(
				'label' 		=> esc_html__( 'Background Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon' => 'background-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'item_color' => 'custom'
				)
			)
		);
		
		$repeater->add_control(
			'item_background_hover_color'
			,array(
				'label' 		=> esc_html__( 'Background Hover Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon:hover' => 'background-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'item_color' => 'custom'
				)
			)
		);
		
		$repeater->add_control(
			'item_icon_color'
			,array(
				'label' 		=> esc_html__( 'Icon/Text Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon i' 		=> 'color: {{VALUE}};'
					,'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon span' 	=> 'color: {{VALUE}};'
					,'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon svg' 	=> 'fill: {{VALUE}};'
				)
				,'condition' 	=> array(
					'item_color' => 'custom'
				)
			)
		);
		
		$repeater->add_control(
			'item_icon_hover_color'
			,array(
				'label' 		=> esc_html__( 'Icon/Text Hover Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon:hover i' 		=> 'color: {{VALUE}};'
					,'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon:hover span' 	=> 'color: {{VALUE}};'
					,'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon:hover svg' 	=> 'fill: {{VALUE}};'
				)
				,'condition' 	=> array(
					'item_color' => 'custom'
				)
			)
		);
		
		$repeater->add_control(
			'item_border_color'
			,array(
				'label' 		=> esc_html__( 'Border Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon' => 'border-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'item_color' => 'custom'
				)
			)
		);
		
		$repeater->add_control(
			'item_border_hover_color'
			,array(
				'label' 		=> esc_html__( 'Border Hover Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}}.elementor-social-icon:hover' => 'border-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'item_color' => 'custom'
				)
			)
		);
		
		$this->add_control(
			'social_icon_list'
			,array(
				'label' 	=> esc_html__( 'Social Icons', 'themesky' )
				,'type' 	=> Controls_Manager::REPEATER
				,'fields' 	=> $repeater->get_controls()
				,'default' 	=> array(
					array(
						'social_icon' => array(
							'value' 	=> 'fab fa-facebook'
							,'library' 	=> 'fa-brands'
						)
					)
					,array(
						'social_icon' => array(
							'value' 	=> 'fab fa-twitter'
							,'library' 	=> 'fa-brands'
						)
					)
					,array(
						'social_icon' => array(
							'value' 	=> 'fab fa-youtube'
							,'library' 	=> 'fa-brands'
						)
					)
				)
				,'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}'
			)
		);
		
		$this->add_control(
            'show_social_name'
            ,array(
                'label' 		=> esc_html__( 'Social Name', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )			
                ,'description' 	=> ''
            )
        );
		
		$this->add_responsive_control(
			'align'
			,array(
				'label' 		=> esc_html__( 'Alignment', 'themesky' )
				,'type' 		=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'flex-start'    	=> array(
						'title' 	=> esc_html__( 'Left', 'themesky' )
						,'icon' 	=> 'eicon-text-align-left'
					)
					,'center' 	=> array(
						'title' 	=> esc_html__( 'Center', 'themesky' )
						,'icon' 	=> 'eicon-text-align-center'
					)
					,'flex-end' 	=> array(
						'title' 	=> esc_html__( 'Right', 'themesky' )
						,'icon' 	=> 'eicon-text-align-right'
					)
				)
				,'prefix_class' => 'e-grid-align%s-'
				,'default' 		=> ''
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-social-icons-elementor-widget .list-items' => 'justify-content: {{VALUE}}'
				)
			)
		);
		
		$this->add_control(
			'hover_animation'
			,array(
				'label' => esc_html__( 'Hover Animation', 'themesky' )
				,'type' => Controls_Manager::HOVER_ANIMATION
			)
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_social_style'
			,array(
				'label' => esc_html__( 'General', 'themesky' )
				,'tab' 	=> Controls_Manager::TAB_STYLE
			)
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 		=> esc_html__( 'Typography', 'themesky' )
				,'name' 		=> 'name_typography'
				,'selector'		=> '{{WRAPPER}} .ts-social-icons-elementor-widget .elementor-social-icon span'
				,'exclude'		=> array('font_style', 'text_decoration', 'word_spacing', 'letter_spacing')
			)
		);
		
		$this->add_responsive_control(
			'icon_size'
			,array(
				'label' 		=> esc_html__( 'Icon Size', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px' 		=> array(
						'min' 	=> 0
						,'max' 	=> 50
					)
				)
				,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-social-icons-elementor-widget .elementor-social-icon i' => 'font-size: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_control(
			'color'
			,array(
				'label' 		=> esc_html__( 'Color', 'themesky' )
				,'type' 		=> Controls_Manager::SELECT
				,'default' 		=> 'default'
				,'options' 		=> array(
					'default' 	=> esc_html__( 'Official Color', 'themesky' )
					,'custom' 	=> esc_html__( 'Custom', 'themesky' )
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->start_controls_tabs(
			'style_tabs'
		);
		
		$this->start_controls_tab(
			'style_normal_tab'
			,array(
				'label' 		=> esc_html__( 'Normal', 'themesky' )
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->add_control(
			'icon_color'
			,array(
				'label' 		=> esc_html__( 'Icon/Text Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-social-icon i' 		=> 'color: {{VALUE}};'
					,'{{WRAPPER}} .elementor-social-icon span' 	=> 'color: {{VALUE}};'
					,'{{WRAPPER}} .elementor-social-icon svg' 	=> 'fill: {{VALUE}};'
				)
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->add_control(
			'background_color'
			,array(
				'label' 		=> esc_html__( 'Background Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-social-icon' => 'background-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->add_control(
			'border_color'
			,array(
				'label' 		=> esc_html__( 'Border Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-social-icon' => 'border-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'style_hover_tab'
			,array(
				'label' 		=> esc_html__( 'Hover', 'themesky' )
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->add_control(
			'icon_hover_color'
			,array(
				'label' 		=> esc_html__( 'Icon/Text Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-social-icon:hover i' 		=> 'color: {{VALUE}};'
					,'{{WRAPPER}} .elementor-social-icon:hover span' 	=> 'color: {{VALUE}};'
					,'{{WRAPPER}} .elementor-social-icon:hover svg' 	=> 'fill: {{VALUE}};'
				)
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->add_control(
			'background_hover_color'
			,array(
				'label' 		=> esc_html__( 'Background Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-social-icon:hover' => 'background-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->add_control(
			'border_hover_color'
			,array(
				'label' 		=> esc_html__( 'Border Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'selectors' 	=> array(
					'{{WRAPPER}} .elementor-social-icon:hover' => 'border-color: {{VALUE}};'
				)
				,'condition' 	=> array(
					'color' 	=> 'custom'
				)
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'item_padding'
			,array(
				'label' 		=> esc_html__( 'Padding', 'themesky' )
				,'type' 		=> Controls_Manager::DIMENSIONS
				,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-social-icons-elementor-widget .elementor-social-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->add_responsive_control(
			'item_margin'
			,array(
				'label' 	=> esc_html__( 'Margin', 'themesky' )
				,'type' 	=> Controls_Manager::SLIDER
				,'range' 	=> array(
					'px' 	=> array(
							'min' 	=> 0
							,'max' 	=> 50
					)
				)
				,'selectors' => array(
					'{{WRAPPER}} .ts-social-icons-elementor-widget .list-items' => 'margin: -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0 0;'
					,'{{WRAPPER}} .ts-social-icons-elementor-widget .list-items > span' => 'margin: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;'
				)
				,'separator'	=> 'before'
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title'					=> ''
			,'title_style'			=> 'title-default'
			,'social_style'			=> 'style-horizontal'
			,'social_icon_list'		=> array()
			,'show_social_name'		=> 0
			,'hover_animation'		=> ''
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		$classes = array('ts-social-icons-elementor-widget social-icons');
		$classes[] = $title_style;
		$classes[] = $social_style;
		
		if( $show_social_name ){
			$classes[] = 'show-name';
		}
		?>
		<div class="<?php echo implode(' ', $classes); ?>">
			<?php if( $title ): ?>
			<header class="shortcode-heading-wrapper">
				<h2 class="shortcode-title">
					<?php echo esc_html($title); ?>
				</h2>
			</header>
			<?php endif; ?>
			
			<div class="list-items">
			<?php
			foreach( $social_icon_list as $index => $item ){
				$social = '';
				if( 'svg' !== $item['social_icon']['library'] ){
					$social = explode( ' ', $item['social_icon']['value'], 2 );
					if( empty( $social[1] ) ){
						$social = '';
					}else{
						$social = str_replace( 'fa-', '', $social[1] );
						$social = str_replace( 'icomoon-', '', $social );
					}
				}
				
				if( 'svg' === $item['social_icon']['library'] ){
					$social = get_post_meta( $item['social_icon']['value']['id'], '_wp_attachment_image_alt', true );
				}
				
				$item_class = array('elementor-icon elementor-social-icon');
				$item_class[] = 'elementor-social-icon-' . $social;
				$item_class[] = 'elementor-repeater-item-' . $item['_id'];
				$item_class[] = 'elementor-animation-' . $hover_animation;
				?>
				<span>
					<a href="<?php echo esc_url($item['link']) ?>" target="_blank" class="<?php echo implode(' ', $item_class); ?>">
						<?php Elementor\Icons_Manager::render_icon( $item['social_icon'] ); ?>
						<?php if( $item['name'] && $show_social_name ){ ?>
							<span class="social-name"><?php echo esc_html($item['name']); ?></span>
						<?php } ?>
					</a>
				</span>
				<?php
			}
			?>
			</div>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Social_Icons() );
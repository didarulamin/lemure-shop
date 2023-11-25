<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_Coupon extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-coupon';
    }
	
	public function get_title(){
        return esc_html__( 'TS Coupon', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'general' );
    }
	
	public function get_icon(){
		return 'eicon-flash';
	}
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'number'
			,array(
				'label' 		=> esc_html__( 'Discount Number', 'themesky' )
				,'type' 		=> Controls_Manager::TEXT
				,'default' 		=> ''
				,'description' 	=> esc_html__( 'Example: -$20, -20%, ...', 'themesky' )
			)
		);
		
		$repeater->add_control(
			'color'
			,array(
				'label' 		=> esc_html__( 'Color', 'themesky' )
				,'type' 		=> Controls_Manager::COLOR
				,'default' 		=> '#F9AD00'
				,'selectors'	=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .discount-number' => 'color: {{VALUE}}; stroke-color: {{VALUE}}; -webkit-text-stroke-color: {{VALUE}}'
				)
			)
		);
		
		$repeater->add_control(
			'caption'
			,array(
				'label' 		=> esc_html__( 'Discount Caption', 'themesky' )
				,'type' 		=> Controls_Manager::TEXT
				,'default' 		=> ''
				,'separator'	=> 'before'
			)
		);
		
		$repeater->add_responsive_control(
			'text_max_width'
			,array(
				'label' 	=> esc_html__( 'Max Width', 'themesky' )
				,'type' 	=> Controls_Manager::SLIDER
				,'range' 	=> array(
					'px' 	=> array(
						'min' 		=> 50
						,'max' 		=> 500
					)
				)
				,'devices' 	=> array( 
					'desktop'
					,'tablet' 
					,'mobile' 
				)
				,'desktop_default' 	=> array(
					'size' 			=> 120
					,'unit' 		=> 'px'
				)
				,'selectors' 		=> array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .discount-caption' => 'max-width: {{SIZE}}{{UNIT}};'
				)
			)
		);
		
		$this->add_control(
            'discounts'
            ,array(
                'label' 		=> esc_html__( 'Discounts', 'themesky' )
                ,'type' 		=> Controls_Manager::REPEATER
                ,'fields' 		=> $repeater->get_controls()
            )
        );
		
		$this->add_control(
            'coupon_code'
            ,array(
                'label' 		=> esc_html__( 'Coupon Code', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'item_layout'
            ,array(
                'label' 		=> esc_html__( 'Item Layout', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'vertical'
				,'options'		=> array(
					'vertical'		=> esc_html__( 'Vertical', 'themesky' )
					,'horizontal'	=> esc_html__( 'Horizontal', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'discount_number_style'
            ,array(
                'label' 		=> esc_html__( 'Discount Number Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'default'
				,'options'		=> array(
					'default'	=> esc_html__( 'Default', 'themesky' )
					,'outline'	=> esc_html__( 'Outline', 'themesky' )
				)			
                ,'description' 	=> ''
            )
        );
		
		$this->add_responsive_control(
            'content_align'
			,array(
				'label' 			=> esc_html__( 'Alignment', 'themesky' )
				,'type' 			=> Controls_Manager::CHOOSE
				,'options' 			=> array(
					'left' 			=> array(
						'title' 	=> esc_html__( 'Left', 'themesky' )
						,'icon' 	=> 'eicon-text-align-left'
					)
					,'center'		=> array(
						'title' 	=> esc_html__( 'Center', 'themesky' )
						,'icon' 	=> 'eicon-text-align-center'
					)
					,'right' 		=> array(
						'title' 	=> esc_html__( 'Right', 'themesky' )
						,'icon' 	=> 'eicon-text-align-right'
					)
				)
				,'selectors' 		=> array(
					'{{WRAPPER}} .ts-coupon-wrapper .discounts' => 'text-align: {{VALUE}};'
				)
				,'default' 		=> ''
				,'toggle' 		=> true
				,'separator'	=> 'before'
			)
        );
		
		$this->add_responsive_control(
			'item_spacing'
			,array(
				'label' 	=> esc_html__( 'Spacing', 'themesky' )
				,'type' 	=> Controls_Manager::SLIDER
				,'range' 	=> array(
					'px' 	=> array(
						'min' 	=> 0
						,'max' 	=> 50
					)
				)
				,'selectors' => array(
					'{{WRAPPER}} .ts-coupon-wrapper,
					{{WRAPPER}} .ts-coupon-wrapper .item' => 'margin: 0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}};'
					,'{{WRAPPER}} .ts-coupon-wrapper > *,
					{{WRAPPER}} .ts-coupon-wrapper .item > *' => 'margin: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};'
				)
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
            'discount_number_font'
            ,array(
                'label'     	=> esc_html__( 'Discount Number', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'text_typography'
				,'selector'			=> '{{WRAPPER}} .discount-number'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '60'
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
				,'exclude'	=> array('text_decoration', 'text_transform', 'font_style', 'word_spacing')
			)
		);
		
		$this->add_control(
            'discount_caption_font'
            ,array(
                'label'     	=> esc_html__( 'Discount Caption', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'caption_typography'
				,'selector'			=> '{{WRAPPER}} .discount-caption'
				,'fields_options'	=> array(
					'font_size'			=> array(
						'default'		=> array(
							'size' 		=> '14'
							,'unit' 	=> 'px'
						)
						,'size_units' 	=> array( 'px', 'em', 'rem', 'vw' )
					)
					,'line_height'		=> array(
						'default' 		=> array(
							'size' 		=> '18'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'	=> array('text_decoration', 'text_transform', 'font_style', 'word_spacing')
			)
		);
		
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'discounts'					=> array()
			,'coupon_code'				=> ''
			,'item_layout' 				=> 'vertical'
			,'discount_number_style' 	=> 'default'
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		$classes = array('ts-coupon-wrapper');
		$classes[] = 'number-style-' . $discount_number_style;
		$classes[] = 'item-' . $item_layout;
		if( count($discounts) > 1 ){
			$classes[] = 'more-items';
		}
		?>
		<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">	
			<?php if( $discounts ){ ?>
			<div class="discounts">
				<?php foreach( $discounts as $item ){ ?>
					<div class="item <?php echo esc_attr('elementor-repeater-item-' . $item['_id']); ?>">
						<span class="discount-number"><?php echo esc_html($item['number']) ?></span>
						<span class="discount-caption"><?php echo esc_html($item['caption']) ?></span>
					</div>
				<?php } ?>
			</div>
			<?php } ?>
			
			<?php if( $coupon_code ){ ?>
			<div class="coupon-code ts-copy-button" data-copy="<?php echo esc_attr($coupon_code); ?>">
				<span><?php echo esc_html($coupon_code); ?></span>
				<span class="copy-message"><?php esc_html_e('Copied!', 'themesky'); ?></span>
			</div>
			<?php } ?>
			
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_Coupon() );
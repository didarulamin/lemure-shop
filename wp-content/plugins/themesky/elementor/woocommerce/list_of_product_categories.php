<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_List_Of_Product_Categories extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-list-of-product-categories';
    }
	
	public function get_title(){
        return esc_html__( 'TS List Of Product Categories', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements', 'woocommerce-elements' );
    }
	
	protected function register_controls(){
		$this->start_controls_section(
            'section_general'
            ,array(
                'label' 	=> esc_html__( 'General', 'themesky' )
                ,'tab'   	=> Controls_Manager::TAB_CONTENT
            )
        );
		
		$this->add_responsive_control(
            'style'
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
				,'default' 		=> 'style-vertical'
			)
        );
		
		$this->add_control(
            'title'
            ,array(
                'label'     	=> esc_html__( 'Title', 'themesky' )
                ,'type'     	=> Controls_Manager::TEXT
				,'default'  	=> ''
            )
        );
		
		$this->add_control(
            'title_style'
            ,array(
                'label' 			=> esc_html__( 'Title Style', 'themesky' )
                ,'type' 			=> Controls_Manager::SELECT
                ,'default' 			=> 'title-style-default'
				,'options'			=> array(
					'title-style-default'	=> esc_html__( 'Default', 'themesky' )
					,'title-style-inline'	=> esc_html__( 'Inline', 'themesky' )
				)			
                ,'description' 		=> ''
				,'condition'	=> array( 
					'style' 	=> 'style-horizontal' 
				)
            )
        );
		
		$this->add_responsive_control(
			'title_width'
			,array(
				'label' 		=> esc_html__( 'Title Width', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'range' 		=> array(
					'px'	=> array(
						'min' 	=> 10
						,'max' 	=> 200
					)
				)
				,'size_units' 	=> array( 'px' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-list-of-product-categories-wrapper .heading-title' => 'min-width: {{SIZE}}{{UNIT}}'
				)
			)
		);
		
		$this->add_control(
            'limit'
            ,array(
                'label'     	=> esc_html__( 'Limit', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> 12
				,'min'      	=> 1
            )
        );
		
		$this->add_responsive_control(
            'columns'
            ,array(
                'label'     	=> esc_html__( 'Columns', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'min'      	=> 1
				,'default' 		=> 2
				,'selectors' => array(
					'{{WRAPPER}} .list-categories ul' => 'grid-template-columns: repeat({{VALUE}}, 1fr);'
				)
				,'condition'	=> array( 
					'style' 	=> 'style-vertical' 
				)
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
				,'description' 	=> esc_html__( 'Get children of this category', 'themesky' )
            )
        );
		
		$this->add_control(
            'direct_child'
			,array(
                'label' 		=> esc_html__( 'Direct Children', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'		
                ,'description' 	=> esc_html__( 'Get direct children of Parent or all children', 'themesky' )
            )
        );
		
		$this->add_control(
            'include_parent'
			,array(
                'label' 		=> esc_html__( 'Include Parent', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Show parent category at the first of list', 'themesky' )
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
                'label' 			=> esc_html__( 'Hide Empty Product Categories', 'themesky' )
                ,'type' 			=> Controls_Manager::SWITCHER
                ,'default' 			=> '1'
				,'return_value' 	=> '1'			
                ,'description' 		=> ''
            )
        );
		
		$this->add_control(
            'show_icon'
			,array(
                'label' 			=> esc_html__( 'Show Icon', 'themesky' )
                ,'type' 			=> Controls_Manager::SWITCHER
                ,'default' 			=> '0'
				,'return_value' 	=> '1'			
                ,'description' 		=> esc_html__( 'Icon is set in Products > Categories', 'themesky' )
            )
        );
		
		$this->add_responsive_control(
            'text_align'
			,array(
				'label' 		=> esc_html__( 'Alignment', 'themesky' )
				,'type' 		=> Controls_Manager::CHOOSE
				,'options' 		=> array(
					'flex-start' 		=> array(
						'title' 	=> esc_html__( 'Left', 'themesky' )
						,'icon' 	=> 'eicon-text-align-left'
					)
					,'center'	=> array(
						'title' 	=> esc_html__( 'Center', 'themesky' )
						,'icon' 	=> 'eicon-text-align-center'
					)
					,'flex-end' 	=> array(
						'title' 	=> esc_html__( 'Right', 'themesky' )
						,'icon' 	=> 'eicon-text-align-right'
					)
				)
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-list-of-product-categories-wrapper ul,
					{{WRAPPER}} .ts-list-of-product-categories-wrapper .list-categories' => 'justify-content: {{VALUE}};'
				)
				,'default' 		=> ''
				,'toggle' 		=> true
				,'separator'	=> 'before'
			)
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
            'section_style'
            ,array(
                'label' 		=> esc_html__( 'General', 'themesky' )
                ,'tab'   		=> Controls_Manager::TAB_STYLE
            )
        );
		
		$this->add_responsive_control(
			'content_spacing'
			,array(
				'label' 		=> esc_html__( 'Space Between', 'themesky' )
				,'type' 		=> Controls_Manager::SLIDER
				,'size_units' 	=> array( 'px', '%', 'em', 'rem', 'vw' )
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-list-of-product-categories-wrapper:not(.style-horizontal) .list-categories ul' => 'row-gap: {{SIZE}}{{UNIT}};'
				)
				,'condition'	=> array( 
					'style' 	=> 'style-vertical' 
				)
			)
		);
		
		$this->add_control(
            'heading_title_font'
            ,array(
                'label'     	=> esc_html__( 'Heading Title', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'condition'	=> array( 
					'style' => 'style-horizontal' 
				)
            )
        );
		
		$this->add_control(
            'heading_text_color'
            ,array(
                'label'     	=> esc_html__( 'Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .list-categories .heading-title'=> 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'heading_typography'
				,'selector'			=> '{{WRAPPER}} .list-categories .heading-title'
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
							'size' 		=> '36'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'		=> array('text_decoration', 'text_transform', 'font_style', 'word_spacing')
				,'condition'	=> array( 
					'style' 	=> 'style-horizontal' 
				)
			)
		);
		
		$this->add_control(
            'content_font'
            ,array(
                'label'     	=> esc_html__( 'Text', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'content_typography'
				,'selector'			=> '{{WRAPPER}} .ts-list-of-product-categories-wrapper li a'
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
							'size' 		=> '20'
							,'unit' 	=> 'px'
						)
					)
				)
				,'exclude'		=> array('text_decoration', 'font_style', 'word_spacing')
				,'condition'	=> array( 
					'style' 	=> 'style-vertical' 
				)
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
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-list-of-product-categories-wrapper ul li a'=> 'color: {{VALUE}}'
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
					'{{WRAPPER}} .style-horizontal ul li a' => 'background: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> 'style-horizontal' 
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
					'{{WRAPPER}} .style-horizontal ul li a' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> 'style-horizontal' 
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'style_hover_tab'
			,array(
				'label' 		=> esc_html__( 'Hover', 'themesky' )
			)
		);
		
		$this->add_control(
            'button_text_hover'
            ,array(
                'label'     	=> esc_html__( 'Text Hover Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> ''
				,'selectors'	=> array(
					'{{WRAPPER}} .ts-list-of-product-categories-wrapper ul li a:hover' => 'color: {{VALUE}}'
				)
            )
        );
		
		$this->add_control(
            'button_background_hover'
            ,array(
                'label'     	=> esc_html__( 'Background Hover Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .style-horizontal ul li a:hover' => 'background: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> 'style-horizontal' 
				)
            )
        );
		
		$this->add_control(
            'button_border_hover'
            ,array(
                'label'     	=> esc_html__( 'Border Hover Color', 'themesky' )
                ,'type'     	=> Controls_Manager::COLOR
				,'default'  	=> '#000000'
				,'selectors'	=> array(
					'{{WRAPPER}} .style-horizontal ul li a:hover' => 'border-color: {{VALUE}}'
				)
				,'condition'	=> array( 
					'style' 	=> 'style-horizontal' 
				)
            )
        );
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title' 				=> ''
			,'style'				=> 'style-vertical'
			,'title_style'			=> ''	
			,'limit'				=> 12
			,'columns'				=> 2
			,'parent'				=> array()
			,'direct_child'			=> 1
			,'include_parent'		=> 1
			,'ids'					=> array()
			,'hide_empty'			=> 1
			,'show_icon'			=> 0
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		
		if( is_array($parent) ){
			$parent = implode( '', $parent );
		}
		
		if( $parent && $include_parent ){
			$limit = absint($limit) - 1;
		}
		
		$args = array(
			'taxonomy'		=> 'product_cat'
			,'hide_empty'	=> $hide_empty
			,'number'		=> $limit
		);
		
		if( $parent ){
			if( $direct_child ){
				$args['parent'] = $parent;
			}
			else{
				$args['child_of'] = $parent;
			}
		}
		
		if( $ids ){
			$args['include'] = $ids;
			$args['orderby'] = 'include';
		}
		
		$list_categories = get_terms( $args );
		
		if( !is_array($list_categories) || empty($list_categories) ){
			return;
		}
		
		$classes = array('ts-list-of-product-categories-wrapper');
		$classes[] = $style;
		$classes[] = $title_style;
		if( $columns ){
			$classes[] = 'columns-' . $columns;
		}
		?>
		<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>">		
			<div class="list-categories">
				<?php if( $title ): ?>		
				<h3 class="heading-title">
					<?php echo esc_html($title) ?>
				</h3>
				<?php endif; ?>
				
				<ul>
					<?php 
					if( $parent && $include_parent ){
						$parent_obj = get_term($parent, 'product_cat');
						if( isset($parent_obj->name) ){
					?>
						<li>
							<a href="<?php echo get_term_link($parent_obj, 'product_cat'); ?>">
							<?php
							if( $show_icon ){
								echo $this->get_icon_html($parent_obj->term_id);
							}
							echo esc_html($parent_obj->name);
							?>
							</a>
						</li>
					<?php
						}
					}
					?>
					
					<?php foreach( $list_categories as $category ){ ?>
						<li>
							<a href="<?php echo get_term_link($category, 'product_cat'); ?>">
							<?php
							if( $show_icon ){
								echo $this->get_icon_html($category->term_id);
							}
							echo esc_html($category->name);
							?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php
	}
	
	function get_icon_html( $term_id ){
		$icon_id = get_term_meta($term_id, 'icon_id', true);
		if( $icon_id ){
			return wp_get_attachment_image( $icon_id, 'thumbnail' );
		}
		return '';
	}
}

$widgets_manager->register( new TS_Elementor_Widget_List_Of_Product_Categories() );
<?php
use Elementor\Controls_Manager;

class TS_Elementor_Widget_List_Of_Product_Tags extends TS_Elementor_Widget_Base{
	public function get_name(){
        return 'ts-list-of-product-tags';
    }
	
	public function get_title(){
        return esc_html__( 'TS List Of Product Tags', 'themesky' );
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
		
		$this->add_control(
            'title'
            ,array(
                'label'     	=> esc_html__( 'Title', 'themesky' )
                ,'type'     	=> Controls_Manager::TEXT
				,'default'  	=> ''
            )
        );
		
		$this->add_control(
            'prefix'
            ,array(
                'label'     	=> esc_html__( 'Tag Prefix', 'themesky' )
                ,'type'     	=> Controls_Manager::TEXT
				,'default'  	=> '#'
				,'description' 	=> esc_html__( 'Add prefix to each tag', 'themesky' )
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
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-list-of-product-tags-wrapper:not(.style-horizontal) .list-tags ul' => ' grid-template-columns: repeat({{VALUE}}, 1fr);'
				)
            )
        );
		
		$this->add_control(
            'ids'
            ,array(
                'label' 		=> esc_html__( 'Specific Tags', 'themesky' )
                ,'type' 		=> 'ts_autocomplete'
                ,'default' 		=> array()
				,'options'		=> array()
				,'autocomplete'	=> array(
					'type'		=> 'taxonomy'
					,'name'		=> 'product_tag'
				)
				,'multiple' 	=> true
				,'label_block' 	=> true
            )
        );
		
		$this->add_control(
            'hide_empty'
            ,array(
                'label' 		=> esc_html__( 'Hide Empty Product Tags', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_slug'
            ,array(
                'label' 		=> esc_html__( 'Show Tag Slug', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Show tag slug instead of tag name', 'themesky' )
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
					'{{WRAPPER}} .ts-list-of-product-tags-wrapper:not(.style-horizontal) .list-tags ul' => 'row-gap: {{SIZE}}{{UNIT}};'
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
				,'selector'			=> '{{WRAPPER}} .ts-list-of-product-tags-wrapper li a'
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
			)
		);
		
		$this->end_controls_section();
	}
	
	protected function render(){
		$settings = $this->get_settings_for_display();
		
		$default = array(
			'title' 				=> ''
			,'prefix' 				=> '#'
			,'limit'				=> 12
			,'columns'				=> 2
			,'ids'					=> array()
			,'hide_empty'			=> 1
			,'show_slug'			=> 1
		);
		
		$settings = wp_parse_args( $settings, $default );
		
		extract( $settings );
		
		if( !class_exists('WooCommerce') ){
			return;
		}
		
		$args = array(
			'taxonomy'		=> 'product_tag'
			,'hide_empty'	=> $hide_empty
			,'number'		=> $limit
		);
		
		if( $ids ){
			$args['include'] = $ids;
			$args['orderby'] = 'include';
		}
		
		$list_tags = get_terms( $args );
		
		if( !is_array($list_tags) || empty($list_tags) ){
			return;
		}
		
		$classes = array('ts-list-of-product-tags-wrapper');
		if( $columns ){
			$classes[] = 'columns-' . $columns;
		}
		?>
		<div class="<?php echo esc_attr( implode(' ', $classes) ); ?>">		
			<div class="list-tags">
				<?php if( $title ): ?>		
				<h3 class="heading-title">
					<?php echo esc_html($title) ?>
				</h3>
				<?php endif; ?>
				
				<ul>
					<?php foreach( $list_tags as $tag ){ ?>
					<li><a href="<?php echo get_term_link($tag, 'product_tag'); ?>"><?php echo esc_html($prefix); ?><?php echo esc_html( $show_slug ? $tag->slug : $tag->name ); ?></a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<?php
	}
}

$widgets_manager->register( new TS_Elementor_Widget_List_Of_Product_Tags() );
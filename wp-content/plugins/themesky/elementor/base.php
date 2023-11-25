<?php
use Elementor\Controls_Manager;

abstract class TS_Elementor_Widget_Base extends Elementor\Widget_Base{
	public function get_name(){
        return 'ts-base';
    }
	
	public function get_title(){
        return esc_html__( 'ThemeSky Base', 'themesky' );
    }
	
	public function get_categories(){
        return array( 'ts-elements' );
    }
	
	/* key|value,key|value => return array */
	public function parse_link_custom_attributes( $custom_attributes ){
		if( !$custom_attributes ){
			return array();
		}
		
		$attributes = array();
		
		$custom_attributes = str_replace(' ', '', $custom_attributes);
		
		$custom_attributes = explode(',', $custom_attributes);
		foreach( $custom_attributes as $custom_attribute ){
			$attr = explode('|', $custom_attribute);
			if( count($attr) == 2 ){
				$attributes[] = $attr;
			}
		}
		
		return $attributes;
	}
	
	public function generate_link_attributes( $link ){
		$link_attr = array();
		
		if( $link['url'] ){
			$link_attr[] = 'href="' . esc_url($link['url']) . '"';
			$link_attr[] = $link['is_external'] ? 'target="_blank"' : '';
			$link_attr[] = $link['nofollow'] ? 'rel="nofollow"' : '';
			
			if( !empty($link['custom_attributes']) ){
				$link_custom_attributes = $this->parse_link_custom_attributes( $link['custom_attributes'] );
				foreach( $link_custom_attributes as $attr ){
					$link_attr[] = $attr[0] . '="' . esc_attr($attr[1]) . '"';
				}
			}
		}
		
		return $link_attr;
	}
	
	public function get_custom_taxonomy_options( $tax = '' ){
		if( !$tax ){
			return;
		}
		
		$terms = get_terms( array(
				'taxonomy'		=> $tax
				,'hide_empty'	=> false
				,'fields'		=> 'id=>name'
			) );
			
		return is_array($terms) ? $terms : array();
	}
	
	public function get_custom_post_options( $post_type = 'post' ){
		$args = array(
				'post_type'				=> $post_type
				,'post_status'			=> 'publish'
				,'posts_per_page'		=> -1
			);
			
		$posts = array();
		
		$query_obj = new WP_Query($args);
		if( $query_obj->have_posts() ){
			foreach( $query_obj->posts as $p ){
				$posts[$p->ID] = $p->post_title;
			}
		}
		
		return $posts;
	}
	
	public function add_lazy_load_controls( $args = array() ){
		$this->add_control(
            'lazy_load'
            ,array(
                'label' 		=> esc_html__( 'Lazy Load', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'Show placeholder and only load content when users scroll down', 'themesky' )
            )
        );
		
		$this->add_responsive_control(
			'lazy_load_thumb_height'
			,array(
				'label' 		=> isset( $args['thumb-label'] ) ? $args['thumb-label'] : esc_html__( 'Lazy Load - Thumbnail Height', 'themesky' )
				,'type' 		=> Controls_Manager::NUMBER
				,'default'		=> isset( $args['thumb-height'] ) ? $args['thumb-height'] : 300
				,'selectors' 	=> array(
					'{{WRAPPER}} .ts-elementor-lazy-load' => '--lazy-thumb-height: {{VALUE}}px'
				)
				,'separator'	=> 'after'
				,'condition' 	=> array( 'lazy_load' => '1' )
			)
		);
	}
	
	public function add_title_and_style_controls(){
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type()
			,array(
				'label' 			=> esc_html__( 'Typography', 'themesky' )
				,'name' 			=> 'heading_typography'
				,'selector'			=> '{{WRAPPER}} .shortcode-title'
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
	}
	
	public function add_product_meta_controls(){

		$this->add_control(
            'show_image'
            ,array(
                'label' 		=> esc_html__( 'Product Image', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_title'
            ,array(
                'label' 		=> esc_html__( 'Product Name', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_sku'
            ,array(
                'label' 		=> esc_html__( 'Product SKU', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_price'
            ,array(
                'label' 		=> esc_html__( 'Product Price', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_short_desc'
            ,array(
                'label' 		=> esc_html__( 'Short Description', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_rating'
            ,array(
                'label' 		=> esc_html__( 'Product Rating', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_label'
            ,array(
                'label' 		=> esc_html__( 'Product Label', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_categories'
            ,array(
                'label' 		=> esc_html__( 'Product Categories', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_add_to_cart'
            ,array(
                'label' 		=> esc_html__( 'Add To Cart Button', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '1'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
	}
	
	public function add_product_color_swatch_controls(){
		$this->add_control(
            'show_color_swatch'
            ,array(
                'label' 		=> esc_html__( 'Color Swatches', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> esc_html__( 'Show the color attribute of variations. The slug of the color attribute has to be "color"', 'themesky' )
            )
        );
		
		$this->add_control(
            'number_color_swatch'
            ,array(
                'label' 		=> esc_html__( 'Number of Color Swatches', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> '3'
				,'options'		=> array(
						'2'		=> '2'
						,'3'	=> '3'
						,'4'	=> '4'
						,'5'	=> '5'
						,'6'	=> '6'
				)			
                ,'description' 	=> ''
                ,'condition' 	=> array( 'show_color_swatch' => '1' )
            )
        );
	}
	
	public function add_product_gallery_controls(){
		$this->add_control(
            'show_gallery'
            ,array(
                'label' 		=> esc_html__( 'Product Galleries', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )
				,'description' 	=> esc_html__( 'Please note that many images may make your site slower', 'themesky' )
            )
        );
		
		$this->add_control(
            'number_gallery'
            ,array(
                'label'     	=> esc_html__( 'Number of Product Galleries', 'themesky' )
                ,'type'     	=> Controls_Manager::NUMBER
				,'default'  	=> -1
				,'min'      	=> -1
				,'condition' 	=> array( 'show_gallery' => '1' )
            )
        );
		
		$this->add_control(
            'gallery_position'
            ,array(
                'label' 		=> esc_html__( 'Product Galleries Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'top'
				,'options'		=> array(
						'top'		=> esc_html__( 'Top', 'themesky' )
						,'bottom'	=> esc_html__( 'Bottom', 'themesky' )
				)			
                ,'description' 	=> esc_html__( 'Position of Galleries in product meta area', 'themesky' )
                ,'condition' 	=> array( 'show_gallery' => '1' )
            )
        );
	}
	
	public function add_product_slider_controls_basic(){
		
		$this->add_control(
            'show_nav'
            ,array(
                'label' 		=> esc_html__( 'Show Navigation', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'		
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'auto_play'
            ,array(
                'label' 		=> esc_html__( 'Auto Play', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
	}
	
	public function add_product_slider_controls_full(){
		
		$this->add_control(
            'show_dots'
            ,array(
                'label' 		=> esc_html__( 'Show Dots', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'show_scrollbar'
            ,array(
                'label' 		=> esc_html__( 'Show Scrollbar', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
                ,'default' 		=> '0'
				,'return_value' => '1'			
                ,'description' 	=> esc_html__( 'If enabled, dots and loop will be disabled', 'themesky' )
            )
        );
		
		$this->add_product_slider_controls_basic();
		
	}
	
	public function add_view_more_button_controls(){
		$this->add_control(
            'show_view_more_button'
			,array(
                'label' 		=> esc_html__( 'View More Button', 'themesky' )
                ,'type' 		=> Controls_Manager::SWITCHER
				,'default' 		=> '0'
				,'return_value' => '1'	
				,'label_on'		=> esc_html__( 'Show', 'themesky' )
				,'label_off'	=> esc_html__( 'Hide', 'themesky' )				
                ,'description' 	=> ''
            )
        );
		
		$this->add_control(
            'heading_view_more_option'
            ,array(
                'label'     	=> esc_html__( 'View More Options', 'themesky' )
                ,'type' 		=> Controls_Manager::HEADING		
                ,'description' 	=> ''
				,'separator'	=> 'before'
				,'condition' 	=> array( 
					'show_view_more_button' 	=> '1'
				)
            )
        );
		
		$this->add_control(
            'view_more_position'
            ,array(
                'label' 		=> esc_html__( 'View More Position', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'top'	
				,'options'		=> array(
					'top'		=> esc_html__( 'Top', 'themesky' )
					,'bottom'	=> esc_html__( 'Bottom', 'themesky' )
				)					
                ,'description' 	=> ''
                ,'condition' 	=> array( 
					'show_view_more_button' => '1'
				)
            )
        );
		
		$this->add_control(
            'view_more_style'
            ,array(
                'label' 		=> esc_html__( 'View More Button Style', 'themesky' )
                ,'type' 		=> Controls_Manager::SELECT
                ,'default' 		=> 'text'	
				,'options'		=> array(
					'text'		=> esc_html__( 'Text', 'themesky' )
					,'button'	=> esc_html__( 'Button', 'themesky' )
				)					
                ,'description' 	=> ''
                ,'condition' 	=> array( 
					'show_view_more_button' => '1'
				)
            )
        );
		
		$this->add_control(
            'view_more_text'
            ,array(
                'label' 		=> esc_html__( 'View More Button Text', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''
                ,'description' 	=> ''
				,'condition' 	=> array( 
					'show_view_more_button' => '1'
				)
            )
        );
		
		$this->add_control(
            'view_more_link'
            ,array(
                'label' 		=> esc_html__( 'View More Link', 'themesky' )
                ,'type' 		=> Controls_Manager::TEXT
                ,'default' 		=> ''
                ,'description' 	=> ''
				,'condition' 	=> array( 
					'show_view_more_button' => '1'
				)
            )
        );
	}
	
	public function view_more_button_html( $view_more_text, $view_more_link, $view_more_style ){
		if( !$view_more_text || !$view_more_link ){
			return;
		}
		
		if( $view_more_style == 'text' ){
			?>
			<div class="view-more-wrapper">
				<a class="button-text" href="<?php echo esc_url($view_more_link); ?>"><?php echo esc_html($view_more_text) ?></a>
			</div>
			<?php
		}
		else{
			?>
			<div class="view-more-wrapper">
				<a class="button button-border-2" href="<?php echo esc_url($view_more_link); ?>"><?php echo esc_html($view_more_text) ?></a>
			</div>
			<?php
		}
	}
	
	public function lazy_load_placeholder( $settings = array(), $type = 'product' ){
		if( !empty($settings['lazy_load']) && !wp_doing_ajax() && !( \Elementor\Plugin::instance()->editor->is_edit_mode() || \Elementor\Plugin::instance()->preview->is_preview_mode() ) ){
			
			$title_style = isset($settings['title_style']) ? $settings['title_style'] : '';
			?>
			<div class="ts-elementor-lazy-load type-<?php echo esc_attr($type); ?> <?php echo esc_attr($title_style); ?>">
			<?php
				$title 				= isset($settings['title']) ? $settings['title'] : '';
				
				$is_slider 			= !empty($settings['is_slider']) || $type == 'product-brand' || ( $type == 'logo' && $settings['layout'] == 'slider' );
				$only_slider_mobile = !empty($settings['only_slider_mobile']);
				if( $only_slider_mobile && !wp_is_mobile() ){
					$is_slider = false;
				}
				
				$columns 	= isset($settings['columns']) ? absint( $settings['columns'] ) : 5;
				$rows 		= isset($settings['rows']) && !wp_is_mobile() ? absint( $settings['rows'] ) : 1;
				$limit 		= isset($settings['limit']) ? absint( $settings['limit'] ) : 5;
				
				$blog_list = false;
				if( $type == 'blog' ){
					if( isset($settings['layout']) && $settings['layout'] == 'list' ){
						$columns = 1;
						$blog_list = true;
					}
				}
				
				if( $is_slider ){
					$count = min( $columns * $rows, $limit );
				}
				else{
					$count = min( $limit, $columns * 2 ); /* show max 2 rows */
				}
				
				$classes = array();
				$classes[] = 'columns-' . $columns;
				if( $is_slider ){
					$classes[] = 'is-slider';
				}
				if( $blog_list ){
					$classes[] = 'layout-list';
					if( isset($settings['thumbnail_position']) ){
						$classes[] = $settings['thumbnail_position'];
					}
				}
				
				if( $type == 'product-category' ){
					if( !empty($settings['thumbnail_radius']) ){
						$classes[] = 'thumbnail-radius';
					}
					if( !empty($settings['show_icon']) ){
						$classes[] = 'show-icon';
					}
					if( !empty($settings['item_style']) ){
						$classes[] = $settings['item_style'];
					}
				}
				
				if( $title ){
				?>
				<div class="placeholder-widget-title"></div>
				<?php
				}
				
				if( $type == 'product-tabs' ){
				?>
				<div class="placeholder-tabs">
					<div class="placeholder-tab-item"></div>
					<div class="placeholder-tab-item"></div>
				</div>
				<?php
				}
				?>
				
				<div class="placeholder-items <?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="--lazy-cols: <?php echo esc_attr( $columns ); ?>">
				<?php for( $i = 1; $i <= $count; $i++ ){ ?>
					<div class="placeholder-item">
						<div class="placeholder-thumb"></div>
						<?php if( $type != 'logo' ){ ?>
							<div class="placeholder-title"></div>
							<?php if( $type != 'product-category' && $type != 'product-brand' ){ ?>
								<div class="placeholder-subtitle"></div>
							<?php } ?>
						<?php } ?>
					</div>
				<?php } ?>
				</div>
			</div>
			<?php
			
			return true;
		}
		return false;
	}
}